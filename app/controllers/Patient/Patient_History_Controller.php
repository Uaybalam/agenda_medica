<?php
/**
 * @route:patient/history
 */
class Patient_History_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->validate_access(['manager','nurse', 'medic']);

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_History_Model' => 'Patient_History_DB'
		]);
	}

	/**
	 * @route:pdf/(:num)
	 */
	function pdf( $ID )
	{

		$this->load->library('print/PDF_Health_History');
		
		if( !$patient = $this->Patient_DB->get_info_history( $ID ) )
		{
			show_error('Patient not found',404);
		}
		
		$data 	=  $this->Patient_History_DB->get_data_pdf( $ID );
		
		$output = [];
		foreach ($data['positions'] as $P) {
			$output[$P->position] = [];
			foreach ($data['group'] as  $G) {
				$output[$G->position][$G->group] = [];
				
			}
		}
		
		foreach ($data['data'] as $C ) {
			$output[$C->position][$C->group_history][] = $C;
		}

		$this->pdf_health_history->body( 
			$patient, 
			$output
			//$information
		);
		
		$this->pdf_health_history->output();
	}

	/**
	 * @route:(:num)
	 */
	function preview( $ID )
	{
	
		if( ! ($patient = $this->Patient_DB->get($ID) ) || (int)$patient->recorded_history === 0 )
		{
			show_error('Paciente no encontrado o historial no registrado', 500 );
		}
		
		$this->template
			->set_title('Patient history')
			->body([
				'ng-app' => 'app_preview_history',
				'ng-controller' => 'ctrl_preview_history',
				'ng-init' => 'initialize('.$ID.')'
			])
			->js('patient/history.preview')
			->render('patient/history/view.panel.preview');
	}

	/**
	 * @route:capture/(:num)
	 */
	function capture( $ID )
	{

		if( ! ($patient = $this->Patient_DB->get($ID) ) )
		{
			show_error('Paciente no encontrado o historial no registrado.', 500 );
		}
		
		//first time
		if(!$patient->recorded_history)
		{
			$msg = "Por favor, complete el historial del paciente para ver el expediente médico.";
			$this->notify->error($msg);
		}
		
		$init = [
			'patient='.$this->template->json_entities($patient),
			'catalog_history='.$this->template->json_entities($this->Patient_History_DB->get_catalog_history( $ID ) ),
			'initialize('.$ID.')'
		];
		
		$this->template
			->set_title('Capture patient history')
			->body([
				'ng-app' => 'app_capture_history',
				'ng-controller' => 'ctrl_capture_history',
				'ng-init' => implode(';', $init )
			])
			->modal('patient/history/modal.confirm.history', [
					'size' => 'modal-xs',
					'title' => 'Por favor, confirme esta información'
				])
			->js('patient/history.capture')
			->render('patient/history/view.panel.capture');
	}

	/**
	 * @route:save/(:num)
	 */
	function save( $ID )
	{
		$response['status'] = 0;
		
		
		if( ! ($patient = $this->Patient_DB->get($ID) )  )
		{
			$response['message'] = 'Patient not found';
		}
		else if( !is_array($this->input->post('data')))
		{	
			$response['message'] = 'Data must be array';
		}
		else
		{	

			$this->Patient_History_DB->deleteBy(['patient_id' => $ID ]);

			$data 	   = $this->input->post('data');
			
			$save_data = [];
			
			foreach ($data as $item) {
				$title           = (isset($item['title']) 		&& $item['title']!='null') ? $item['title'] : '';
				$group           = (isset($item['group']) 		&& $item['group']!='null') ? $item['group'] : '';
				$disease_patient = (isset($item['patient'])		&& $item['patient']!='null') ? $item['patient'] : '';
				$disease_family  = (isset($item['family'])		&& $item['family']!='null') ? $item['family'] : '';
				$comments        = (isset($item['comments'])	&& $item['comments']!='null' ) ? $item['comments'] : '';
				$position        = (isset($item['position'])	&& $item['position']!='null' ) ? $item['position'] : '';
				
				if(!in_array($position , ['left','middle','right'])){
					continue;
				}
				
				$this->Patient_History_DB->patient_id    = $ID;
				$this->Patient_History_DB->title         = $title;
				$this->Patient_History_DB->group_history = $group;
				$this->Patient_History_DB->patient       = $disease_patient;
				$this->Patient_History_DB->family        = $disease_family;
				$this->Patient_History_DB->comments      = $comments;
				$this->Patient_History_DB->position      = $position;
				
				$this->Patient_History_DB->save();
			}
			
			$this->Patient_DB->recorded_history                     = 1;
			$this->Patient_DB->recorded_history_user_id             = $this->current_user->id;
			$this->Patient_DB->recorded_history_at                  = date('Y-m-d H:i:s');
			$this->Patient_DB->recorded_history_current_medications = trim($this->input->post('current_medications'));
			$this->Patient_DB->recorded_history_comments            = trim($this->input->post('comments'));
			$this->Patient_DB->recorded_history_surgeries           = trim($this->input->post('surgeries'));
			$this->Patient_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'Ok',
				'data' => $data,
				'redirect' => site_url('/patient/history/'. $ID )
			];
			
			$this->notify->success('Se creó el historial del paciente');
		}


		

		$this->template->json( $response );
	}

	/**
	 * @route:data/(:num)
	 */
	function data( $ID )
	{

		if( ! ($patient = $this->Patient_DB->get_info_history($ID) ))
		{
			show_error('Paciente no encontrado o historial no registrado. ', 500 );
		}
		
		$this->template->json([
			'patient' => $patient,
		]);
	}

	/**
	 * @route:init/(:num)
	 */
	function init( $ID )
	{

		if( ! ($patient = $this->Patient_DB->get_info_history($ID) ) || (int)$patient->recorded_history===0 )
		{
			show_error('Paciente no encontrado o historial no registrado. ', 500 );
		}
		
		$this->template->json([
			'patient' => $patient,
			'history_information' => $this->Patient_History_DB->get_info($ID) ,
		]);
	}
}