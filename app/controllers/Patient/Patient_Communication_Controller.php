<?php
/**
 * @route:patient/communication
 */
class Patient_Communication_Controller extends APP_User_Controller
{
	
	function __construct()
	{	
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Contact_Model' => 'Contact_DB',
			'Patient_Communication_Model' => 'Communication_DB',
			'Appointment_Model' => 'Appointment_DB',
		]);
	}

	/**
	 * @route:pdf/(:num)
	 */
	function pdf( $ID )
	{	
		$this->load->library('print/PDF_Communications');
		
		if( !$patient = $this->Patient_DB->get_info( $ID ) )
		{
			show_error('Patient not found',404);
		}
		
		$from = ($this->input->get('from')!='') ? 
			date('Ymd', strtotime(clear_var( $this->input->get('from'))) ) : '';
		$to = ($this->input->get('to')!='') ?
			date('Ymd', strtotime(clear_var( $this->input->get('to'))) ) : '';
			
		$params = [
			'type_communications' => $this->Communication_DB->get_available_types(),
			'filter_from' => clear_var($this->input->get('from')),
			'filter_to' => clear_var($this->input->get('to'))  
		];

		$this->pdf_communications->body( $patient , 
			$this->Communication_DB->get_history_by_patient( $ID , $from , $to ),
			$params 
		);
		
		$this->pdf_communications->output();
	}

	/**
	 * @route:{post}save
	 */
	function save()
	{
		
		$this->form_validation
			->set_rules('patient_id','Paciente','trim|required|xss_clean|exist_data[patient.id]')
			->set_rules('notes','Notas', 'trim|required|xss_clean');
		
		$full_date = '';

		if($this->input->post('has_appointment') === 'true')
		{
			$this->_rules_appointment( $this->form_validation );
			$full_date = $this->input->post('date').' '
						.$this->input->post('hour');		

		}
		
		$contact = ( $this->input->post('contact_id') > 0 )  ? $this->Contact_DB->get( $this->input->post('contact_id')) : null;

		if($contact && $contact->status == 1)
		{
			$response['message'] = 'Estatus de solicitud de contacto completo';
		}
		else if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{
			$Msg_success 	= [];
			$appointment_id = 0;
			
			if($this->input->post('has_appointment') === 'true' )
			{	
				$visit_type_avalible = $this->Appointment_DB->get_visit_types(); 
				
				$this->Appointment_DB->status           = 1;
				$this->Appointment_DB->code             = $this->input->post('code'); 
				$this->Appointment_DB->visit_type       = $visit_type_avalible[$this->input->post('visit_type')]; 
				$this->Appointment_DB->type_appointment = ( $type_appointment = $this->input->post('type_appointment') ) ? $type_appointment : 0; 
				$this->Appointment_DB->patient_id       = $this->input->post('patient_id');
				$this->Appointment_DB->notes            = trim($this->input->post('notes_appointment'));
				
				$this->Appointment_DB->date_appointment = date('Y-m-d H:i:00', strtotime( $full_date ));
				$this->Appointment_DB->create_user_by = $this->current_user->id;
				
				$appointment_id = $this->Appointment_DB->save();
				
				$Msg_success[]= 'Cita fue creada';
				
				$this->add_appt_event($appointment_id,'created_from_communication');
			}
			
			if( $this->input->post('close_pending')==='true' && $contact)
			{
				$this->Contact_DB->status         = 1;
				$this->Contact_DB->update_user_by = $this->current_user->id;
				$this->Contact_DB->update_at      = date('Y-m-d H:i:s');
				$this->Contact_DB->save( $contact->id );
				
				$Msg_success[]= 'solicitud de contacto fue actualizada';
			}
			
			$this->Communication_DB->patient_id         = $this->input->post('patient_id');
			$this->Communication_DB->notes              = $this->input->post('notes');
			$this->Communication_DB->type               = (int)$this->input->post('type');
			$this->Communication_DB->created_by_user 	= $this->current_user->nick_name;
			
			$this->Communication_DB->appointment_id     = $appointment_id;
			$this->Communication_DB->patient_contact_id = $this->input->post('contact_id');
			$this->Communication_DB->create_at          = date('Y-m-d H:i:s');
			
			$ID = $this->Communication_DB->save();
			$Msg_success[]= 'Solicitud de contacto fue añadida';

			$response = [
				'status' => 1,
				'message' => implode("<br>",$Msg_success)
			];
			
			if($this->input->post('redirect'))
			{		
				$response['redirect'] = true;
				$this->notify->success(implode("<br>",$Msg_success));
			}
			else
			{	
				$response['item'] = $this->Communication_DB->get_by_id( $ID );
			}
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{get}(:num)/appointment
	 */
	function appointment( $id )
	{
		$this->template->json([
			'communications' => $this->Communication_DB->get_history_by_appointment( $id ) 
		]);
	}

	/**
	 * @route:history/(:num)
	 */
	function history( $ID )
	{	
		$this->template->json([
			'history_communications' => $this->Communication_DB->get_history_by_patient( (int)$ID )
		]);
	}

	private function _rules_appointment( $form_validation)
	{
		$form_validation
			->set_rules('visit_type','Tipo de visita','xss_clean|required|numeric|in_list[0,1,2,3]')
			->set_rules('code','Codigo','trim|xss_clean|max_length[120]')
			->set_rules('date','Fecha','xss_clean|required|trim|exist_date')
			->set_rules('hour','Hora','xss_clean|required|trim') 
		;
	}
}