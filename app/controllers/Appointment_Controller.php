<?php
/**
* @route:appointment
*/
class Appointment_Controller extends APP_User_Controller
{	

	private $appointment_data = null;

	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Communication_Model' => 'Communication_DB',
			'Appointment_Model' => 'Appointment_DB',
			'Encounter_Model' => 'Encounter_DB',
			'Appointment_Event_Model' => 'Appointment_Event_DB'
		]);
	}

	/**
	 * @route:{get}pdf/(:num)
	 */
	function pdf( $ID )
	{

		$this->load->library('print/PDF_Appointment');
		
		if( !$appointment = $this->Appointment_DB->get_info_detail( $ID ) )
		{
			show_error('Cita no encontrada',404);
		}

		$appointment->status = $this->Appointment_DB->get_status_array($appointment->status);
		
		$this->pdf_appointment->body([
			'appointment' => $appointment,
			'patient_info' =>  $this->Patient_DB->get( $appointment->patient_id ),
			'user_info' => $this->User_DB->get( $appointment->create_user_by ),
			'events' => $this->Appointment_Event_DB->get_data(  $ID, 'DESC' ),
			'available_events' => $this->Appointment_Event_DB->get_events(),
		]);

		$this->pdf_appointment->output();
	}
	
	/**
	 * @route:{post}update/(:num)/(:any)
	 */
	function update($ID, $type = '' )
	{

		$response = [
			'status' => 0,
			'message' => 'Type not found '.$type
		];

		$appointment = $this->db->select([
				'status',
				'id',
				'visit_type',
				'date_appointment',
				'notes',
				'code',
				'insurance_type'
			])
			->where([ 'id' => $ID ])
			->get('appointment')
		    ->row();


		if($appointment)
		{
			$fields = $this->Appointment_DB->get_can_edit( $appointment->status );
				
			if( $type!=='cancel' && !isset($fields[$type]) )
			{
				$this->template->json( [
					'status' => 0,
					'message' => "Field {$type} not found ".print_r($fields,1)
				]);
			}
			else if( $type!=='cancel' && ( !$fields[$type] ) )
			{
				$this->template->json( [
					'status' => 0,
					'message' => "El campo de tipo {$type} no puede actualizar el valor [".$fields[$type]."]"
				]);
			}
		}
		else
		{
			$this->template->json( [
				'status' => 0,
				'message' => 'Cita no encontrada, por favor verifique el estado nuevamente.'
			]);
		}

	    if($type === 'appointment_date')
		{
			$this->form_validation
				->set_rules('date','Fecha','xss_clean|required|trim|exist_date|date_min_today')
				->set_rules('hour','Hora','xss_clean|required|trim');


			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else
			{
				$full_date = $this->input->post('date').' '.$this->input->post('hour');

				$this->Appointment_DB->date_appointment = date('Y-m-d H:i', strtotime($full_date));
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'Fecha y hora fue actualizada'
				];

				$date_before  = date('M d, Y h:i A', strtotime($appointment->date_appointment) );

				$this->add_appt_event($ID, 'update_date', sprintf( "Cambiado por: <b>%s</b>", $date_before ) );

			}
		}
		else if($type === 'visit_type')
		{
			$visit_types_avalibles = implode(",",$this->Appointment_DB->get_visit_types() );
			
			$this->form_validation->set_rules('visit_type','Tipo de visita','trim|required|in_list['.$visit_types_avalibles.']|same_value['.$appointment->visit_type.']');
			
			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else
			{
				
				$this->Appointment_DB->visit_type = $this->input->post('visit_type');
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'Tipo de visita fue actualizado'
				];

				$this->add_appt_event($ID, 'update_visit_type', sprintf("Cambiado por: <b>%s</b>", $appointment->visit_type));

			}
		}
		else if($type === 'notes')
		{
			$this->form_validation->set_rules('notes','Notes','trim|xss_clean|required|max_length[700]');
			
			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else
			{
				$this->Appointment_DB->notes = $this->input->post('notes');
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'Notas fueron actualizado'
				];

				$this->add_appt_event($ID, 'update_notes',  sprintf("Cambiado por: <b>%s</b>", $appointment->notes) );
			}
		}
		else if($type === 'code' )
		{
			$this->form_validation->set_rules('code','Codigo','trim|xss_clean|max_length[120]|same_value['.$appointment->code.']');
			
			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else
			{
				$this->Appointment_DB->code = $this->input->post('code');
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'El codigo fue actualizado'
				];

				$this->add_appt_event($ID, 'update_code' , sprintf("Cambiado por: <b>%s</b>", $appointment->code ) );
			}
		}
		else if($type === 'insurance_type')
		{
			$this->form_validation->set_rules('insurance_type','Insurance Type','trim|xss_clean|max_length[250]|same_value['.$appointment->insurance_type.']');
			
			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else
			{
				$this->Appointment_DB->insurance_type = $this->input->post('insurance_type');
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'Tipo de seguro fue actualizado'
				];
				
				$this->add_appt_event($ID, 'update_insurance_type' , sprintf("Cambiado por: <b>%s</b>", $appointment->insurance_type ) );
			}
		}
		else if($type === 'cancel')
		{
			$this->form_validation->set_rules('reason_cancel','Razón de cancelación','xss_clean|required|trim|max_length[120]');

			if($this->form_validation->run() === FALSE )
			{
				$response['message'] = $this->form_validation->error_string();
			}
			else if( !in_array( $appointment->status , [ 1, 2] ) )
			{
				$str = $this->Appointment_DB->get_status_array($appointment->status);
				$response['message'] = 'Cita con estatus ['.$str.'] no puede ser cancelada';
			}
			else
			{	
				$this->Appointment_DB->status        = 8;
				$this->Appointment_DB->reason_cancel = $this->input->post('reason_cancel'); 
				$this->Appointment_DB->save($ID);

				$response = [
					'status' => 1,
					'message' => 'La cita fue cancelada'
				];

				$this->add_appt_event($ID, 'cancel', sprintf("Razón de cancelación: <b>%s</b>", $this->input->post('reason_cancel')) );
			}
		}
		
		$response['appointment'] = $this->Appointment_DB->get_info_detail( $ID );
		$response['can_edit']    = $this->Appointment_DB->get_can_edit( $response['appointment']->status );
		$response['events']      = $this->Appointment_Event_DB->get_data( $ID );

		$this->template->json( $response );	
	}

	/**
	 * @route:{get}detail/(:num)
	 */
	function detail( $ID )
	{
		
		if( $appointment = $this->Appointment_DB->existID( $ID ) )
		{		
			$this->template
				->body([
					'ng-app' => 'ng_appointment_detail',
					'ng-controller' => 'ctrl_appointment_detail',
					'ng-init' => 'initialize('.$ID.')',
				])
				->set_title('Appointment detail')
				->js('','/assets/vendor/angular/angular-sanitize.min.js')
				->js('appointment/appointment.detail')
				->modal('appointment/modal.edit.detail',['title' => 'Actualizar cita'])
				->modal('appointment/modal.helpevents',['title' => 'Descripción de eventos'])
			    ->render('appointment/view.panel.appointment.detail');
		}
		else
		{
			redirect('/');
		}
	}

	/**
	 * @route:{get}detail/(:num)/initialize
	 */
	function detail_initialize( $ID )
	{
			
		if( !$appointment = $this->Appointment_DB->get_info_detail( $ID ) )
		{		
			$this->template->json(['message' => 'Cita no encontrada']);
		}
		
		$this->template->json( [
			'appointment' => $appointment,
			'can_edit' => $this->Appointment_DB->get_can_edit( $appointment->status ),
			'patient_info' =>  $this->Patient_DB->get( $appointment->patient_id ),
			'user_info' => $this->User_DB->get( $appointment->create_user_by ),
			'arr_visits' =>  $this->Appointment_DB->get_visit_types(),
			'arr_status' => $this->Appointment_DB->get_status_array(),
			'events' => $this->Appointment_Event_DB->get_data(  $ID ),
			'available_events' => $this->Appointment_Event_DB->get_events( [
				'can_edit' => $this->Appointment_DB->get_can_edit_str()
			]),
			'opened' => \libraries\Administration::getValue('opend'),
			'closed' => \libraries\Administration::getValue('closed'),
			'time'   => \libraries\Administration::getValue('appointment_time'),
		]);
	}


	/**
	 * @route:arrival/(:num)
	 */
	function arrival( $ID )
	{
		$this->validate_access(['manager','secretary','medic','nurse','reception']);

		$response['status'] = 0;

		if( ! ($appointment = $this->Appointment_DB->get($ID) ) )
		{
			$response['message'] = 'Cita no encontrada';
		}
		else if( (int)$appointment->status != 1 )
		{
			$response['message'] = 'Appointment is not status pending';		
		}
		else 
		{	
			$this->Appointment_DB->status          = 2;
			$this->Appointment_DB->time_arrival    = date('h:i A');
			$this->Appointment_DB->user_arrival_id = $this->current_user->id;
			$this->Appointment_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'Se asignó la fecha de llegada a la cita.',
				'appointment' => $this->Appointment_DB->get_info($ID)
			];

			$this->add_appt_event( $ID, 'arrival' );
		}

		$this->template->json( $response );
	}

	/**
	 * @route:chartup/(:num)
	 */
	function chartup( $ID )
	{
		$this->validate_access(['manager','secretary','medic','nurse','reception']);
		
		$response['status'] = 0;
		
		if( ! ($appointment = $this->Appointment_DB->get($ID) ) )
		{
			$response['message'] = 'Cita no encontrada';
		}
		else if(!in_array((int)$appointment->status,[1,2]) )
		{
			$response['message'] = 'La cita no está en estado pendiente ni de llegada.';		
		}
		else 
		{	
			$this->Appointment_DB->status          = 10;
			$this->Appointment_DB->time_chartup    = date('h:i A');
			$this->Appointment_DB->user_chartup_id = $this->current_user->id;
			$this->Appointment_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'Se asignó la fecha de llegada a la cita.',
				'appointment' => $this->Appointment_DB->get_info($ID)
			];

			$this->add_appt_event( $ID, 'chartup' );
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{post}reminder/(:num)
	 */
	function reminder( $ID )
	{
		//$this->validate_access(['manager','nurse']);
		
		$response['status'] = 0;
		$confirm            = (int)$this->input->post('confirm');
		
		if( ! ($appointment = $this->Appointment_DB->get($ID) ) )
		{
			$response['message'] = 'Cita no encontrada';
		}
		else if( (int)$appointment->status !== 1 )
		{
			$response['message'] = 'La cita no tiene el estado de Pendiente de llegada';		
		}
		else if($confirm !== 1 && trim($this->input->post('reminder_message'))==='' ) 
		{
			$response['message'] = 'El campo de mensaje es obligatorio';	
		}
		else
		{
			
			$message_out = '';

			if( $confirm === 1 )
			{
				$this->Appointment_DB->confirm      = 1;
				$this->Appointment_DB->date_confirm = date('Y-m-d H:i:s');
				$this->Appointment_DB->user_confirm = $this->current_user->nick_name;

				$message_out = 'Cita confirmada';

				$this->add_appt_event( $ID, 'reminder_confirm');
			}
			else
			{	
				$this->Appointment_DB->reminder_message = trim($this->input->post('reminder_message'));
				//$this->Appointment_DB->date_reminder    = date('Y-m-d H:i:s');
				//$this->Appointment_DB->user_reminder    = $this->current_user->nick_name;

				$message_out = 'Recordatorio de cita actualizado';

				/**
				 * Add Communication
				 */
				$this->Communication_DB->patient_id         = $appointment->patient_id;
				$this->Communication_DB->notes              = "Llamar paciente para cita {$ID}, " . trim($this->input->post('reminder_message') );
				$this->Communication_DB->type               = 0;
				$this->Communication_DB->created_by_user 	= $this->current_user->nick_name;
				$this->Communication_DB->create_at          = date('Y-m-d H:i:s');
				$this->Communication_DB->appointment_id     = $ID;
				$this->Communication_DB->save();

				$this->add_appt_event( $ID, 'reminder_not_confirm', 'Mensaje: '. trim($this->input->post('reminder_message')) );

			}

			$this->Appointment_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => $message_out,
				'appointment' => $this->Appointment_DB->get_info( $ID )
			];
		}
		
		$this->template->json( $response );
	}

	/**
	 * @route:{post}coming/(:num)
	 */
	function coming( $ID )
	{
		
		$this->validate_access(['manager','nurse','reception']);

		$response['status'] = 0;

		if( ! ($appointment = $this->Appointment_DB->get($ID) ) )
		{
			$response['message'] = 'Cita no encontrada';
		}
		else if( $this->Appointment_DB->get_last_appointment($appointment->patient_id))
		{
			$response['message'] = 'El paciente tiene una cita pendiente en consulta, por favor revise el expediente del paciente';
		}
		else if( (int)$appointment->status !== 10 )
		{
			$response['message'] = 'La cita no tiene el estado de llegada';		
		}
		else 
		{		
			$this->Appointment_DB->status 		= 3;
			$this->Appointment_DB->time_nurse 	= date('h:i A');
			$this->Appointment_DB->save( $ID );
			
			$redirectSite = site_url('/patient/chart/' . $appointment->patient_id );
			/*
			if( $appointment->visit_type==='Lab only' && $this->input->post('what_to_do') == 1  )
			{		
				if( $encounter_id = $this->Encounter_DB->get_last_encounter_id( $appointment->patient_id ) )
				{
					$redirectSite = site_url('/encounter/request/' . $encounter_id );
				}
			}
			*/

			$response = [
				'status' => 1,
				'message' => 'Cita asignada con enfermera',
				'appointment' => $this->Appointment_DB->get_info($ID),
				'redirect' => $redirectSite
			];

			$this->add_appt_event( $ID, 'set_nurse' );

		}

		$this->template->json( $response );
	}

	/**
	 * @route:room/(:num)
	 */
	function room( $ID )
	{
		$this->validate_access(['manager','nurse','reception']);
		
		$response['status'] = 0;
		
		$this->form_validation->set_rules('room','Nombre o numero','xss_clean|required|trim|max_length[20]');

		if( ! ($appointment = $this->Appointment_DB->get($ID) ) )
		{
			$response['message'] = 'Cita no encontrada';
		}
		else if( !in_array($appointment->status, [4,5,6,7] ) )
		{
			$response['message'] = 'La cita no tiene el estado de Signos Vitales';		
		}
		else if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();		
		}
		else 
		{	
			//KEEP FIRST TIME ROOM 
			if($appointment->room === '' )
			{
				$this->Appointment_DB->time_room = date('h:i A');
			}
			$this->Appointment_DB->room      = trim($this->input->post('room'));

			if( $appointment->status == 4 )
			{
				$this->Appointment_DB->status    = 5;
			}

			$this->Appointment_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'El cuarto fue asignado',
				'appointment' => $this->Appointment_DB->get_info($ID)
			];
			
			$this->add_appt_event( $ID, 'patient_room', sprintf( "Cuarto: <b>%s</b>", trim($this->input->post('room')) )  );
			
		}

		$this->template->json( $response );
	}
	
	/**
	 * @route:book
	 */
	function book()
	{

		$this->load->library('Mobile_Detect/Mobile_Detect', null);

		$catalog_status = $this->Appointment_DB->get_status( $this->current_user->access_type );
		$statusInclude = array();
		foreach ($catalog_status as $sta) {
			if($sta->checked) {
				$statusInclude[] = $sta->id;
			}
		}
		
		$init = [
			'catalog_status='.$this->template->json_entities( $catalog_status ),
			'statusInclude='.$this->template->json_entities( $statusInclude ),
			'visit_types='.$this->template->json_entities( $this->Appointment_DB->get_visit_types() ) 
		];
		
		$timeMinutesAlert = \libraries\Administration::getValue('minutes_waiting_doctor');
		$timeMinutesLate = \libraries\Administration::getValue('minutes_late_to_appointment');
		
		$minutes_waiting_doctor 	 = intval( $timeMinutesAlert ) ?  intval( $timeMinutesAlert ) : 5;
		$minutes_late_to_appointment = intval( $timeMinutesLate ) ?  intval( $timeMinutesLate ) : 5;
		
		//
		$this->template
			->set_title('Appointment book')
			->body([
					'ng-app' => 'ng_records_appointment',
					'ng-controller' => 'ctrl-records',
					'id' => 'ctrl-records',
					'ng-init' => implode(';',$init),
				])
			->modal('appointment/modal.arrival',[
					'title' => 'Confirmar que el paciente ha llegado',
					'size' => 'modal-md'
				])
			->modal('appointment/modal.coming',[
					'title' => 'Cambiar estado del paciente a con Asistente Médico',
					'size' => 'modal-md'
				])
			->modal('appointment/modal.reminder',[
					'title' => 'Actualizar/Confirmar recordatorio', 
				])
			->modal('appointment/modal.room',[
					'title' => 'Asignar cuarto a paciente',
					'size' => 'modal-md'
				])
			->modal('appointment/modal.chartup',[
					'title' => 'Preparar expediente del paciente', 
					'size' => 'modal-md'
				])
			->js('appointment/book')
			->render( 'appointment/view.panel.appointment.book',[
				'minutes_waiting_doctor' => $minutes_waiting_doctor,
				'minutes_late_to_appointment' => $minutes_late_to_appointment
			]);
	}


	/**
	 * @route:{get}create
	 */
	function create()
	{
		$init = [
			'visit_types='.$this->template->json_entities( $this->Appointment_DB->get_visit_types() ),
			'data_insurance_types='.$this->template->json_entities( $this->Custom_Setting_DB->getElements('setting_insurance',true) ),
			'initialize()'
		];
		
		$patient = false;
		if( $this->input->get('patient_id') ) 
		{
			$patient = $this->Patient_DB->get_info( $this->input->get('patient_id') );
		}
		
		$this->template
			->set_title('Crear cita')
			->body([
				'ng-app' => 'app_appointment',
				'ng-controller' => 'ctrl_appointment',
				'ng-init' => implode(';', $init)
			])
			->modal('patient/modal.create.basic', [
				'title' => 'Agregar Paciente'
			],[
				'settings_how_found_us' => implode(',', $this->Custom_Setting_DB->getElements('setting_how_found_us', true )),
				'insurance_plans' => $this->Custom_Setting_DB->getElements('setting_insurance',true)
			])
			->js('appointment/create')
			->render('appointment/view.panel.appointment.create',[
				'patient' => $patient
			]);	
	}

	/**
	 * @route:{post}save
	 */
	public function save()
	{
		$response = [
			'status' => 0
		];
	
		// Validation Rules
		$this->form_validation
			->set_rules('type_appointment', 'Tipo de cita', 'xss_clean|required|numeric|in_list[0,1]')
			->set_rules('visit_type', 'Tipo de visita', 'xss_clean|required|in_list[' . implode(',', $this->Appointment_DB->get_visit_types()) . ']')
			->set_rules('notes', 'Notas', 'trim|xss_clean|required|max_length[700]')
			->set_rules('patient_id', 'Paciente', 'xss_clean|required|trim|exist_data[patient.id]')
			->set_rules('code', 'Codigo', 'trim|xss_clean|max_length[120]')
			->set_rules('insurance_type', 'Tipo de seguro', 'trim|xss_clean|max_length[250]');
	
		// Handle Appointment Type
		if ((int)$this->input->post('type_appointment') === 0) {
			// Scheduled appointment
			$full_date = $this->input->post('date') . ' ' . $this->input->post('hour');
			$this->form_validation
				->set_rules('date', 'Fecha', 'xss_clean|required|trim|exist_date')
				->set_rules('hour', 'Hora', 'xss_clean|required|trim');
		} else {
			// Unscheduled appointment
			$full_date = date('Y-m-d H:i:s'); // Current timestamp
		}
	
		// Run Validation
		if ($this->form_validation->run() === FALSE) {
			$response['message'] = $this->form_validation->error_string();
		} else {
			// Prepare Appointment Data
			$appointment_data = [
				'code'             => $this->input->post('code') ?? '',
				'insurance_type'   => $this->input->post('insurance_type') ?? '',
				'visit_type'       => $this->input->post('visit_type'),
				'type_appointment' => $this->input->post('type_appointment'),
				'date_appointment' => date('Y-m-d H:i:s', strtotime($full_date)),
				'patient_id'       => $this->input->post('patient_id'),
				'notes'            => $this->input->post('notes'),
				'create_user_by'   => $this->session->userdata('user_id'),
				'status'           => (int)$this->input->post('type_appointment') === 1 ? 2 : 1
			];
	
			// Additional Fields for Unscheduled Appointments
			if ((int)$this->input->post('type_appointment') === 1) {
				$appointment_data['time_arrival'] = date('H:i:s', strtotime($full_date));
				$appointment_data['user_arrival_id'] = $this->session->userdata('user_id');
			}
	
			// Save to Database
			$appointment_id = $this->Appointment_DB->save($appointment_data);
	
			// Add Event Log
			$this->add_appt_event($appointment_id, 'create', "Tipo de cita: " . (($appointment_data['type_appointment'] === 1) ? "Sin cita" : "Con cita"));
	
			// Success Response
			$response = [
				'status' => 1,
				'message' => 'La cita fue creada'
			];
		}
	
		// Return JSON Response
		$this->template->json($response);
	}
	

	/**
	 * @route:records
	 */
	public function records()
{
    // Get the logged-in user's ID
    $user_id = $this->session->userdata('user_id');
    if (!$user_id) {
        // Unauthorized access
        echo json_encode(['error' => 'Unauthorized access']);
        return;
    }

    // Validate the 'date' parameter
    $date = $this->input->get('date');
    if (empty($date)) {
        $this->template->json([
            'message' => 'Selecionar una fecha es requerido',
            'appointments' => []
        ]);
        return;
    }

    // Fetch appointments for the logged-in user on the specified date
    $appointments = $this->Appointment_DB->get_by_user_and_date($user_id, $date);

    // Prepare response with other settings
    $response = [
        'appointments' => $appointments,
        'opened'       => \libraries\Administration::getValue('opend'),
        'closed'       => \libraries\Administration::getValue('closed'),
        'time'         => \libraries\Administration::getValue('appointment_time')
    ];

    // Return the response as JSON
    $this->template->json($response);
}


	public function _validate_charge( $ID )
	{
		$this->appointment_data = $this->Appointment_DB->get( (int)$ID );
		
		if(!$this->appointment_data)
		{
			$this->set_message('_validate_charge', 'Cita no encontrada');
			return FALSE;
		}
        else if( $this->appointment_data->status != 6 )
        {
        	$this->set_message('_validate_charge', 'La cita no tiene el estado de Esperando Salida');
        	return FALSE;
        }
        else
        {
        	return TRUE;
        }       
	}

}
