<?php
/**
 * @route:patient/warning
 */
class Patient_Warning_Controller extends APP_User_Controller
{	
	
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Warnings_Model' => 'Patient_Warnings_DB',
			'Menu_Model' => 'Menu_DB',
		]);

		$this->Menu_DB->current_user = $this->current_user;
	}
	
	/**
	 * @route:{post}create
	 */
	function create()
	{
		$response['status'] = 0;

		$this->form_validation
			->set_rules('description', 'Description','trim|xss_clean|required|max_length[300]')
			->set_rules('patient_id', 'Patient ID','trim|xss_clean|required|exist_data[patient.id]')
			->set_rules('request_reply', 'Request reply','in_list[0,1]')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{		
			$user_full_name = trim($this->current_user->names.' '.$this->current_user->last_name);
			$this->Patient_Warnings_DB->description       = $this->input->post('description');
			$this->Patient_Warnings_DB->patient_id        = $this->input->post('patient_id');
			$this->Patient_Warnings_DB->request_reply     = $this->input->post('request_reply');
			$this->Patient_Warnings_DB->description_reply = '';
			
			$this->Patient_Warnings_DB->status 		  = (
				$this->input->post('request_reply') == 1
			) ? 2 : 0;
			
			$this->Patient_Warnings_DB->user_create   = $user_full_name;
			
			$warning_id = $this->Patient_Warnings_DB->save();

			$response = [
				'status' => 1,
				'message' => 'Alerta agregada',
				'warning' => $this->Patient_Warnings_DB->get($warning_id),
				'pending' => $this->Menu_DB->get_pending_warnings()
			];
		}

		$this->template->json( $response );
	}
	
	/**
	 * @route:{get}remove/(:num)
	 */
	function remove( $id )
	{
		$response['status'] = 0;

		if( ! ($warning = $this->Patient_Warnings_DB->get($id)) )
		{
			$response['message'] = 'Alerta no encontrada';
		}
		else if($warning->status == 1 )
		{
			$response['message'] = 'Alerta con estatus removida';
		}
		else
		{

			$user_full_name = trim($this->current_user->names.' '.$this->current_user->last_name);

			$this->Patient_Warnings_DB->status      = 1;
			$this->Patient_Warnings_DB->user_remove = $user_full_name;
			$this->Patient_Warnings_DB->save( $id );

			$response = [
				'status' => 1,
				'message' => 'Alerta fue removida',
				'warning' => $this->Patient_Warnings_DB->get( $id ),
				'pending' => $this->Menu_DB->get_pending_warnings()	
			];
		}

		$this->template->json( $response );
	}
	
	/**
	 * @route:{post}(:num)/update-reply
	 */
	function update_reply( $ID )
	{

		$this->validate_access(['manager','medic']);

		$warning = $this->Patient_Warnings_DB->get( $ID );
		
		if( !$warning )
		{
			$this->template->json([
				'message' => 'Alerta no encontrada'
			]);
		}
		if( !in_array($warning->status, [2,3]) )
		{	
			$this->template->json([
				'message' => 'Estatus de alerta no repondida o pendiente de remover'
			]);
		}
		
		$this->form_validation
			->set_rules('description_reply', 'Descripción','trim|xss_clean|required|max_length[400]')
		;

		if($this->form_validation->run() === FALSE )
		{
			$this->template->json([
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{
			$user_full_name = trim($this->current_user->names.' '.$this->current_user->last_name);
			$this->Patient_Warnings_DB->description_reply = $this->input->post('description_reply');
			$this->Patient_Warnings_DB->user_reply 		  = $user_full_name;
			$this->Patient_Warnings_DB->status 			  = 3;
			$this->Patient_Warnings_DB->save( $ID );

			$this->template->json([
				'status' => 1,
				'message' => 'Respuesta fue enviada',
				'warning' => $this->Patient_Warnings_DB->get( $ID ),
				'pending' => $this->Menu_DB->get_pending_warnings()	
			]);
		}
	}
}