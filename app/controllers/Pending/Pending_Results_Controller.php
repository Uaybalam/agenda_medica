<?php
/**
* @route:pending/results
*/
class Pending_Results_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Encounter_Results_Model' => 'Encounter_Results_DB',
			'Patient_Related_Files_Model' => 'Patient_Related_Files_DB'
		]);
	}
	
	/**
	 * @route:__avoid__
	 */
	public function index()
	{
		$arrayStatus = Array();
		foreach ($this->Encounter_Results_DB->get_status() as $key => $value) {

			if($key === 7 )
				continue;

			$dataStatus = [
				'id' => $key,
				'name' => $value,
				'checked' => ""
			];
			$arrayStatus[] = $dataStatus;
		}
		
		$status = $this->template->json_entities($arrayStatus);
		$types  = $this->template->json_entities($this->Encounter_Results_DB->get_results_availible());
		
		$this->template
			->set_title('List of results')
			->body([
				'ng-app' => 'app_pending_results',
				'ng-controller' => 'ctrl_pending_results',
				'ng-init' => 'startData('.$status.','.$types.')'
			])
			->modal('encounter/request/modal.result',[
				'title' => 'Detalle del resultado',
				'size' => 'modal-lg'
			])
			->modal('patient/modal.create.contact', [
				'title' => 'Agregar razón de comunicación', 
				'size' => 'modal-md'
			])
			->js('pending/pending.results')
			->render('pending/view.pending.results');
	}

	/**
	 * @route:check
	 */
	function check()
	{
		$arrayStatus = Array();	
		foreach ($this->Encounter_Results_DB->get_status() as $key => $value) {
			
			if($key === 7 )
				continue;

			$dataStatus = [
				'id' => $key,
				'name' => $value,
				'checked' => (in_array($key, [3, 4] ) ) ? 1 : 0
			];
			$arrayStatus[] = $dataStatus;
		}
		
		$status = $this->template->json_entities($arrayStatus);
		$types  = $this->template->json_entities($this->Encounter_Results_DB->get_results_availible());
		
		$this->template
			->set_title('List of results')
			->body([
				'ng-app' => 'app_check_requests',
				'ng-controller' => 'ctrl_check_requests',
				'ng-init' => 'startData('.$status.','.$types.')'
			])
			->modal('encounter/request/modal.result',[
				'title' => 'Detalle del resultado',
				'size' => 'modal-md'
			])
			->modal('patient/modal.create.contact', [
				'title' => 'Agregar razón de comunicación', 
				'size' => 'modal-md'
			])
			->js('pending/check.requests')
			->render('pending/view.check.requests');
	}
	
	private function _view_render( $type )
	{
		
		$this->template
			->set_title('List of results pending')
			->body([
				'ng-app' => 'app_pending_results',
				'ng-controller' => 'ctrl_pending_results',
				'ng-init' => "initialize('".$type."')"
			])
			->modal('encounter/request/modal.result',[
				'title' => 'Detalle del resultado',
				'size' => 'modal-md'
			])
			->modal('patient/modal.create.contact', [
				'title' => 'Agregar razón de comunicación', 
				'size' => 'modal-md'
			])
			->js('pending/pending.results')
			->render('pending/view.pending.results');

	}
	
	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	function search( $maxRecords = 0, $page = 0)
	{	
		$result = $this->Encounter_Results_DB->getPagination( 
			$maxRecords, 
			$page, 
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		
		return $this->template->json( $result );
	}
	
	/**
	 * @route:{post}refreshStatus/(:num)
	 */
	public function refreshStatus( $encounter_result_id )
	{
		$result = $this->Encounter_Results_DB->get($encounter_result_id);
		if(!$result)
		{
			show_error('Result not found');
		}
		
		$status   = $this->input->post('status');
		$response = Array('status' => 0, 'message' => '');
		
		if($status==4)
			$response = $this->changeResultRecive($result);
		else if($status==5)
			$response = $this->changeResultDone($result);
		else if($status==6)
			$response = $this->changeResultRefused($result);
		else if($status==8)
			$response = $this->changeResultDocOnFile($result);
		else
			return $this->template->json([
				'status' => 0,
				'message' => 'Uncopateble status',
			]);

		if( $response['status'] )
		{
			$response['result']  = $this->Encounter_Results_DB->get_info( $encounter_result_id );
			$response['pending'] = Array(
				'waiting' => $this->Menu_DB->get_pending_results_waiting(),
				'check' => $this->Menu_DB->get_pending_results_check()
			);
		}

		return $this->template->json( $response);
	}

	private function changeResultDocOnFile( $result )
	{
		if($result->file_name)
		{
			return Array(
				'status' => 0,
				'message' => 'Please remove document'
			);
		}
		
		$this->form_validation
			->set_rules('doc_on_file_reason','Doc on File','trim|required')
		;
		
		if($this->form_validation->run() === FALSE )
		{
			return Array(
				'status' => 0,
				'message' => $this->form_validation->error_string()
			);
		}

		$this->Encounter_Results_DB->status               = 8;
		$this->Encounter_Results_DB->doc_on_file_date     = date('Y-m-d H:i:s');
		$this->Encounter_Results_DB->doc_on_file_reason   = $this->input->post('doc_on_file_reason');
		$this->Encounter_Results_DB->doc_on_file_nickname = $this->current_user->nick_name;
		$this->Encounter_Results_DB->save( $result->id );
		
		return Array(
			'status' => 1,
			'message' => 'Results change to status Doc on File'
		);
	}

	private function changeResultRecive( $result )
	{
		if(!$result->file_name)
		{
			return [
				'status' => 0,
				'message' => 'Require upload document'
			];
		}
		
		if(!$this->input->post('title_document'))
			return [
				'status' => 0,
				'message' => 'Capture Document title'
			];
			
		$this->Encounter_Results_DB->status         = 4;
		$this->Encounter_Results_DB->title_document = $this->input->post('title_document');
		$this->Encounter_Results_DB->save( $result->id );

		return [
			'status' => 1,
			'message' => 'Results change to status  Recive'
		];	
	}

	private function changeResultDone( $result )
	{
		if(!$result->file_name)
		{
			return Array(
				'status' => 0,
				'message' => 'Require upload document'
			);
		}
		
		$this->form_validation
			->set_rules('title_document','Doc Title','trim|required')
			->set_rules('pin','Pin','required|pin_verify');

		if($this->input->post('contact_patient') == 1)
			$this->form_validation->set_rules('reason_contact', 'Reason Contact','required|trim');
		
		if($this->form_validation->run() === FALSE )
			return Array(
				'status' => 0,
				'message' => $this->form_validation->error_string()
			);

		$this->Encounter_Results_DB->title_document= $this->input->post('title_document');
		$this->Encounter_Results_DB->status        = 5;
		$this->Encounter_Results_DB->done_date     = date('Y-m-d H:i:s');
		$this->Encounter_Results_DB->done_nickname = $this->current_user->nick_name;
		$this->Encounter_Results_DB->save( $result->id );
		
		$this->Patient_Related_Files_DB->title           = $this->input->post('title_document');
		$this->Patient_Related_Files_DB->user_id_created = $this->current_user->id;
		$this->Patient_Related_Files_DB->type            = $result->type_result;
		$this->Patient_Related_Files_DB->file_name       = $result->file_name;
		$this->Patient_Related_Files_DB->patient_id      = $result->patient_id;
		$this->Patient_Related_Files_DB->create_at       = date('Y-m-d H:i:s');
		$related_file_id = $this->Patient_Related_Files_DB->save();

		if($this->input->post('contact_patient') == 1 )
		{
			$this->load->model(['Patient_Contact_Model' => 'Patient_Contact_DB']);

			$this->Patient_Contact_DB->related_file_id = $related_file_id;
			$this->Patient_Contact_DB->create_user_by  = $this->current_user->id;
			$this->Patient_Contact_DB->reason          = $this->input->post('reason_contact');
			$this->Patient_Contact_DB->patient_id      = $result->patient_id;
			$this->Patient_Contact_DB->create_at       = date('Y-m-d H:i:s');
			$this->Patient_Contact_DB->save();
		}

		return Array(
			'status' => 1,
			'message' => 'Results change to status Done'
		);	
	}

	private function changeResultRefused( $result )
	{
		if($result->file_name)
		{
			return Array(
				'status' => 0,
				'message' => 'Remove document for refuse result'
			);
		}

		$this->form_validation
			->set_rules('refused_reason','Reason','trim|required|max_length[5000]')
			->set_rules('pin','Pin','required|pin_verify');

		if($this->form_validation->run() === FALSE )
			return Array(
				'status' => 0,
				'message' => $this->form_validation->error_string()
			);

		$this->Encounter_Results_DB->status           = 6;
		$this->Encounter_Results_DB->refused_date     = date('Y-m-d H:i:s');
		$this->Encounter_Results_DB->refused_reason   = $this->input->post('refused_reason');
		$this->Encounter_Results_DB->refused_nickname = $this->current_user->nick_name;
		$this->Encounter_Results_DB->save( $result->id );

		return Array(
			'status' => 1,
			'message' => 'Results change to status Refused'
		);	
	}

}