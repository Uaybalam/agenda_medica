<?php
/**
 * @route:encounter/referrals
 */
class Encounter_Referrals_Controller extends APP_User_Controller{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Referrals_Model' => 'Encounter_Referrals_DB',
			'Patient_Model'=> 'Patient_DB'
		]);

		$this->Encounter_DB->set_user( $this->current_user );
	}

	/**
	 * @route:{get}__avoid__
	 */
	function index()
	{

		$availableStatus = $this->Encounter_Referrals_DB->getStatus();
		unset($availableStatus[0]);
		
		$this->template
			->modal('encounter/referrals/modal.detail',['title' => 'Detalle de derivación'])
			->modal('referr/modal.create',['title' => 'Agregar nueva derivación'])
			->set_title('Referrals')
			->body([
				'ng-app' => 'ng_referrals',
				'ng-controller' => 'ctrl_referrals',
				'ng-init' => 'initialize('.$this->template->json_entities( $availableStatus ).')'
			])
			->js('encounter/referrals-search')
			->render('encounter/referrals/view.search');
			
	}

	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	function search( $maxRecords = 0, $page = 0)
	{
		$dataSerch = $this->Encounter_Referrals_DB->getPagination(
			$maxRecords,
			$page,
			$this->input->get('sort'),
			$this->input->get('filters')
		);

		return $this->template->json( $dataSerch );
	}

	/**
	 * @route:{post}(:num)/pdf
	 */
	function pdf( $encounter_referrals_id )
	{
		$referr     = $this->Encounter_Referrals_DB->get($encounter_referrals_id);
		if(!$referr)
		{
			show_error("Derivación no encontrada");
		}

		$this->load->library('print/PDF_Referrals');
		
		$administration = \libraries\Administration::init();
		
		$status          = $this->input->post('status');
		$statusAvailable = $this->Encounter_Referrals_DB->getStatus();
		unset($statusAvailable[0]);
		if(!$status || !isset($statusAvailable[$status]) )
		{
			show_error("Estatus no definido ". $status );
		}

		$this->Encounter_Referrals_DB->setData(Array(
			'print_icd_code' => trim($this->input->post('icdCode')),
			'print_extra_diagnosis' => trim($this->input->post('extraDiagnosis')),
			'print_services_requested' => trim($this->input->post('servicesRequested')),
			'print_date' => date('Y-m-d H:i:s'),
			'print_user' => $this->current_user->nick_name,
			'status' => $status
		));

		$this->Encounter_Referrals_DB->save($encounter_referrals_id);

		$data = Array(
			'provider' => Array(
				'name'      => $administration->getValue('billing_provider_name'),
				'signature' => $administration->getValue('billing_provider_signature'),
				'office_contact' => $administration->getValue('billing_office_contact','Daisy'),
				'npi'       => $administration->getValue('phys_npi'),
				'address'   => $administration->getValue('billing_facility_streetAddr'),
				'phone'     => $administration->getValue('billing_facility_telephone'),
				'fax'     => $administration->getValue('billing_provider_fax'),
			),
			'referr' => $referr,
			'patient' => $this->Patient_DB->get($referr->patient_id),
			'encounter' =>$this->Encounter_DB->get($referr->encounter_id),
			'post' => Array(
				'icdCode' => $this->input->post('icdCode'),
				'referrDiagnosis' => $this->input->post('referrDiagnosis'),
				'servicesRequested' => $this->input->post('servicesRequested'),
				'extraDiagnosis' => $this->input->post('extraDiagnosis'),
			)
		);

		$this->pdf_referrals->body($data);
		$this->pdf_referrals->output();

	}

	/**
	 * @route:{post}(:num)/update
	 */
	function update( $encounter_referrals_id )
	{
		$response = ['status' => 0];
		
		$referr = $this->Encounter_Referrals_DB->get( $encounter_referrals_id );
		
		if (!$referr  || $referr->status==0) 
		{
			show_error(403,"Derivación no encontrada");
		}
		
		$this->form_validation
			->set_rules('status','Estatus','required|trim|in_list[1,2,3,4,5,6]')
			->set_rules('acuity','Gravedad','trim|max_length[120]')
			->set_rules('diagnosis','Diagnostico','trim|max_length[120]')
			->set_rules('date_ipa_sent','Fecha de IPA enviada','trim|max_length[75]')
			->set_rules('date_ipa_recived','Fecha de IPA recivida','trim|max_length[75]')
			->set_rules('date_requested','Fecha de solicitud','trim|max_length[75]')
			->set_rules('date_patient_notify','Fecha de notificación de la cita','trim|max_length[75]')
			->set_rules('date_specialist_appt','Fecha de especialista', 'trim|max_length[75]')
			->set_rules('date_consultation_report','Fecha del Informe de Consulta','trim|max_length[75]')
			->set_rules('comments','Comentatios','trim|max_length[5000]')
			->set_rules('comments_completed','Comentarios','trim|max_length[5000]')
			->set_rules('requested_provider','Solicitud de proveedor','trim|max_length[120]')
			->set_rules('insurance','Seguro','trim|max_length[120]')
		;
		//
		$this->form_validation
			->set_rules('refer_date','Fecha de derivación','trim|required|xss_clean|exist_date')
			->set_rules('speciality','Especialidad','trim|required|xss_clean|max_length[120]')
			->set_rules('service','Servicio','trim|required|xss_clean|max_length[250]')
			->set_rules('reason','Razón','trim|required|xss_clean')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{
			//
			$this->Encounter_Referrals_DB->speciality = $this->input->post('speciality');
			$this->Encounter_Referrals_DB->service    = $this->input->post('service');
			$this->Encounter_Referrals_DB->reason     = $this->input->post('reason');
			$this->Encounter_Referrals_DB->refer_date = $this->input->post('refer_date');
			$this->Encounter_Referrals_DB->insurance  = $this->input->post('insurance');
			
			$this->Encounter_Referrals_DB->status                   = $this->input->post('status');
			$this->Encounter_Referrals_DB->acuity                   = $this->input->post('acuity');
			$this->Encounter_Referrals_DB->diagnosis                = $this->input->post('diagnosis');
			$this->Encounter_Referrals_DB->date_ipa_sent            = $this->input->post('date_ipa_sent');
			$this->Encounter_Referrals_DB->date_ipa_recived         = $this->input->post('date_ipa_recived');
			$this->Encounter_Referrals_DB->date_requested           = $this->input->post('date_requested');
			$this->Encounter_Referrals_DB->requested_provider       = $this->input->post('requested_provider');
			$this->Encounter_Referrals_DB->date_patient_notify      = $this->input->post('date_patient_notify');
			$this->Encounter_Referrals_DB->date_follow_up_appt      = $this->input->post('date_follow_up_appt');
			$this->Encounter_Referrals_DB->date_specialist_appt     = $this->input->post('date_specialist_appt');
			$this->Encounter_Referrals_DB->date_consultation_report = $this->input->post('date_consultation_report');
			$this->Encounter_Referrals_DB->comments                 = $this->input->post('comments');
			$this->Encounter_Referrals_DB->comments_completed       = $this->input->post('comments_completed');
			
			$this->Encounter_Referrals_DB->save( $encounter_referrals_id );
			
			$response = [
				'status' => 1,
				'message' => 'Derivación fue actualizada',
				'referral' => $this->Encounter_Referrals_DB->get(  $encounter_referrals_id )
			];
		}
		
		$this->template->json( $response , '');
	}

	/**
	 * @route:{post}(:num)/create
	 */
	function create( $patient_id )
	{
		if(!$patient_id)
		{
			return $this->template->json(['message' => 'El campo paciente es requerido']);
		}

		$this->load->model([
			'Patient_Model' => 'Patient_DB'
		]);

		$patient = $this->Patient_DB->get($patient_id);
		if(!$patient)
		{
			return $this->template->json(['messsage' => 'Paciente no encontrado']);
		}

		$this->form_validation
			->set_rules('refer_date','Fecha de derivación','trim|required|xss_clean|exist_date')
			->set_rules('speciality','Especialidad','trim|required|xss_clean|max_length[120]')
			->set_rules('service','Servicio','trim|required|xss_clean|max_length[250]')
			->set_rules('reason','Razón','trim|required|xss_clean')
			->set_rules('status','Estatus','required|trim|in_list[1,2,3,4,5,6]')
			->set_rules('acuity','Gravedad','trim|max_length[120]')
			->set_rules('diagnosis','Diagnostico','trim|max_length[120]')
			->set_rules('date_ipa_sent','Fecha IPA enviado','trim|max_length[75]')
			->set_rules('date_ipa_recived','Fecha IPA recivido','trim|max_length[75]')
			->set_rules('date_requested','Fecha de solicitud','trim|max_length[75]')
			->set_rules('date_patient_notify','Fecha de notificación de la cita','trim|max_length[75]')
			->set_rules('date_specialist_appt','Fecha de especialista', 'trim|max_length[75]')
			->set_rules('date_consultation_report','Fecha del Informe de Consulta','trim|max_length[75]')
			->set_rules('comments','Comentarios','trim|max_length[5000]')
			->set_rules('comments_completed','Comentarios','trim|max_length[5000]')
			->set_rules('requested_provider','Solicitud del proveedor','trim|max_length[120]')
			->set_rules('insurance','Seguro','trim|max_length[120]')
		;

		if($this->form_validation->run() === FALSE )
		{
			return $this->template->json(['message' => $this->form_validation->error_string() ]); 
		}
		else
		{
			$this->Custom_Setting_DB->insertIfNew($this->input->post('speciality'), 'setting_referral_specialty' );
			$this->Custom_Setting_DB->insertIfNew($this->input->post('service'), 'setting_referral_service' );
			
			$this->Encounter_Referrals_DB->patient_id               = $patient_id;
			$this->Encounter_Referrals_DB->insurance                = $this->input->post('insurance');
			$this->Encounter_Referrals_DB->speciality               = $this->input->post('speciality');
			$this->Encounter_Referrals_DB->service                  = $this->input->post('service');
			$this->Encounter_Referrals_DB->reason                   = $this->input->post('reason');
			$this->Encounter_Referrals_DB->acuity                   = $this->input->post('acuity');
			$this->Encounter_Referrals_DB->status                   = $this->input->post('status');
			$this->Encounter_Referrals_DB->acuity                   = $this->input->post('acuity');
			$this->Encounter_Referrals_DB->diagnosis                = $this->input->post('diagnosis');
			$this->Encounter_Referrals_DB->date_ipa_sent            = $this->input->post('date_ipa_sent');
			$this->Encounter_Referrals_DB->date_ipa_recived         = $this->input->post('date_ipa_recived');
			$this->Encounter_Referrals_DB->date_requested           = $this->input->post('date_requested');
			$this->Encounter_Referrals_DB->requested_provider       = $this->input->post('requested_provider');
			$this->Encounter_Referrals_DB->date_patient_notify      = $this->input->post('date_patient_notify');
			$this->Encounter_Referrals_DB->date_follow_up_appt      = $this->input->post('date_follow_up_appt');
			$this->Encounter_Referrals_DB->date_specialist_appt     = $this->input->post('date_specialist_appt');
			$this->Encounter_Referrals_DB->date_consultation_report = $this->input->post('date_consultation_report');
			$this->Encounter_Referrals_DB->comments                 = $this->input->post('comments');
			$this->Encounter_Referrals_DB->comments_completed       = $this->input->post('comments_completed');

			//IF EXIST IS AN EXTERNAL REFER
			$this->Encounter_Referrals_DB->refer_date 				= $this->input->post('refer_date');
			$this->Encounter_Referrals_DB->created_at 				= date('Y-m-d H:i:s');
			$this->Encounter_Referrals_DB->user_created_nickname    = $this->current_user->nick_name;
			//##

			$this->Encounter_Referrals_DB->save( $this->input->post('id') );

			return $this->template->json([
				'status' => 1,
				'message' => '',
				'referral' => null 
			]); 
		}
	}

	/**
	 * @route:saveFromRequest/(:num)
	 */
	function saveFromRequest( $ID )
	{
		$response = ['status' => 0];

		$encounter = $this->Encounter_DB->is_open( $this, $ID, TRUE );

		if( !  $encounter )
		{	
			show_error('Consulta no encontrada', 404);
		}
		
		$this->form_validation
			->set_rules('speciality','Especialidad','trim|required|xss_clean|max_length[120]')
			->set_rules('service','Servicio','trim|required|xss_clean|max_length[250]')
			->set_rules('reason','Razón','trim|required|xss_clean')
		;
		
		if($this->form_validation->run() === false )
		{
			$response['message'] = $this->form_validation->error_string();
		}	
		else
		{	
			$this->Custom_Setting_DB->insertIfNew($this->input->post('speciality'), 'setting_referral_specialty' );
			$this->Custom_Setting_DB->insertIfNew($this->input->post('service'), 'setting_referral_service' );
			
			if( (int)$this->input->post('id') > 0 )
			{		
				
				$this->Encounter_Referrals_DB->speciality = $this->input->post('speciality');
				$this->Encounter_Referrals_DB->service    = $this->input->post('service');
				$this->Encounter_Referrals_DB->reason     = $this->input->post('reason');
				$this->Encounter_Referrals_DB->acuity     = $this->input->post('acuity');
				
				$this->Encounter_Referrals_DB->save( $this->input->post('id') );

				$response = [
					'status' => 1,
					'message' => 'Derivación fue actualizada',
					'referral' => $this->Encounter_Referrals_DB->get(  $this->input->post('id') )
				];
				
				$this->Encounter_DB->set_activity($encounter->id, 'encounter_referrals_edit');
			}		
			else
			{	
				$this->Encounter_Referrals_DB->created_at            = date('Y-m-d H:i:s');
				$this->Encounter_Referrals_DB->user_created_nickname = $this->current_user->nick_name;

				$this->Encounter_Referrals_DB->encounter_id          = $encounter->id;
				$this->Encounter_Referrals_DB->patient_id            = $encounter->patient_id;
				$this->Encounter_Referrals_DB->speciality            = $this->input->post('speciality');
				$this->Encounter_Referrals_DB->service               = $this->input->post('service');
				$this->Encounter_Referrals_DB->reason                = $this->input->post('reason');
				$this->Encounter_Referrals_DB->acuity                = $this->input->post('acuity');
				$this->Encounter_Referrals_DB->refer_date            = date( 'm/d/Y' );
				$this->Encounter_Referrals_DB->status 				 = 1;
				
				$referrals_id = $this->Encounter_Referrals_DB->save();
				
				$response = [
					'status' => 1,
					'message' => 'Derivación fue agregada',
					'referral' => $this->Encounter_Referrals_DB->get( $referrals_id )
				];

				$this->Encounter_DB->set_activity($encounter->id, 'encounter_referrals_add');
			}
		} 

		$this->template->json( $response );
	}

	/**
	 * @route:save/(:num)
	 */
	function save( $ID )
	{
		$response = ['status' => 0];

		$encounter = $this->Encounter_DB->is_open( $this, $ID, TRUE );

		if( !  $encounter )
		{	
			show_error('Consulta no encontrada', 404);
		}
		
		$this->form_validation
			->set_rules('speciality','Especialidad','trim|required|xss_clean|max_length[120]')
			->set_rules('service','Servicio','trim|required|xss_clean|max_length[250]')
			->set_rules('reason','Razón','trim|required|xss_clean')
		;
		
		if($this->form_validation->run() === false )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmada';
		}	
		else
		{	
			$this->Custom_Setting_DB->insertIfNew($this->input->post('speciality'), 'setting_referral_specialty' );
			$this->Custom_Setting_DB->insertIfNew($this->input->post('service'), 'setting_referral_service' );
			
			if( (int)$this->input->post('id') > 0 )
			{		
				
				$this->Encounter_Referrals_DB->speciality = $this->input->post('speciality');
				$this->Encounter_Referrals_DB->service    = $this->input->post('service');
				$this->Encounter_Referrals_DB->reason     = $this->input->post('reason');
				$this->Encounter_Referrals_DB->acuity     = $this->input->post('acuity');
				
				$this->Encounter_Referrals_DB->save( $this->input->post('id') );

				$response = [
					'status' => 1,
					'message' => 'Derivación fue actualizada',
					'referral' => $this->Encounter_Referrals_DB->get(  $this->input->post('id') )
				];
				
				$this->Encounter_DB->set_activity($encounter->id, 'encounter_referrals_edit');
			}		
			else
			{	
				
				$this->Encounter_Referrals_DB->encounter_id  = $encounter->id;
				$this->Encounter_Referrals_DB->patient_id    = $encounter->patient_id;	
				$this->Encounter_Referrals_DB->speciality    = $this->input->post('speciality');
				$this->Encounter_Referrals_DB->service       = $this->input->post('service');
				$this->Encounter_Referrals_DB->reason        = $this->input->post('reason');
				$this->Encounter_Referrals_DB->acuity        = $this->input->post('acuity');
				$this->Encounter_Referrals_DB->refer_date 	 = date( 'm/d/Y',strtotime($encounter->create_at));

				$referrals_id = $this->Encounter_Referrals_DB->save();
				
				$response = [
					'status' => 1,
					'message' => 'Derivación creada',
					'referral' => $this->Encounter_Referrals_DB->get( $referrals_id )
				];

				$this->Encounter_DB->set_activity($encounter->id, 'encounter_referrals_add');
			}
		}
 
		$this->template->json( $response );
	}

	/**
	 * @route:delete/(:num)
	 */
	function delete( $ID )
	{
		$response['status'] = 0;
		
		if( !($referral = $this->Encounter_Referrals_DB->get($ID)) )
		{
			show_error('Derivación no encontrada', 404);
		}

		$encounter = $this->Encounter_DB->get( $referral->encounter_id );
		if(!$encounter || $encounter->status != 1 )
		{		
			show_error('Consulta no encontrada', 404);
		}
		
		
		$this->Encounter_Referrals_DB->delete( $ID );
		$response = [
			'status' => 1, 
			'message' => 'Derivación Eliminada'
		];
		
		$this->Encounter_DB->set_activity($referral->encounter_id, 'encounter_referrals_remove');

		$this->template->json( $response );
	}

	/**
	 * @route:delete/(:num)/external
	 */
	function deleteExternal( $encounter_referrals_id )
	{

		if( !($referral = $this->Encounter_Referrals_DB->get($encounter_referrals_id)) )
		{
			show_error('Derivación no encontrada', 404);
		}

		if(!$referral->user_created_nickname)
		{
			return $this->template->json( ['message' => 'Derivación invalida']);
		}
		
		$this->Encounter_Referrals_DB->delete( $encounter_referrals_id );
		
		return $this->template->json( [
			'status' => 1,
			'message' => 'Derivación Eliminada'
		]);
	}
}