<?php
/**
 * @route:encounter
 */
class Encounter_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Patient_Model' => 'Patient_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Encounter_Medication_Model' => 'Encounter_Medication_DB',
			'Encounter_Referrals_Model' => 'Encounter_Referrals_DB',
			'Encounter_Physicalexam_Model' => 'Encounter_Physicalexam_DB',
			'Encounter_Results_Model' => 'Encounter_Results_DB',
			'Encounter_Addendum_Model' => 'Encounter_Addendum_DB',
			'Appointment_Model' => 'Appointment_DB',
			'Encounter_Child_Model' => 'Encounter_Child_DB'
		]);
		
		$this->Encounter_DB->set_user( $this->current_user );
	}

	/**
	 * @route:pdf/(:num)/(encounter)
	 * @route:pdf/(:num)/(patient)
	 */
	function pdf( $ID , $type = 'encounter' )
	{
		$this->load->library('print/PDF_Encounter');
		
		if( $type ==='encounter' )
		{
			$encounter                        = $this->Encounter_DB->get_info( $ID );
			if( !$encounter || $encounter->status == 1 )
			{
				show_error('Consulta no encontrado o pendiente de firma' , 404 );
			}

			$encounter->physical_examinations = $this->Encounter_Physicalexam_DB->getResultsBy(['encounter_id' => $ID]);
			$encounter->diagnostics           = $this->Encounter_Diagnosis_DB->getResultsBy(['encounter_id' => $ID ]);
			$encounter->medications 		  = $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $ID ]);
			$encounter->results 		  	  = $this->Encounter_Results_DB->getResultsBy(['encounter_id' => $ID ]);
			$encounter->referrals 		  	  = $this->Encounter_Referrals_DB->getResultsBy(['encounter_id' => $ID ]);
			
			$encounter->addendums 			  = $this->Encounter_Addendum_DB->get_data( $ID );
			$encounter->patient 			  = $this->Patient_DB->get_info( $encounter->patient_id );
			$encounter->encounter_child 	  = $this->Encounter_Child_DB->get_data( $ID );
			

			$this->pdf_encounter->body( $encounter  );
		}
		else if( $type === 'patient' )
		{	
			$from = ($this->input->get('from')!='') ? 
				date('Ymd', strtotime(clear_var( $this->input->get('from'))) ) : '';
			$to = ($this->input->get('to')!='') ?
				date('Ymd', strtotime(clear_var( $this->input->get('to'))) ) : '';
			
			$encounters = $this->Encounter_DB->get_info_by_patient( $ID ,$from, $to );
			

			foreach ($encounters as $encounter) {
				$encounter->physical_examinations = $this->Encounter_Physicalexam_DB->getResultsBy(['encounter_id' => $encounter->id ]);
				$encounter->diagnostics           = $this->Encounter_Diagnosis_DB->getResultsBy(['encounter_id' => $encounter->id  ]);
				$encounter->medications 		  = $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $encounter->id  ]);
				$encounter->results 		  	  = $this->Encounter_Results_DB->getResultsBy(['encounter_id' => $encounter->id  ]);
				$encounter->referrals 		  	  = $this->Encounter_Referrals_DB->getResultsBy(['encounter_id' => $encounter->id  ]);
				$encounter->addendums 			  = $this->Encounter_Addendum_DB->get_data( $encounter->id );
				$encounter->patient 			  = $this->Patient_DB->get_info( $ID );
				$encounter->encounter_child 	  = $this->Encounter_Child_DB->get_data( $encounter->id  );
				
				$this->pdf_encounter->body( $encounter );
			}

			if( count($encounters) === 0 )
			{
				show_error('Consulta requiere firma' , 404 );
			}
		}

		$this->pdf_encounter->output();
	}

	/**
	 * @route:pdf/(:num)/prescription
	 */
	function prescription( $ID )
	{
		$this->load->library('print/PDF_Prescription');
		
		$encounter = $this->Encounter_DB->get_info( $ID );

		if( !$encounter || $encounter->status == 1 )
		{
			show_error('Consulta no encontrado o pendiente de firma' , 404 );
		} 
		
		$encounter->medications 		  = $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $ID ]); 
		$encounter->patient 			  = $this->Patient_DB->get_info( $encounter->patient_id ); 
		
		$date_of_birth = explode("/",$encounter->patient->date_of_birth);

		$encounter->patient->date_of_birth = date("d/m/Y",strtotime($date_of_birth[2]."-".$date_of_birth[0]."-".$date_of_birth[1]));

		$this->pdf_prescription->body($encounter);
		$this->pdf_prescription->output();
	}

	/**
	 * @route:{post}createBill/(:num)
	 */
	function createBill( $ID )
	{
		$this->validate_access(['manager','medic','nurse','billing']);
		
		$encounter = $this->Encounter_DB->is_open( $this, $ID );

		$response = ['status' => 0];

		$this->form_validation
			->set_rules('insurance_plan','Nombre de plan','trim|xss_clean')
			->set_rules('insurance_id','Numero de seguro','trim|xss_clean')
			->set_rules('pin','Pin de usuario','required|trim|pin_verify')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else if($encounter->status!= 2 || $encounter->has_insurance!=0)
		{
			$response['message'] = 'Encuentro sin estado enviado o sin seguro';
		}
		else
		{	
			///save data encounter
			
			$this->Encounter_DB->setData(Array(
				'has_insurance' => 1,
				'insurance_title' => $this->input->post('insurance_plan'),
				'insurance_number' => $this->input->post('insurance_id'),
			));

			$this->Encounter_DB->save($ID);
			
			$this->Encounter_DB->set_activity($ID, 'encounter_create_billing');
			
			$encounter->has_insurance    = 1;
			$encounter->insurance_title  = $this->input->post('insurance_plan');
			$encounter->insurance_number = $this->input->post('insurance_id');

			//create billing
			$this->load->model(['Billing_Model' => 'Billing_DB']);
			$this->Billing_DB->create_default( $encounter );

			$response = Array(
				'status' => 1,
				'encounter' => $encounter,
				'message' => 'Facturación creada',
			);
		}

		$this->template->json( $response );
	}

	
	/**
	 * @route:sign/(:num)
	 */
	function sign( $ID )
	{

		$this->validate_access(['manager','medic','nurse']);

		$userDigital = $this->User_DB->getDigitalSignature($this->current_user->id);

		$response = ['status' => 0];

		$encounter = $this->Encounter_DB->is_open( $this, $ID );

		$this->form_validation
			->set_rules('pin','Pin de usuario','required|trim|pin_verify')
			->set_rules('next_appointment','Sigueinte cita','trim|xss_clean')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}else if( (int)$encounter->status == 2  )
		{		
			$response['message'] = 'Consulta estatus firmado';
		}
		else if( !$userDigital )
		{
			$response['message'] = "El usuario no ha capturado una firma digital. - ".$userDigital;
		}
		else
		{
			$patient = $this->Patient_DB->get( $encounter->patient_id );
			
			//Active encounter signed
			$this->Encounter_DB->status           = 2;
			$this->Encounter_DB->signed_at        = date('Y-m-d H:i:s');
			$this->Encounter_DB->user_id          = $this->current_user->id;
			$this->Encounter_DB->user_signature   = $userDigital;
			$this->Encounter_DB->next_appointment = $this->input->post('next_appointment');
			$this->Encounter_DB->save( $ID );
			
			//Refresh data
			$encounter = $this->Encounter_DB->get($ID);
			
			//Active Referrals
			$this->Encounter_Referrals_DB->status = 1;
			$this->Encounter_Referrals_DB->update( ['encounter_id' => $ID ] );
			//Active results
			$this->Encounter_Results_DB->status = 1;
			$this->Encounter_Results_DB->update( ['encounter_id' => $ID ] );
			//Active Medications
			$this->Encounter_Medication_DB->status = 1;
			$this->Encounter_Medication_DB->update(['encounter_id' => $ID ] );
			
			$this->load->model(['Encounter_Invoice_Model' => 'Encounter_Invoice_DB']);
			
			$this->Encounter_Invoice_DB->createDefault( $encounter, $patient );
			
			if( (int)$encounter->has_insurance === 0 )
			{
				//Create encounter invoice
				$this->Encounter_DB->setData([
					'insurance_title' => 'CASH'
				]);
				$this->Encounter_DB->save( $ID );
			}
			else
			{	
				//create billing
				$this->load->model(['Billing_Model' => 'Billing_DB']);
				$this->Billing_DB->create_default( $encounter );
				
			}
			
			//Have appointment
			if( $encounter->appointment_id  )
			{	
				$this->Appointment_DB->status      = 6;
				$this->Appointment_DB->time_signed = date('h:i A');
				$this->Appointment_DB->save( $encounter->appointment_id );

				$this->add_appt_event($encounter->appointment_id,'encounter_signed');
			}
			
			$response = [
				'status' => 1,
				'message' => '',
				'redirect' => site_url('/encounter/detail/' . $ID )
			];

			$this->Encounter_DB->set_activity($ID, 'encounter_sign');
		}	

		$this->template->json( $response );
	}
	
	/**
	 * @route:refresh/(:num)
	 */
	function refresh( $ID )
	{
		$this->validate_access(['manager','medic','nurse','reception']);
		
		$encounter = $this->Encounter_DB->is_open( $this, $ID  );
		
		$this->template->json([
			'encounter' => $encounter
		]);
	}

	/**
	 * @route:{get}detail/(:num)/(nurse)
	 */
	function detail_nurse( $ID )
	{
		$this->validate_access(['manager','nurse','reception']);
		
		$encounter = $this->Encounter_DB->is_open( $this, $ID );
		
		if($encounter->status == 1 )
		{	
			$this->template
				->modal('encounter/detail/modal.vitals',['title' => 'Signos vitales'] )
				->modal('encounter/detail/modal.results',['title' => 'Solicitudes'])
				->modal('encounter/detail/modal.referrals',['title' => 'Derivaciones'] )
				->modal('encounter/detail/modal.childphysical',['title' => 'Examen físico pediátrico', 'size' => 'modal-xl' ])
				->modal('encounter/detail/modal.education',['title' => 'Educación del paciente'])
				->modal('encounter/detail/modal.sign',['title' => 'Firmar Consulta', 'size' => 'modal-md'])
				->js('encounter/detail-sign')
				->js('encounter/detail-vitals')
				->js('encounter/detail-results')
				->js('encounter/detail-referrals')
				->js('encounter/detail-childphysical')
				->js('encounter/detail-education')
				->js('Chart.min','/assets/vendor/node_modules/chart.js/dist/')
			;
			
		}
		
		$this->load->model(['Patient_History_Model' => 'Patient_History_DB' ]);
		$patient_healthHistory = $this->Patient_History_DB->get_active_diseases( $encounter->patient_id );
		$patient_activeHX = $this->db->where( [
			'patient_id' => $encounter->patient_id 
		])->get('patient_history_active')->row();
		
		$this->template
			->set_title('Encounter detail ' . $ID )
			->modal('encounter/detail/modal.patient.healthhistory',[
				'title' => 'Historial de salud del paciente'], [
				'data' => $patient_healthHistory 
			])
			->modal('encounter/detail/modal.patient.activehx',[
				'title' => 'Antecedentes médicos activos del paciente'],[
				'data' => $patient_activeHX
			])
			->body([
				'ng-app' => 'ng_encounter_detail',
				'ng-controller' => 'ctrl_encounter_detail',
				'id' => 'id_encounter_detail',
				'ng-init' => 'initialize('.$ID.')'
			])
			->js('encounter/detail')
			->render('encounter/view.panel.detail.nurse');
	}
	
	/**
	 * @route:detail/(:num)
	 */
	function detail( $ID )
	{
		$access = $this->validate_access(['billing','manager','medic'],'/encounter/detail/' . $ID .'/nurse' );
		
		$encounter = $this->Encounter_DB->is_open( $this, $ID );
		
		if( (int)$encounter->first_open === 0 )
		{
			//has appointment
			if( $encounter->appointment_id )
			{
				$this->Appointment_DB->time_open = date('h:i A');
				$this->Appointment_DB->save( $encounter->appointment_id );
				$this->add_appt_event( $encounter->appointment_id, 'open_encounter' );
			}
			
			$this->Encounter_DB->first_open = 1;
			$this->Encounter_DB->save( $ID );
		}
		
		if($encounter->status == 2 )
		{	
			$this->template
				->modal('billing/modal.create',['title' => 'Crear facturación'])
				->modal('encounter/detail/modal.addendum', ['title' => 'Adenda'])
				->js('encounter/detail-addendum')
			;
		}	
		else
		{	
			$this->template
				->modal('encounter/detail/modal.vitals',['title' => 'Signos Vitales'] )
				//->modal('encounter/detail/modal.procedure',['title' => 'Procedure'] )
				->modal('encounter/detail/modal.illness',['title' => 'Historial de Enfermedades'] )
				->modal('encounter/detail/modal.diagnosis',['title' => 'Diagnóstico'] )
				->modal('encounter/detail/modal.medication',['title' => 'Medicación'] )
				->modal('encounter/detail/modal.referrals',['title' => 'Dereviaciones'] )
				->modal('encounter/detail/modal.physicalexam',['title' => 'Examen Físico'])
				->modal('encounter/detail/modal.results',['title' => 'Solicitudes'])
				->modal('encounter/detail/modal.childphysical',['title' => 'Examen Físico Pediátrico', 'size' => 'modal-xl' ])
				->modal('encounter/detail/modal.sign',['title' => 'Firmar consulta', 'size' => 'modal-md'])
				->modal('encounter/detail/modal.education',['title' => 'Educación del Paciente'])
				
				
				->js('encounter/detail-vitals')
				->js('encounter/detail-illness')
				//->js('encounter/detail-procedure')
				->js('encounter/detail-diagnosis')
				->js('encounter/detail-medication')
				->js('encounter/detail-referrals')
				->js('encounter/detail-physicalexam')
				->js('encounter/detail-results')
				->js('encounter/detail-childphysical')
				->js('encounter/detail-sign')	
				->js('encounter/detail-education')
			;
		}	

		$this->load->model(['Patient_History_Model' => 'Patient_History_DB' ]);
		$patient_healthHistory = $this->Patient_History_DB->get_active_diseases( $encounter->patient_id );
		$patient_activeHX = $this->db->where( [
			'patient_id' => $encounter->patient_id 
		])->get('patient_history_active')->row();
		
		$this->template
			->set_title('Encounter detail ' . $ID )
			->body([
				'ng-app' => 'ng_encounter_detail',
				'ng-controller' => 'ctrl_encounter_detail',
				'id' => 'id_encounter_detail',
				'ng-init' => 'initialize('.$ID.')',
			])
			->modal('encounter/detail/modal.patient.healthhistory',[
				'title' => 'Historial de Salud del Paciente'], [
				'data' => $patient_healthHistory 
			])
			->modal('encounter/detail/modal.patient.activehx',[
				'title' => 'Patient Active HX'],[
				'data' => $patient_activeHX
			])
			->modal('encounter/detail/modal.activity',[
					'title' => 'Historial de consulta',
					'size' => 'modal-xl'])
			->modal('encounter/compare/modal.compare',['title' => 'Comparar información de otras consultas', 'size' => 'modal-xl'] )
			->js('Chart.min','/assets/vendor/node_modules/chart.js/dist/')
			->js('encounter/detail')
			->render('encounter/view.panel.detail');
	}
	
	/**
	 * @route:{post}create/(:num)
	 */
	function create( $ID )
	{
		
		$this->validate_access(['manager','medic','nurse','reception']);
		
		$response = ['status' => 0];
		
		$appointment = $this->Appointment_DB->get($this->input->post('appointment_id') );

		if( $error_string = $this->Encounter_DB->validate_vitals( $this->form_validation )  )
		{	
			$response['message'] = $error_string;
		}
		else if( !$appointment || (int)$appointment->status !==3 )
		{
			$response['message'] =  'La cita no tiene estado pendiente ni de llegada.';		
		}
		else
		{	
			$patient = $this->db->select('date_of_birth')
				->where('id', $ID )
				->get('patient')->row();
			
			//
			$this->Encounter_DB->patient_id 	= $ID;
			$this->Encounter_DB->appointment_id = $appointment->id;
			$encounter_id = $this->Encounter_DB->save_data( $this->input );
			$this->Encounter_DB->set_activity($encounter_id,'encounter_create');
			//
			$current_date  = new DateTime();
			$date_of_birth = new DateTime( $patient->date_of_birth );
			$interval      = $date_of_birth->diff( $current_date );
			$months        = ( $interval->y * 12 ) + $interval->m;
			
			$availableChildPhisycal = (int)\libraries\Administration::getValue('available_months_child');

			if( (int)$months <= $availableChildPhisycal )
			{
				$this->Encounter_Child_DB->encounter_id   = $encounter_id;
				$this->Encounter_Child_DB->patient_months = (int)$months;
				$this->Encounter_Child_DB->save();
			}
			
			//SET encounter_id and status 4
			$this->Appointment_DB->status       = 4;
			$this->Appointment_DB->encounter_id = $encounter_id;
			$this->Appointment_DB->save( $appointment->id );

			$this->add_appt_event( $appointment->id, 'vitals_created' );

			$response = [
				'status' => 1,
				'message' => 'Signos Vitales guardados',
				'encounter' => $this->Encounter_DB->get_data_chart( $encounter_id ),
				'last_appointment' => $this->Appointment_DB->get_last_appointment( $ID ),
				'redirect' => ''
			];
		}

		$this->template->json( $response );
	}

	/**
	 * 
	 * @route:{post}update/child/(:num)
	 *
	 */
	function update_child( $ID )
	{

		$this->validate_access(['manager','medic','nurse','reception']);

		$encounter = $this->Encounter_DB->is_open( $this, $ID );
		
		$response = ['status' => 0];
		
		if( (int)$encounter->status != 1 )
		{	
			$this->template->json([
				'message' => 'Estatus de consulta firmado'
			]);
		} 
		else if( $errorValidate = $this->Encounter_Child_DB->validate_data($this->form_validation) )
		{
			$this->template->json([
				'message' => $errorValidate 
			]);
		}
		else
		{	
			$this->Encounter_Child_DB->ethnic_code     = $this->input->post('ethnic_code');
			$this->Encounter_Child_DB->type_of_screen  = $this->input->post('type_of_screen');
			$this->Encounter_Child_DB->referred_to_wic = $this->input->post('referred_to_wic');
			$this->Encounter_Child_DB->enrolled_in_wic = $this->input->post('enrolled_in_wic');
			$this->Encounter_Child_DB->treatment       = $this->input->post('treatment');
			$this->Encounter_Child_DB->assessment      = $this->input->post('assessment');
			$this->Encounter_Child_DB->tb_risk         = $this->input->post('tb_risk');
			$this->Encounter_Child_DB->lead_risk       = $this->input->post('lead_risk');

			$this->Encounter_Child_DB->interval_history_diet              = $this->input->post('interval_history_diet');
			$this->Encounter_Child_DB->interval_history_illness           = $this->input->post('interval_history_illness');
			$this->Encounter_Child_DB->interval_history_problems          = $this->input->post('interval_history_problems');
			$this->Encounter_Child_DB->interval_history_immunization      = $this->input->post('interval_history_immunization');
			$this->Encounter_Child_DB->interval_history_parental_concerns = $this->input->post('interval_history_parental_concerns');
			
			$this->Encounter_Child_DB->development_result  = $this->input->post('development_result');
			$this->Encounter_Child_DB->development_options = (is_array($this->input->post('development_options'))) ? implode(',', $this->input->post('development_options')) : $this->input->post('development_options');
			$this->Encounter_Child_DB->development_plan    = (is_array($this->input->post('development_plan'))) ? implode(',', $this->input->post('development_plan')) : $this->input->post('development_plan');

			
			$this->Encounter_Child_DB->physical_comments_general_appearance = $this->input->post('physical_comments_general_appearance');
			$this->Encounter_Child_DB->physical_comments_nutrition          = $this->input->post('physical_comments_nutrition');
			$this->Encounter_Child_DB->physical_comments_skin               = $this->input->post('physical_comments_skin');
			$this->Encounter_Child_DB->physical_comments_head_neck_nodes    = $this->input->post('physical_comments_head_neck_nodes');
			$this->Encounter_Child_DB->physical_comments_eyes_eq_reflex     = $this->input->post('physical_comments_eyes_eq_reflex');
			$this->Encounter_Child_DB->physical_comments_ent_hearing        = $this->input->post('physical_comments_ent_hearing');
			$this->Encounter_Child_DB->physical_comments_mouth_dental       = $this->input->post('physical_comments_mouth_dental');
			$this->Encounter_Child_DB->physical_comments_chest_lungs        = $this->input->post('physical_comments_chest_lungs');
			$this->Encounter_Child_DB->physical_comments_heart              = $this->input->post('physical_comments_heart');
			$this->Encounter_Child_DB->physical_comments_abdomen            = $this->input->post('physical_comments_abdomen');
			$this->Encounter_Child_DB->physical_comments_external_genitalia = $this->input->post('physical_comments_external_genitalia');
			$this->Encounter_Child_DB->physical_comments_back               = $this->input->post('physical_comments_back');
			$this->Encounter_Child_DB->physical_comments_extremities_hips   = $this->input->post('physical_comments_extremities_hips');
			$this->Encounter_Child_DB->physical_comments_neurological       = $this->input->post('physical_comments_neurological');
			$this->Encounter_Child_DB->physical_comments_fem_pulses         = $this->input->post('physical_comments_fem_pulses');

			$this->Encounter_Child_DB->physical_result_general_appearance = $this->input->post('physical_result_general_appearance');
			$this->Encounter_Child_DB->physical_result_nutrition          = $this->input->post('physical_result_nutrition');
			$this->Encounter_Child_DB->physical_result_skin               = $this->input->post('physical_result_skin');
			$this->Encounter_Child_DB->physical_result_head_neck_nodes    = $this->input->post('physical_result_head_neck_nodes');
			$this->Encounter_Child_DB->physical_result_eyes_eq_reflex     = $this->input->post('physical_result_eyes_eq_reflex');
			$this->Encounter_Child_DB->physical_result_ent_hearing        = $this->input->post('physical_result_ent_hearing');
			$this->Encounter_Child_DB->physical_result_mouth_dental       = $this->input->post('physical_result_mouth_dental');
			$this->Encounter_Child_DB->physical_result_chest_lungs        = $this->input->post('physical_result_chest_lungs');
			$this->Encounter_Child_DB->physical_result_heart              = $this->input->post('physical_result_heart');
			$this->Encounter_Child_DB->physical_result_abdomen            = $this->input->post('physical_result_abdomen');
			$this->Encounter_Child_DB->physical_result_external_genitalia = $this->input->post('physical_result_external_genitalia');
			$this->Encounter_Child_DB->physical_result_back               = $this->input->post('physical_result_back');
			$this->Encounter_Child_DB->physical_result_extremities_hips   = $this->input->post('physical_result_extremities_hips');
			$this->Encounter_Child_DB->physical_result_neurological       = $this->input->post('physical_result_neurological');
			$this->Encounter_Child_DB->physical_result_fem_pulses         = $this->input->post('physical_result_fem_pulses');
			
			$this->Encounter_Child_DB->tobacco_patient_exposed            = $this->input->post('tobacco_patient_exposed');
			$this->Encounter_Child_DB->tobacco_used_by_patient            = $this->input->post('tobacco_used_by_patient');
			$this->Encounter_Child_DB->tobacco_prevention_referred        = $this->input->post('tobacco_prevention_referred');
			
			$this->Encounter_Child_DB->educations = (is_array($this->input->post('educations'))) ? implode(',', $this->input->post('educations')) : $this->input->post('educations');
			
			$this->Encounter_Child_DB->save( $ID );

			$this->template->json([
				'status' => 1,
				'message' => 'La evaluación del niño se ha actualizado',
				'encounter_child' => $this->Encounter_Child_DB->get_data( $ID ),
				'development_options_x' => $this->input->post('development_options')
			]);
		}
	}
	
	/**
	 * @route:{post}update/vitals/(:num)
	 */
	function update_vitals($ID)
	{
		$this->validate_access(['manager','medic','nurse','reception']);

		$encounter = $this->Encounter_DB->is_open( $this, $ID , TRUE );

		$response = ['status' => 0];

		if( $error_string = $this->Encounter_DB->validate_vitals( $this->form_validation )  )
		{	
			$response['message'] = $error_string;
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmado';
		}
		else
		{		
			//
			$this->Encounter_DB->save_data( $this->input , $ID );
			//
			$response = [
				'status' => 1,
				'message' => 'Signos Vitales actualizados',
				'encounter' => $this->Encounter_DB->get_info( $ID )
			];

			$this->Encounter_DB->set_activity($ID, 'encounter_vitals_update');
		}

		$this->template->json( $response , '' );
	}

	/**
	 * @route:{post}update/illness/(:num)
	 */
	function update_illness( $ID )
	{
		$this->validate_access(['manager','medic']);

		if(!( $encounter = $this->Encounter_DB->is_open( $this, $ID , TRUE ) ) )
		{
			show_error('Consulta no encontrada', 404);
		}

		$response = ['status' => 0];
		$this->form_validation
			->set_rules('present_illness_history','Present illness ','trim|xss_clean')
		;

		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmado';
		}
		else
		{
			$this->Encounter_DB->present_illness_history = $this->input->post('present_illness_history');
			$this->Encounter_DB->save( $ID );
			//
			$response = [
				'status' => 1,
				'message' => 'La historia de enfermedades se ha actualizado.',
				'encounter' => $this->Encounter_DB->get_info( $ID )
			];

			$this->Encounter_DB->set_activity($ID, 'encounter_illness_update');
		}
		
		$this->template->json( $response , '' );
	}
	
	/**
	function update_procedure($ID)
	{
		$this->validate_access(['medic']);

		if(!( $encounter = $this->Encounter_DB->is_open( $this, $ID , TRUE )  ) )
		{
			show_error('Consulta no encontrada', 404);
		}
		
		$response = ['status' => 0];
		$this->form_validation
			->set_rules('procedure_text','Procedure text','trim|xss_clean|max_length[250]')
		;
		
		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmado';
		}
		else
		{	
			//
			$this->Encounter_DB->procedure_text         	 = $this->input->post('procedure_text');
			$this->Encounter_DB->save( $encounter->id );
			//
			$response = [
				'status' => 1,
				'message' => 'Procedure updated',
				'encounter' => $this->Encounter_DB->get_info( $ID )
			];

			$this->Encounter_DB->set_activity($ID, 'encounter_procedure_update');
		}	

		$this->template->json( $response , '' );
	}
	*/

	/**
	 * @route:{post}update/education/(:num)
	 */
	function update_education($ID)
	{
		$this->validate_access(['medic','nurse']);

		if(!($encounter = $this->Encounter_DB->is_open( $this, $ID , TRUE ) ) )
		{
			show_error('Consulta no encontrada', 404);
		}
		
		$response = ['status' => 0];
		$this->form_validation
			->set_rules('procedure_patient_education','Procedure text','trim|xss_clean|max_length[2000]')
		;
		
		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmado';
		}
		else
		{	
			//
			$this->Encounter_DB->procedure_patient_education = $this->input->post('procedure_patient_education');
			$this->Encounter_DB->save( $encounter->id );
			//
			$response = [
				'status' => 1,
				'message' => 'Educación del paciente actualizado',
				'encounter' => $this->Encounter_DB->get_info( $ID )
			];

			$this->Encounter_DB->set_activity($ID, 'encounter_procedure_update');
		}	

		$this->template->json( $response , '' );
	}

	/**
	 * @route:activity/(:num)
	 */
	function activity( $ID )
	{
		$this->validate_access(['manager','medic']);
		
		$this->template->json( [
			'catalog_activity' => $this->lang->language,
			'encounter_activity' =>  $this->Encounter_DB->get_activity( $ID )
		]);
	}
	
	/**
	 * @route:init/(:num)
	 */
	function init( $ID )
	{

		$this->validate_access(['billing','manager','medic','nurse','reception']);
		
		if(!( $encounter = $this->Encounter_DB->get_info($ID) ) )
		{
			show_error('Consulta no encontrada', 404);
		}
		
		$my_examinations = $this->db->where([
				'user_id' => $this->current_user->id
			])->get('examinations')->result();

		$encounter_child 		     = $this->Encounter_Child_DB->get_data( $ID );
		
		$development_options_default = $development_plan_default = array();

		if($encounter_child->id > 0 )
		{
			$development_options_default = $this->Encounter_Child_DB->available_development_options( $encounter_child->patient_months );
			$development_plan_default 	  = $this->Encounter_Child_DB->available_development_plans( $encounter_child->patient_months );
			
		}
		
		$settingEducation = $this->Custom_Setting_DB->getElements('setting_education');
		array_unshift($settingEducation, [
			'id'   => "99999",
			'type' => 'setting_education',
			'name' => 'Ninguna'
		]);

		$response = [

			'patient' => $this->Patient_DB->get_info( $encounter->patient_id ),

			'encounter' => $encounter,

			'catalog_educations' => $settingEducation,
			
			'catalog_refer_services' => $this->Custom_Setting_DB->getElements('setting_referral_service', true),

			'catalog_specialities' =>  $this->Custom_Setting_DB->getElements('setting_referral_specialty', true ), 
			
			'catalog_medications' =>  $this->Custom_Setting_DB->getElements('setting_medication', true),
			
			'catalog_results' =>  $this->Custom_Setting_DB->getElements('setting_request', true ),
			
			'encounter_diagnosis' => $this->Encounter_Diagnosis_DB->getResultsBy(['encounter_id' => $encounter->id ]),
			
			'encounter_medications' => $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $ID] ),
			
			'encounter_referrals' => $this->Encounter_Referrals_DB->getResultsBy(['encounter_id' => $ID]),
			
			'encounter_physicalexam' => $this->Encounter_Physicalexam_DB->getResultsBy(['encounter_id' => $ID]),
			
			'catalog_examinations' =>  $my_examinations,
			
			'encounter_results' =>  $this->Encounter_Results_DB->getResultsBy(['encounter_id' => $ID]),
			
			'status_referrals'	=> $this->Encounter_Referrals_DB->getStatus(),

			'status_results' => $this->Encounter_Results_DB->get_status(),
			
			'encounter_results_availible' => $this->Encounter_Results_DB->get_results_availible(),

			'encounter_addendums' => $this->Encounter_Addendum_DB->get_data( $ID ),
			
			'current_diagnostics' => $this->Encounter_Diagnosis_DB->current_diagnostics( $encounter->patient_id ),
			
			'encounter_child' => $encounter_child,

			'development_options_default' => $development_options_default,

			'development_plan_default' => $development_plan_default,

			'options_educations_default' => $this->Encounter_Child_DB->options_educations,
			
			'settings_ethnic_codes' => $this->Encounter_Child_DB->settings_ethnic_codes,

			'questions_ins_inmigration' => \libraries\Administration::getValue('questions_ins_inmigration')
			
		];	
		
		
		$this->template->json(  $response , '');
	}

	function _confirm_user( $str )
	{

		if(!password_verify( $str ,  $this->current_user->password )  ) 
		{
			$this->form_validation->set_message('_confirm_user',   'La contraseña no coincide con el usuario');
            return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

}

