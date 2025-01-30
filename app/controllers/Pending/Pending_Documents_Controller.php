<?php
/**
* @route:pending/documents
*/
class Pending_Documents_Controller extends APP_User_Controller
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
	function index()
	{
		$arrayStatus = Array();	
		foreach ($this->Encounter_Results_DB->get_status() as $key => $value) {
			
			if($key === 7 )
				continue;

			$dataStatus = [
				'id' => $key,
				'name' => $value,
				'checked' =>""
			];
			$arrayStatus[] = $dataStatus;
		}
		
		$status = $this->template->json_entities($arrayStatus);
		$types  = $this->template->json_entities($this->Encounter_Results_DB->get_results_availible());
		
		$this->template
			->set_title('Pending of Done Documents')
			->body([
				'ng-app' => 'app_check_requests',
				'ng-controller' => 'ctrl_check_requests',
				'ng-init' => 'startData('.$status.','.$types.')'
			])
			->modal('pending/modal-check-documents',[
				'title' => 'Revisar documento',
				'size' => 'modal-xl'
			])
			->js('pending/pending.check.documents')
			->render('pending/view.check.documents');
	}
	
	/**
	 * @route:{get}from-results/search/(:num)/(:num)
	 */
	function from_results_search( $maxRecords = 0, $page = 0)
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
	 * @route:{get}from-chart/search/(:num)/(:num)
	 */
	function from_chart_search( $maxRecords = 0, $page = 0)
	{
		
		$result = $this->Patient_Related_Files_DB->getPagination( 
			$maxRecords,
			$page, 
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		
		return $this->template->json( $result );
	}
}