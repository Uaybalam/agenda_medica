<?php
/**
* @route:encounter/request
*/
class Encounter_Request_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		
		parent::__construct();
		
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Medication_Model' => 'Encounter_Medication_DB',
			'Encounter_Referrals_Model' => 'Encounter_Referrals_DB',
			'Encounter_Results_Model' => 'Encounter_Results_DB',
			'Encounter_Invoice_Model' => 'Encounter_Invoice_DB',
			'Encounter_Addendum_Model' => 'Encounter_Addendum_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Appointment_Model' => 'Appointment_DB',
			'Custom_Setting_Model' => 'Custom_Setting_DB'
		]);
	}
	
	/**
	 * @route:(:num)
	 */
	function index( $ID  )
	{	
		$encounter    = $this->Encounter_DB->getRowBy( [ 'id' => $ID, 'status' => 2 ] );
			
		if(!$encounter)
		{
			show_error('Consulta no encontrada o no firmada', 404 );
		}

		$patient = $this->Patient_DB->get_info( $encounter->patient_id );

		$titlePreviousCharges = sprintf("Historial de Cargos del Paciente: %s ", $patient->name.' '.$patient->last_name );

		$diagnosis = $this->Encounter_Diagnosis_DB->getAll(['encounter_id' => $ID]);
		
		$this->template
			->set_title('Encounter requests')
			->body([
				'ng-app' => 'app_request_encounter',
				'ng-controller' => 'ctrl_request_encounter',
				'ng-init' => 'initialize('.$ID.')'
			])
			->modal('referr/modal-print',[
				'title' => 'Provedor de derivación'
			], [
				'diagnosis' => $diagnosis,
				'referr_status' => $this->Encounter_Referrals_DB->getStatus()
			])
			->modal('encounter/detail/modal.referrals',['title' => 'Derivaciones'] )
			->modal('encounter/request/modal.previous.charges',['title' => $titlePreviousCharges, 'size' => 'modal-xl' ])
			->modal('encounter/request/modal.checkout.cancel', ['title' => 'Cancelar el Pago de Solicitudes', 'size' => 'modal-md' ])
			->modal('encounter/request/modal.done', ['title' => 'Cerrar consulta y factura', 'size' => 'modal-md' ])
			->modal('encounter/request/modal.invoice', ['title' => 'Factura de consulta' ])
			->modal('encounter/detail/modal.addendum', ['title' => 'Addendum'])
			->js('encounter/request.index')
			->render('encounter/request/view.panel.encounter.request');
	}

	/**
	 * @route:{get}initialize/(:num)
	 */
	function initialize( $ID )
	{
		
		if( !$encounter = $this->Encounter_DB->get_info_request($ID) )
		{
			show_error('Consulta no encontrada o no firmada ', 404 );
		}
		
		$status = [
			'referral' => $this->Encounter_Referrals_DB->getStatus(),
			'result' => $this->Encounter_Results_DB->get_status(),
			'payment_types' => $this->Encounter_Invoice_DB->get_payment_types()
		];
			
		$data = [
			'patient' => $this->Patient_DB->get_info($encounter->patient_id),
			'patient_open_balance' => $this->Patient_DB->get_open_balance( $encounter->patient_id ),
			'encounter' => $encounter,
			'invoice' => $this->Encounter_Invoice_DB->get_info($ID),
			'medications' => $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $ID]),
			'referrals' => $this->Encounter_Referrals_DB->getResultsBy(['encounter_id' => $ID]),
			'results' => $this->Encounter_Results_DB->getResultsBy(['encounter_id' => $ID]),
			'status' => $status,
			'checked_out' => $this->Encounter_DB->get_checked_out( $encounter->checked_out_id ),
			'addendums' => $this->Encounter_Addendum_DB->get_data(  $ID , FALSE ),
			'catalog_refer_services' => $this->Custom_Setting_DB->getElements('setting_referral_service', true),
			'catalog_specialities' =>  $this->Custom_Setting_DB->getElements('setting_referral_specialty', true ), 
		];
		
		$this->template->json( $data , 'JSON_PRESERVE_ZERO_FRACTION' );
	}

	/**
	 * @route:{post}invoice-update/(:num)
	 */
	function invoice_update( $ID )
	{
		
		$invoice = $this->Encounter_Invoice_DB->get_info( $ID );

		if(!$invoice)
		{
			$this->template->json( ['message' => 'Factura no encontrada' ] );	
		}
		else if( $invoice->status != 0 )
		{	
			$this->template->json( ['message' => 'Factura finalizada'  ] );	
		}

		$OPTIONS_PAYMENT = implode(',', $this->Encounter_Invoice_DB->get_payment_types() );

		$this->form_validation
			->set_rules('office_visit','Visita a Consultorio','xss_clean|numeric')
			->set_rules('injections','Inyecciones','xss_clean|numeric')
			->set_rules('discount','Descuento','xss_clean|numeric')
			->set_rules('medications','Medicaciones','xss_clean|numeric')
			->set_rules('procedures','Procedimientos','xss_clean|numeric')
			->set_rules('physical','INS físisco','xss_clean|numeric')
			->set_rules('ecg','ECG','xss_clean|numeric')
			->set_rules('ultrasound','Ultrasonido','xss_clean|numeric')
			->set_rules('x_ray','Co-Pago','xss_clean|numeric')
			->set_rules('print_cost','Imprimir','xss_clean|numeric')
			->set_rules('payment_type','Tipo de pago','required|in_list['.$OPTIONS_PAYMENT.']')
			->set_rules('open_balance','Abrir balance','trim|numeric')
			->set_rules('discount_type','Tipo de descuento','trim|xss_clean|max_length[250]')
		;

		
		if ( $this->form_validation->run() === false) 
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{		
			
			$subtotal = floatval($this->input->post('office_visit'))
				+ floatval($this->input->post('laboratories'))
				+ floatval($this->input->post('injections'))
				+ floatval($this->input->post('medications'))
				+ floatval($this->input->post('procedures'))
				+ floatval($this->input->post('physical'))
				+ floatval($this->input->post('ecg'))
				+ floatval($this->input->post('ultrasound'))
				+ floatval($this->input->post('x_ray'))
				+ floatval($this->input->post('print_cost'))
			;
			
			$total = $subtotal
				+ $invoice->open_balance
				- floatval($this->input->post('discount'))
			;
			
			$this->Encounter_Invoice_DB->discount_type = $this->input->post('discount_type');
			$this->Encounter_Invoice_DB->open_balance  = $invoice->open_balance;
			
			$this->Encounter_Invoice_DB->payment_type  = $this->input->post('payment_type');
			$this->Encounter_Invoice_DB->office_visit  = $this->input->post('office_visit');
			
			$this->Encounter_Invoice_DB->laboratories  = $this->input->post('laboratories');
			$this->Encounter_Invoice_DB->injections    = $this->input->post('injections');
			$this->Encounter_Invoice_DB->medications   = $this->input->post('medications');
			$this->Encounter_Invoice_DB->procedures    = $this->input->post('procedures');
			$this->Encounter_Invoice_DB->physical      = $this->input->post('physical');
			$this->Encounter_Invoice_DB->ecg           = $this->input->post('ecg');
			$this->Encounter_Invoice_DB->ultrasound    = $this->input->post('ultrasound');
			$this->Encounter_Invoice_DB->x_ray         = $this->input->post('x_ray');
			$this->Encounter_Invoice_DB->print_cost    = $this->input->post('print_cost');
			$this->Encounter_Invoice_DB->discount      = $this->input->post('discount');
			$this->Encounter_Invoice_DB->subtotal      = $subtotal;
			$this->Encounter_Invoice_DB->total         = $total;
			$this->Encounter_Invoice_DB->paid          = $this->input->post('paid');
			$this->Encounter_Invoice_DB->balance_due   = $this->input->post('balance_due');
			
			$this->Encounter_Invoice_DB->save( $invoice->id );
			
			$response = [
				'status' => 1,
				'message' => 'Cargo fue actualizado',
				'invoice' => $this->Encounter_Invoice_DB->get_info( $invoice->encounter_id ) 
			];
		}

		$this->template->json( $response , 'JSON_PRESERVE_ZERO_FRACTION' );	
	}

	/**
	 * @route:{post}(:num)/set-done
	 */
	function set_done( $ID )
	{
		$encounter = $this->Encounter_DB->getRowBy( [ 
			'id' => $ID, 
			'status' => 2,
			"checked_out_id" => 0
		]);

		if(!$encounter)
		{	
			$this->template->json([
				'message' => 'Consulta no encontrada o checkout completado'
			]);
		}

		if( $response_message = $this->Encounter_Results_DB->exist_new( $encounter->id ) )
		{		
			$this->template->json([
				'message' => "Resultado  <b>{$response_message->title}</b> se requiere establecer el estado como pendiente o rechazado"
			]);
		}
		
		$this->form_validation
			->set_rules('pin', 'PIN de usuario', 'trim|xss_clean|required|pin_verify')
		;
		
		if($this->form_validation->run() === FALSE )
		{
			$this->template->json([
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{		
			$invoice = $this->Encounter_Invoice_DB->get_info( $ID );
			
			$this->db->insert('checked_out', [
				'user_id' => $this->current_user->id,
				'patient_id' => $encounter->patient_id,
				'digital_signature' => $this->current_user->digital_signature,
 				'created_at' => date("Y-m-d H:i"),
			]);
			
			$checked_out_id = $this->db->insert_id();
			$printURL       = "";
			
			if($invoice)
			{	
				//Patient
				//set new open Balance?
				$this->Patient_DB->open_balance = $invoice->balance_due; 
				$this->Patient_DB->save( $encounter->patient_id );
				
				//Invoice
				$this->Encounter_Invoice_DB->status = 1;
				$this->Encounter_Invoice_DB->save( $invoice->id );

				if( $invoice->enabled )
					$printURL =  site_url('/encounter/invoice/'.$ID.'/pdf');
			}
			
			//Waiting results
			$this->Encounter_Results_DB->checked_out_id = $checked_out_id;
			$this->Encounter_Results_DB->update(['encounter_id' => $ID ]);
			
			if( $encounter->appointment_id )
			{
				//Appointment
				$this->Appointment_DB->status         = 7;
				$this->Appointment_DB->time_done      = date('h:i A');
				$this->Appointment_DB->checked_out_id = $checked_out_id;
				$this->Appointment_DB->save( $encounter->appointment_id );
				
				$this->add_appt_event( $encounter->appointment_id, 'checkout');
			}
			
			//Encounter
			$this->Encounter_DB->checked_out_id = $checked_out_id;
			$this->Encounter_DB->save( $ID );
			
			$this->template->json([
				'status' => 1,
				'message' => "Cita cambio a checkout",
				'pending' => $this->Menu_DB->get_pending_results(),
				'redirect' => $printURL,
			], 'JSON_PRESERVE_ZERO_FRACTION');
		}
	}
	
	/**
	 * @route:{get}(:num)/change-result/(1|2|3|5|6|7)
	 */
	function change_result( $result_id, $set_new_status )
	{

		if( ! $result = $this->Encounter_Results_DB->get( $result_id ) )
		{
			$this->template->json(['message' => 'Resultado no encontrado']);
		}

		if(!in_array($result->status, [1,2,3,5,6, 7 ] ))
		{
			$this->template->json(['message' => 'Revisar estatus de Resultado']);
		}
		else
		{	

			$this->Encounter_Results_DB->status = $set_new_status;
			$this->Encounter_Results_DB->save( $result_id );
			
			$this->template->json([
				'status' => 1,
				'message' => 'Resultado fue actualizado',
				'pending' => $this->Menu_DB->get_pending_results()
			]);
		}
	}

	/**
	 * @route:{post}create-addendum/(:num)
	 */
	function create_normal( $ID )
	{
		$response['status'] = 0;

		if( ! ( $encounter = $this->Encounter_DB->get($ID) ) )
		{
			show_error('Consulta no encontrada' , 404);
		}else if( $encounter->status === 1 )
		{	
			show_error('Consulta no encontrada o estatus en proceso' , 404);
		}

		$this->form_validation
			->set_rules('notes','Comentario de diagnostico','required|xss_clean|trim')
			->set_rules('password','PIN de usuario','required|pin_verify')
		;

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{		

			$this->Encounter_Addendum_DB->is_request   = 1;

			$this->Encounter_Addendum_DB->encounter_id = $encounter->id;
			$this->Encounter_Addendum_DB->patient_id   = $encounter->patient_id;
			$this->Encounter_Addendum_DB->user_id      = $this->current_user->id;
			$this->Encounter_Addendum_DB->create_at    = date('Y-m-d H:i:s');
			$this->Encounter_Addendum_DB->notes        = $this->input->post('notes');
			$addendum_id = $this->Encounter_Addendum_DB->save();
			
			$response = [
				'status' => 1,
				'message' => 'Addendum fue agregado',
				'addendum' => $this->Encounter_Addendum_DB->get_detail( $addendum_id ),
			];

		}


		$this->template->json( $response );
	}

	/**
	 * @route:{post}(:num)/cancel
	 */
	public function cancel( $ID )
	{
		$this->validate_access(['manager']);
		
		$encounter = $this->Encounter_DB->getRowBy( [ 
			'id' => $ID, 
			'status' => 2
		]);

		if(!$encounter || $encounter->checked_out_id== 0)
		{	
			$this->template->json([
				'message' => 'No se encontró la consultao la salida está pendiente'
			]);
		}

		$checkedOut = $this->db->from('checked_out')->where(['id' => $encounter->checked_out_id])->get()->row_array();
		if(!$checkedOut){
			$this->template->json([
				'message' => 'ID de salida no encontrado {$checked_out_id}'
			]);
		}

		$this->form_validation
			->set_rules('pin', 'PIN de usuario', 'trim|xss_clean|required|pin_verify')
			->set_rules('reason_cancel', 'Rázon', 'trim|xss_clean|required')
		;

		if($this->form_validation->run() === FALSE )
		{
			$this->template->json([
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{		
			//$checkedOut
			$invoice = $this->Encounter_Invoice_DB->get_info( $ID );
			$patient = $this->Patient_DB->get($encounter->patient_id);

			$this->db->where(['id' => $encounter->checked_out_id])->delete('checked_out');

			if($invoice)
			{	
				//Patient
				$this->Patient_DB->open_balance = floatval($patient->open_balance) - floatval($invoice->open_balance); 
				$this->Patient_DB->save( $patient->id );
				
				//Invoice
				$this->Encounter_Invoice_DB->status = 0;
				$this->Encounter_Invoice_DB->save( $invoice->id );
			}
			
			//Waiting results
			$this->Encounter_Results_DB->checked_out_id = 0;
			$this->Encounter_Results_DB->update(['encounter_id' => $ID ]);
			
			$notes = sprintf("Razón de canlar salida: <b>%s</b>", $this->input->post('reason_cancel')  );
			
			if( $encounter->appointment_id )
			{
				//Appointment
				$this->Appointment_DB->status         = 6;
				$this->Appointment_DB->time_done      = '';
				$this->Appointment_DB->checked_out_id = 0;
				$this->Appointment_DB->save( $encounter->appointment_id );
				
				$this->add_appt_event( $encounter->appointment_id, 'cancel_checkout', $notes );
			}
			else
			{
				//add Addendum
				$this->Encounter_Addendum_DB->is_request   = 1;
				$this->Encounter_Addendum_DB->encounter_id = $encounter->id;
				$this->Encounter_Addendum_DB->patient_id   = $encounter->patient_id;
				$this->Encounter_Addendum_DB->user_id      = $this->current_user->id;
				$this->Encounter_Addendum_DB->create_at    = date('Y-m-d H:i:s');
				$this->Encounter_Addendum_DB->notes        = $notes;
				$this->Encounter_Addendum_DB->save();
			}
			
			//Encounter
			$this->Encounter_DB->checked_out_id = 0;
			$this->Encounter_DB->save( $ID );
			
			$this->template->json([
				'status' => 1,
				'message' => "Cita programada pendiente de salida",
				'pending' => $this->Menu_DB->get_pending_results(),
				'redirect' => '',
			], 'JSON_PRESERVE_ZERO_FRACTION');
		}
	}

	
}