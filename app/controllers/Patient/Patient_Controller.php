<?php
/**
 * @route:patient
 */
class Patient_Controller extends APP_User_Controller{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Warnings_Model' => 'Patient_Warnings_DB'
		]);
	}
	
	/**
	 * @route:printpdf
	 */
	function printpdf()
	{
		$this->load->library('print/PDF_Patient_List');
			
		$result = $this->Patient_DB->getPagination( 0, 0, 
			$this->input->get("sort"), 
			$this->input->get('filters') );
		
		$this->pdf_patient_list->body( $result );

		$this->pdf_patient_list->output();
	}

	/**
	 * @route:pdf/(:num)
	 */
	function pdf( $ID )
	{
		$this->load->library('print/PDF_Patient');
		
		if( !$patient = $this->Patient_DB->get_info( $ID ) )
		{
			show_error(' Paciente no encontrado',404);
		}

		$param = [
			'marital_status' => $this->Patient_DB->getMaritalStatus()
		];
		
		$this->pdf_patient->body( $patient , $param);
		$this->pdf_patient->output();
	}

	/**
	 * @route:importCsv
	 */
	function importCsv( )
	{	
		$data   = $this->Patient_DB->getAll(); 
		$mStatu = [
			0 => 'Sin especificar',
			1 => 'Soltero',
			2 => 'Casado',
			3 => 'Otro'
		];

		$fields = [
			"id",
			"last_name", //Last Name	
			"middle_name", //Middle Name
			"name", //First Name
			"", //Suffix
			"date_of_birth",// Date of Birth
			"phone", //Home Phone
			"phone_alt", //Cell Phone
			"", //Work phone
			"", //Work Phone Extension
			"", // Office Fax
			"email", //EMail
			"insurance_primary_identify", // Social Security #
			"marital_status", //Marital status
			"gender", //gender
			"address", //Address 1
			"", //Address 2
			"address_city", //city
			"address_state", //state
			"address_zipcode", // zip code
			"", //country
			"",// Comments
		]; 

		$f = fopen('php://memory', 'w'); 

		foreach($data as $key => $patiente) 
		{
			$line = [];

			foreach ($fields as $key => $field) 
			{
				if($field == "")
				{
					$line[] = " ";
					continue;
				}

				if($field === "marital_status")
				{
					$line[] = $mStatu[$patiente->$field];
				}
				else
				{
					$line[] = $patiente->$field;
				}
			} 
	
			fputcsv($f, $line,"\t"," ");
		} 

		fseek($f, 0);
	    header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename="Patientes_Exports'.date("Y-m-d").'.csv";');
	    fpassthru($f);
	}
	
	/**
	 * @route:{get}demographics/(:num)
	 */
	function demographics( $ID )
	{
		$patient = $this->Patient_DB->get_info($ID);
		
		$this->template->json([
			'patient' => $patient 
		]);
	}

	/**
	 * @route:{get}(:num)/information
	 */
	function information( $ID )
	{
		
		if( $patient = $this->Patient_DB->get_info($ID) )
		{
			$patient->warnings = $this->Patient_Warnings_DB->getResultsBy(['patient_id' => $ID ]);
		}

		$this->template->json([
			'patient' => $patient
		]);
	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{

		$this->load->model([
			'Appointment_Model' => 'Appointment_DB',
			'Patient_Communication_Model' => 'Communication_DB'
		]);
		
		$typesOfCommunications = $this->Communication_DB->get_available_types();

		$this->template
			->body([
				'ng-app' => 'app_patients_list',
				'ng-controller' => 'ctrl_patients_list',
				'ng-init' => 'typesOfCommunications='.$this->template->json_entities($typesOfCommunications),
				'ng-keydown' => 'keyup($event)'
			])
			->set_title("Pacientes")
			->js('patient/patient.list')
			->modal('patient/communicate/modal.create.communication',['title' => 'Completa notas' ], ['visit_types' => $this->Appointment_DB->get_visit_types()])
			->modal('patient/communicate/modal.history.communication',['title' => 'Detalles de la historia clínica','size' => 'modal-xl' ] )
			->modal('appointment/modal.current.date',['title' => 'Citas programadas para hoy' ] )
			->modal('patient/modal.create',[
					'title' => 'Agregar información basica',
				],[
					'settings_how_found_us' => implode(',', $this->Custom_Setting_DB->getElements('setting_how_found_us', true ) ),
					'insurance_plans' => $this->Custom_Setting_DB->getElements('setting_insurance')
				])
			->render('patient/view.panel.patient.list');
	}

	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	function search( $maxRecords = 0, $page = 0)
	{

		$this->load->model([ 'Appointment_Model'  => 'Appointment_DB'] );

		$result = $this->Patient_DB->getPagination( 
			$maxRecords, 
			$page, 
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		
		foreach ($result['result_data'] as &$patient) {
			$patient['appointment'] = $this->Appointment_DB->near( $patient['id'] );
		}

		return $this->template->json( $result );
	}

	
	/**
	 * @route:{get}filter
	 */
	function filter()
	{
		$q = trim($this->input->get('q'));
		
		if( $q == '')
		{	
			$this->template->json( ['message' => 'Requiere una palabra' ]);
		}
		
		$fullQuery = explode(":",$q);
		$names     = trim($fullQuery[0]);
		$birth     = isset($fullQuery[1]) ?  trim($fullQuery[1]) : '';  

		$this->db
			->select([
				'id',
				'name',
				'middle_name',
				'last_name',
				"date_of_birth",
			])
			->from('patient')
			->like("CONCAT(name,' ',last_name)" , $names )
			->like("date_of_birth", $birth )
			->order_by('name, middle_name, last_name')
			->where(['instance_id' => $_SESSION['User_DB']->instance_id])
			->limit( 100 );
		
		$patients = $this->db->get()->result();

		//PR($this->db->last_query());

		$this->template->json( [
			'total_count' => count( $patients ),
			'items' => $patients 
		]);
	}
		
	/**
	 *	@route:{post}save-from-appointment
	 */
	function save_from_appointment()
	{
		$this->form_validation
			->set_rules('name','Nombre del paciente','xss_clean|required|trim|max_length[120]')
			->set_rules('middle_name','Segundo nombre','xss_clean|trim|max_length[120]')
			->set_rules('last_name','Apellidos','xss_clean|required|trim|max_length[120]')
			->set_rules('phone','Teléfono','xss_clean|required|trim|max_length[20]')
			->set_rules('date_of_birth','Fecha de nacimiento','xss_clean|required|trim|exist_date|date_max_today')
			->set_rules('how_found_us','¿Como nos encontraste?','trim|xss_clean|required|max_length[75]')
		;

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] =  $this->form_validation->error_string();
		}
		else
		{

			$this->Custom_Setting_DB->insertIfNew( $this->input->post('how_found_us'), 'setting_how_found_us' );
			$patient_id = $this->Patient_DB->create_basic_patient( (array) $this->input->post() ); 
			
			$user_full_name = trim($this->current_user->names.' '.$this->current_user->last_name);
			
			$this->Patient_Warnings_DB->description = 'Creado automáticamente mediante citas';
			$this->Patient_Warnings_DB->patient_id  = $patient_id;
			$this->Patient_Warnings_DB->user_create = $user_full_name;
			$warning_id = $this->Patient_Warnings_DB->save();
			
			$response = [
				'status' => 1,
				'message' => 'Added patient',
				'patient' => $this->Patient_DB->get_info( $patient_id )
			];
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{get}similarPatients
	 */
	function similarPatients()
	{
		if( !$this->input->get('date_of_birth') ||
			!$this->input->get('name') || 
			!$this->input->get('last_name')  )
		{
			return $this->template->json(Array(
				'status' => 1,
				'patients' => []
			)); 
		}

		$name       = trim( strtoupper($this->input->get('name')) );
		$lastName   = trim( strtoupper($this->input->get('last_name')) );
		$middleName = trim( strtoupper($this->input->get('middle_name')) );

		$this->db->select('id, create_at,phone')->from('patient');
		$this->db->where([
			'name' => $name,
			'last_name' => $lastName,
			'middle_name' => $middleName,
			'date_of_birth' => $this->input->get('date_of_birth')
		]);

		$patients = $this->db->get()->result_array();
		
		if(!$patients)
			return $this->template->json(Array(
				'status' => 1,
				'patients' => []
			));

		$this->load->model([ 'Appointment_Model'  => 'Appointment_DB'] );

		foreach ($patients as &$patient) {
			$patient['near_appointment'] = $this->Appointment_DB->near( $patient['id'] );;
		}

		return $this->template->json(Array(
			'status' => 1,
			'patients' => $patients
		));

	}

	/**
	 * @route:{post}save
	 */
	function save()
	{
	
		$save_from_appointment = $this->input->get('save_from_appointment');

		$this->form_validation
			->set_rules('name','Nombre del paciente','xss_clean|required|trim|max_length[120]')
			->set_rules('middle_name','Segundo nombre','xss_clean|trim|max_length[120]')
			->set_rules('last_name','Apellidos','xss_clean|required|trim|max_length[120]')
			->set_rules('phone','Teléfono','xss_clean|required|trim|max_length[20]')
			->set_rules('date_of_birth','Fecha de nacimiento','xss_clean|required|trim|exist_date|date_max_today')
			->set_rules('gender','Genero','xss_clean|required|trim|in_list[Male,Female]')
			->set_rules('how_found_us','¿Como nos encontraste?','trim|xss_clean|required|max_length[75]')
			->set_rules('interpreter_needed','¿Necesitas interprete?','required|in_list[Yes,No]')
			//->set_rules('advanced_directive_offered','Was advance directive offered','required|in_list[Yes,No]')
			//->set_rules('advanced_directive_taken','Taken directive','required|in_list[Yes,No]')
			->set_rules('insurance_primary_plan_name','Nombre del plan','xss_clean|trim')
			->set_rules('insurance_primary_identify','Numero de seguro','xss_clean|trim|max_length[25]')
		;

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] =  $this->form_validation->error_string();
		}
		else
		{		
			$this->Custom_Setting_DB->insertIfNew( $this->input->post('how_found_us'), 'setting_how_found_us' );
			
			$this->Patient_DB->insurance_primary_plan_name = $this->input->post('insurance_primary_plan_name');
			$this->Patient_DB->insurance_primary_identify  = $this->input->post('insurance_primary_identify');
			$patientId = $this->Patient_DB->create_basic_patient( (array) $this->input->post() ); 

			if( $save_from_appointment === "Yes" )
			{
				$user_full_name = trim($this->current_user->names.' '.$this->current_user->last_name);
				$this->Patient_Warnings_DB->description = 'Creado automáticamente mediante citas';
				$this->Patient_Warnings_DB->patient_id  = $patientId;
				$this->Patient_Warnings_DB->user_create = $user_full_name;
				$this->Patient_Warnings_DB->save();
			}
			
			$response = [
				'status' => 1,
				'edit_patient' => site_url("patient/detail/{$patientId}"),
				'patient' => $this->Patient_DB->get_info( $patientId ),
				'message' => 'Paciente agregado'
			];
			
		}

		$this->template->json( $response );
	}


	/**
	 * @route:{get}detail/(:num)
	 */
	function detail( $ID )
	{

		$patient = $this->db->select([
				'id',
			])
			->from('patient')
			->where(['id' => $ID ])
			->get()->row();

		if( !$patient )
		{
			redirect('/patient/');
		}
		
		$availableForRemove = $this->Patient_DB->isAvailableForRemove($ID);
		
		$completeRegister = $this->Patient_DB->requiredValues( $ID, FALSE );

		if($completeRegister)
		{
			$completeRegister[] = "<br><p><strong>Estos valores son requeridos para poder ver la historia clinica</strong></p>";
			
			$this->notify->error($completeRegister);
		}

		$this->template
			->set_title('Demographics')
			->body([
				'ng-app' => 'ng_patient_detail',
				'ng-controller' => 'ctrl_patient_detail',
				'ng-init' => 'initialize('.$ID.')',
			])
			->modal('patient/detail/modal.patient.detail.about',[
				'title' => 'Acerca del paciente',
				'size' => 'modal-xl'
			])
			->modal('patient/detail/modal.patient.detail.address',['title' => 'Dirección del paciente','size' => 'modal-md'] )
			->modal('patient/detail/modal.patient.detail.insurance_primary',['title' => 'Seguro principal del paciente','size' => 'modal-md'] )
			->modal('patient/detail/modal.patient.detail.insurance_secondary',['title' => 'Seguro secundario del paciente','size' => 'modal-md'])
			->modal('patient/detail/modal.patient.detail.member',['title' => 'Membresia','size' => 'modal-md'])
			->modal('patient/detail/modal.patient.detail.responsible',['title' => 'Responsable'])
			->modal('patient/detail/modal.patient.detail.emergency',['title' => 'Contacto de emergencia'])
			->modal('patient/detail/modal.preventions', [ 'title' => 'Prevenciones'] )
			->modal('patient/warnings/modal.patient.warning.create',['title' => 'Añadir alerta','size' => 'modal-md'])
			->modal('patient/warnings/modal.patient.warning.log',['title' => 'Registro de alerts'])
			->js('patient/patient.detail')
			->render('patient/view.content.patient.detail');
	}

	/**
	 * @route:{get}initialize/(:num)
	 */
	function initialize( $ID )
	{

		$patient = $this->Patient_DB->get_info($ID);
		
		if(!$patient)
		{	
			$this->template->json( ['patient_not_found' => true] );
		}

		$mStatu = [
			0 => 'Sin especificar',
			1 => 'Soltero',
			2 => 'Casado',
			3 => 'Otro'
		];
		
		$patient_details = [
			'patient' => $patient,	
			'catalog_allergies' => $this->Custom_Setting_DB->getElements('setting_allergie'),
			'insurance_plans' => $this->Custom_Setting_DB->getElements('setting_insurance'),
			//'insurance_types' => $this->Custom_Setting_DB->getElements('insurance_type'),
			'settings_marital_status' => $mStatu,
			'settings_languages' => $this->Custom_Setting_DB->getElements('setting_language', true),
			'settings_how_found_us' => $this->Custom_Setting_DB->getElements('setting_how_found_us'),
			'warnings' => $this->Patient_Warnings_DB->getResultsBy(['patient_id' => $ID ])
		];
		
		$this->template->json( $patient_details , 0 );
	}
	
	/**
	 * @route:{post}update/(about|address|insurance_status|insurance_primary|insurance_secondary|member|responsible|emergency|responsible_self)
	 */
	function update( $type )
	{
		$response = [];

		if($type === 'about')
		{
			$response = $this->_update_about();
		}
		else if($type === 'address')
		{
			$response = $this->_update_address();
		}
		else if($type === 'insurance_status')
		{	
			$response = $this->_update_insurance_status();
		}
		else if($type === 'insurance_primary')
		{	
			$response = $this->_update_insurance_primary();
		}
		else if($type === 'insurance_secondary')
		{	
			$response = $this->_update_insurance_secondary();
		}
		else if( $type === 'member' )
		{
			$response = $this->_update_member();
		}
		else if( $type === 'responsible_self' )
		{
			$response = $this->_update_responsible_self();
		}
		else if( $type === 'responsible' )
		{
			$response = $this->_update_responsible();
		}
		else if( $type === 'emergency' )
		{
			$response = $this->_update_emergency();
		}

		$response['patient'] = $this->Patient_DB->get_info( $this->input->post('id') );

		$this->template->json( $response );
	}

	private function _update_emergency()
	{
		$this->form_validation
			->set_rules('id','Patient id', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('emergency_name','Nombre', 'trim|xss_clean|max_length[75]|required')
			->set_rules('emergency_middle_name','Segundo nombre', 'trim|xss_clean|max_length[75]')
			->set_rules('emergency_last_name','Apellidos', 'trim|xss_clean|max_length[75]')
			->set_rules('emergency_gender','Genero',"trim|in_list['',Male,Female]")
			->set_rules('emergency_phone','Teléfono','trim|xss_clean|max_length[14]|required')
			->set_rules('emergency_phone_alt','Teléfono alternativo','trim|xss_clean|max_length[14]')
			->set_rules('emergency_address','Dirección','trim|xss_clean|max_length[120]')
			->set_rules('emergency_address_zipcode','Codigo postal','trim|xss_clean|max_length[20]')
			->set_rules('emergency_address_city','Ciudad','trim|xss_clean|max_length[150]')
			->set_rules('emergency_address_state','Estado','trim|xss_clean|max_length[75]')
			->set_rules('emergency_relationship','Relación','trim|xss_clean|max_length[120]|required')
		;

		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{
			$this->Patient_DB->emergency_name            = $this->input->post('emergency_name');
			$this->Patient_DB->emergency_middle_name     = $this->input->post('emergency_middle_name');
			$this->Patient_DB->emergency_last_name       = $this->input->post('emergency_last_name');
			$this->Patient_DB->emergency_gender          = $this->input->post('emergency_gender');
			$this->Patient_DB->emergency_phone           = $this->input->post('emergency_phone');
			$this->Patient_DB->emergency_phone_alt       = $this->input->post('emergency_phone_alt');
			$this->Patient_DB->emergency_address         = $this->input->post('emergency_address');
			$this->Patient_DB->emergency_address_zipcode = $this->input->post('emergency_address_zipcode');
			$this->Patient_DB->emergency_address_city    = $this->input->post('emergency_address_city');
			$this->Patient_DB->emergency_address_state   = $this->input->post('emergency_address_state');
			$this->Patient_DB->emergency_relationship    = $this->input->post('emergency_relationship');
			
			$this->Patient_DB->save( $this->input->post('id') );
			
			return [
				'message' => 'Paciente responsable actualizado',
				'status' => 1
			];
		}
	}

	private function _update_responsible_self()
	{
		
		$patient = $this->db
			->select(['responsible_self'])->from('patient')
			->where([
				'id' => $this->input->post('id')
		])->get()->row();

		if( $patient )
		{
			if( $patient->responsible_self === 'Yes' )
			{	
				$this->Patient_DB->responsible_name = '';
				$this->Patient_DB->responsible_self = 'No';
			}
			else
			{
				$this->Patient_DB->responsible_name = 'Self';
				$this->Patient_DB->responsible_self = 'Yes';
			}
			
			$this->Patient_DB->save( $this->input->post('id') );

			return [
				'message' => 'Entidad Responsable fue actualizada',
				'status' => 1
			];
		}
		else
		{
			return [
				'message' => 'Paciente no encontrado',
				'status' => 0
			];
		}

		
	}

	private function _update_responsible()
	{
		
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('responsible_name','Nombre del responsable', 'trim|xss_clean|max_length[75]|required')
			->set_rules('responsible_middle_name','Segundo nombre del responsable', 'trim|xss_clean|max_length[75]')
			->set_rules('responsible_last_name','Apellidos del responsable', 'trim|xss_clean|max_length[75]')
			->set_rules('responsible_gender','Genero del responsable',"trim|in_list['',Male,Female]")
			->set_rules('responsible_phone','Teléfono del responsable','trim|xss_clean|max_length[14]')
			->set_rules('responsible_phone_alt','Teléfono alternativo del responsable','trim|xss_clean|max_length[14]')
			
			->set_rules('responsible_address','Dirección del responsable','trim|xss_clean|max_length[120]')
			->set_rules('responsible_address_zipcode','Codigo postal del responsable','trim|xss_clean|max_length[20]')
			->set_rules('responsible_address_city','Ciudad del responsable','trim|xss_clean|max_length[20]')
			->set_rules('responsible_address_state','Estado del responsable','trim|xss_clean|max_length[20]')
			->set_rules('responsible_relationship','Relación','trim|xss_clean|max_length[120]')
		;
		
		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{
			$this->Patient_DB->responsible_name            = $this->input->post('responsible_name');
			$this->Patient_DB->responsible_middle_name     = $this->input->post('responsible_middle_name');
			$this->Patient_DB->responsible_last_name       = $this->input->post('responsible_last_name');
			$this->Patient_DB->responsible_gender          = $this->input->post('responsible_gender');
			$this->Patient_DB->responsible_phone           = $this->input->post('responsible_phone');
			$this->Patient_DB->responsible_phone_alt       = $this->input->post('responsible_phone_alt');
			$this->Patient_DB->responsible_address         = $this->input->post('responsible_address');
			$this->Patient_DB->responsible_address_zipcode = $this->input->post('responsible_address_zipcode');
			$this->Patient_DB->responsible_address_city    = $this->input->post('responsible_address_city');
			$this->Patient_DB->responsible_address_state   = $this->input->post('responsible_address_state');
			$this->Patient_DB->responsible_relationship    = $this->input->post('responsible_relationship');
			$this->Patient_DB->responsible_self            = $this->input->post('responsible_self');

			$this->Patient_DB->save( $this->input->post('id') );
			
			return [
				'message' => 'Responsable del paciente actualizado',
				'status' => 1
			];
		}
	}

	private function _update_member()
	{
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('membership_name','Nombre/titulo de membresia', 'trim|xss_clean|max_length[75]')
			->set_rules('membership_date','Fecha de membresia', 'trim|xss_clean|exist_date')
			->set_rules('membership_type','Tipo de membresia', 'trim|xss_clean|max_length[75]')
			->set_rules('membership_notes','Notas de membresia','trim|xss_clean|max_length[500]')
		;

		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{	
			
			$this->Patient_DB->membership_name  = $this->input->post('membership_name');
			$this->Patient_DB->membership_date  = $this->input->post('membership_date');
			$this->Patient_DB->membership_type  = $this->input->post('membership_type');
			$this->Patient_DB->membership_notes = $this->input->post('membership_notes');

			$this->Patient_DB->save( $this->input->post('id') );

			return [
				'message' => 'Membresia del paciente actualizada',
				'status' => 1
			];
		}
	}

	private function _update_insurance_status()
	{

		$this->form_validation
			->set_rules('id','Id del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('column','Estatus de seguro incompleto', 'trim|xss_clean|in_list[insurance_primary_status,insurance_secondary_status]')
		;
		
		$column = $this->input->post('column');

		if( $this->form_validation->run() === FALSE)
		{	
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else if($column==='insurance_primary_status')
		{	
			$patient = $this->db
				->select([$column, 'insurance_primary_plan_name', 'insurance_primary_identify'])->from('patient')
				->where([
					'id' => $this->input->post('id')
			])->get()->row();

			if($patient->insurance_primary_plan_name==='' && $patient->{$column}==0)
			{
				return [
					'message' => 'Por favor, elija el nombre del plan de seguro primario'
				];
			}
			if(!$patient->insurance_primary_identify && $patient->{$column}==0)
			{
				return [
					'message' => 'Por favor, capture el ID del seguro primario'
				];
			}
		}
		else if($column==='insurance_secondary_status')
		{
			$patient = $this->db
				->select([$column, 'insurance_secondary_plan_name', 'insurance_secondary_identify'])->from('patient')
				->where([
					'id' => $this->input->post('id')
			])->get()->row();

			if($patient->insurance_secondary_plan_name==='' && $patient->{$column}==0 )
			{
				return [
					'message' => 'Por favor, elija el nombre del plan de seguro secundario'
				];
			}		
			if(!$patient->insurance_secondary_identify && $patient->{$column}==0)
			{	
				return [
					'message' => 'Por favor, capture el ID del seguro secundario'
				];
			}
		}
		
		$this->Patient_DB->{$column} = ($patient->{$column} == 1 ) ? 0 : 1;
		$this->Patient_DB->save( $this->input->post('id') );

		return [
			'message' => 'Estatus de seguro actualizado',
			'status' => 1
		];
		
	}

	private function _update_insurance_secondary()
	{
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('insurance_secondary_plan_name','Nombre de seguro', 'trim|xss_clean|max_length[75]')
			->set_rules('insurance_secondary_identify','Numero de seguro', 'trim|xss_clean|max_length[75]')
			->set_rules('insurance_secondary_notes','Notas del seguro', 'trim|xss_clean|max_length[250]')
		;

		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{	
			$this->Patient_DB->insurance_secondary_plan_name  = $this->input->post('insurance_secondary_plan_name');
			$this->Patient_DB->insurance_secondary_identify = $this->input->post('insurance_secondary_identify');
			$this->Patient_DB->insurance_secondary_notes    = $this->input->post('insurance_secondary_notes');
			$status = 1;
			if(!$this->input->post('insurance_secondary_identify') || !$this->input->post('insurance_secondary_plan_name'))
			{
				$status = 0;
				$this->Patient_DB->insurance_secondary_status = 0;
			}

			$this->Patient_DB->save( $this->input->post('id') );

			return [
				'message' => 'Seguro secundario del paciente actualizado',
				'status' => 1,
				'new_status' => $status
			];
		}
	}

	private function _update_insurance_primary()
	{
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('insurance_primary_plan_name','Nombre del seguro', 'trim|xss_clean|max_length[75]')
			->set_rules('insurance_primary_identify','Numero de seguro', 'trim|xss_clean|max_length[75]')
			->set_rules('insurance_primary_notes','Notas del seguro', 'trim|xss_clean|max_length[250]')
		;

		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{
			$this->Patient_DB->insurance_primary_plan_name = $this->input->post('insurance_primary_plan_name');
			$this->Patient_DB->insurance_primary_identify  = $this->input->post('insurance_primary_identify');
			$this->Patient_DB->insurance_primary_notes     = $this->input->post('insurance_primary_notes');
			$status = 1;
			if(!$this->input->post('insurance_primary_plan_name') || !$this->input->post('insurance_primary_identify'))
			{
				$status = 0;
			}
			$this->Patient_DB->insurance_primary_status = $status;
			$this->Patient_DB->save( $this->input->post('id') );
			
			return [
				'message' => 'Seguro primario del paciente actualizado',
				'status' => 1,
				'new_status' => $status
			];
		}
	}

	private function _update_address()
	{
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('address','Dirección', 'trim|xss_clean|max_length[120]')
			->set_rules('address_zipcode','Codigo postal', 'trim|xss_clean|max_length[20]')
			->set_rules('address_city','Ciudad', 'trim|xss_clean|max_length[150]')
			->set_rules('address_state','Estado', 'trim|xss_clean|max_length[75]')
		;

		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}	
		else
		{	

			$this->Patient_DB->address         = $this->input->post('address');
			$this->Patient_DB->address_zipcode = $this->input->post('address_zipcode');
			$this->Patient_DB->address_city    = $this->input->post('address_city');
			$this->Patient_DB->address_state   = $this->input->post('address_state');
			
			$this->Patient_DB->save( $this->input->post('id') );

			return [
				'message' => 'Dirección del paciente actualizada',
				'status' => 1
			];
		}
	}

	private function _update_about()
	{
		$this->form_validation
			->set_rules('id','ID del paciente', 'required|xss_clean|numeric|exist_data[patient.id]')
			->set_rules('name','Nombre', 'required|trim|xss_clean|max_length[75]')
			->set_rules('middle_name','Segundo Nombre', 'trim|xss_clean|max_length[75]')
			->set_rules('last_name','Apellidos', 'required|trim|xss_clean|max_length[75]')
			->set_rules('gender','Genero', 'required|in_list[Male,Female]')
			->set_rules('phone','Teléfono', 'trim|required|xss_clean|max_length[12]')
			->set_rules('phone_memo','Descripción de teléfono', 'trim|xss_clean|max_length[75]')
			->set_rules('phone_alt','Teléfono alternativo', 'trim|xss_clean|max_length[12]')
			->set_rules('phone_alt_memo','Descripción de teléfono alternativo', 'trim|xss_clean|max_length[75]')
			->set_rules('date_of_birth','Fecha de nacimiento', 'required|trim|xss_clean|max_length[75]|exist_date|date_max_today')
			->set_rules('how_found_us','¿Como nos encontraste?', 'trim|xss_clean|max_length[75]')
			->set_rules('email','Email', 'trim|xss_clean|valid_email')
			->set_rules('ethnicity','Etnicidad', 'trim|xss_clean|max_length[70]')
			->set_rules('blood_type','Tipo Sanguineo', 'trim|xss_clean|max_length[70]')
			->set_rules('language','idioma', 'trim|xss_clean|max_length[75]')
			->set_rules('interpreter_needed','¿Necesita un interprete?',"required|in_list[Yes,No]")
			//->set_rules('advanced_directive_offered','Was a advance directive offered',"required|in_list['',Yes,No]")
			//->set_rules('advanced_directive_taken','Directive taken',"required|trim|in_list[Yes,No]")
			->set_rules('discount_type','Descuento de empresa', 'trim|xss_clean|max_length[75]')
			->set_rules('marital_status','Estado civil','trim|xss_clean')
		;
		
		if( $this->form_validation->run() === FALSE)
		{
			return [
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{
			
			$this->Patient_DB->name                 = mb_strtoupper( $this->input->post('name'));
			$this->Patient_DB->middle_name          = mb_strtoupper( $this->input->post('middle_name'));
			$this->Patient_DB->last_name            = mb_strtoupper( $this->input->post('last_name'));
			$this->Patient_DB->gender               = $this->input->post('gender');
			$this->Patient_DB->phone                = $this->input->post('phone');
			$this->Patient_DB->phone_memo           = $this->input->post('phone_memo');
			$this->Patient_DB->phone_alt            = $this->input->post('phone_alt');
			$this->Patient_DB->phone_alt_memo       = $this->input->post('phone_alt_memo');
			$this->Patient_DB->date_of_birth        = $this->input->post('date_of_birth');
			$this->Patient_DB->how_found_us         = $this->input->post('how_found_us');
			$this->Patient_DB->email                = $this->input->post('email');
			$this->Patient_DB->ethnicity            = $this->input->post('ethnicity');
			$this->Patient_DB->blood_type           = $this->input->post('blood_type');
			$this->Patient_DB->language             = $this->input->post('language');
			$this->Patient_DB->discount_type        = $this->input->post('discount_type');
			$this->Patient_DB->marital_status       = $this->input->post('marital_status');
			
			$this->Patient_DB->interpreter_needed         = $this->input->post('interpreter_needed');
			$this->Patient_DB->advanced_directive_offered = $this->input->post('advanced_directive_offered');
			$this->Patient_DB->advanced_directive_taken   = $this->input->post('advanced_directive_taken');
			
			$this->Patient_DB->save( $this->input->post('id') );
			
			$this->Custom_Setting_DB->insertIfNew( $this->input->post('language') , 'setting_language', ',' );
			$this->Custom_Setting_DB->insertIfNew( $this->input->post('how_found_us') , 'setting_how_found_us');
			
			return [
				'message' => 'Paciente actualizado',
				'status' => 1
			];
		}
	}


}