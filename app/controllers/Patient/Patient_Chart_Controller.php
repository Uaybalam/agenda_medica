<?php
/**
* @route:patient/chart
*/
class Patient_Chart_Controller extends APP_User_Controller
{	
	/**
	 * @route:(:num)
	 */
	function index( $ID )
	{	

		$this->load->model([
			'Appointment_Model' => 'Appointment_DB',
			'Patient_Vaccines_Model' => 'Patient_Vaccines_DB',
			'Patient_Model' => 'Patient_DB'
		]);
		
		$encounters = $this->db->select('id,create_at')
			->from('encounter')
			->where(['patient_id' => $ID, 'status' => 2])
			->order_by('id DESC')
			->get()->result_array();
		
		$this->Patient_DB->requiredValues( $ID  );
		
		$this->validate_access(['manager','nurse','medic','reception','billing']);
		
		$this->template

			->set_title('Expediente del paciente')
			->body([
				'ng-app' => 'ng_patient_chart',
				'ng-controller' => 'ctrl_patient_chart',
				'id'=>'id_patient_chart',
				'ng-init' => 'initialize('.$ID.')',
			])
			->modal('patient/modal.create.contact', [
				'title' => 'Agregar razón para contacto', 
				'size' => 'modal-md'
			])
			->modal('patient/communicate/modal.create.communication',['title' => 'Completar notas' ], [
				'visit_types' => $this->Appointment_DB->get_visit_types()
			])
			->modal('patient/chart/modal.vaccines',[
					'title' => 'Registro de inmunización', 
					'size' => 'modal-xl'
			])
			->modal('patient/chart/modal.related.file.preview',[
					'title' => 'Vista previa de documento', 
					'size' => 'modal-xl'
			])
			->modal('patient/warnings/modal.patient.warning.reply',['title' => 'Responder notificación'])
			->modal('patient/warnings/modal.patient.warning.create',['title' => 'agregar alerta'])
			->modal('patient/chart/modal.related.file',['title' => 'Agregar archivo relacionado'],  [
				'encounters' => $encounters
			])
			->modal('patient/chart/modal.list.diagnosis',['title' => 'Lista de diganostico'], ['patient_id' => $ID ] )
			->modal('patient/chart/modal.list.medications',['title' => 'Lista de medicamentos'], ['patient_id' => $ID ])
			->modal('patient/detail/modal.history.active',['title' => 'Historial Médico Actual', 'size' => 'modal-xl'])
			->modal('patient/communicate/modal.history.communication',['title' => 'Detalles del historial','size' => 'modal-xl' ] )
			->modal('appointment/modal.current.date',['title' => 'Citas de fecha actual' ] )
			->modal('encounter/detail/modal.vitals', [ 'title' => 'Signos vitales de consulta'] )
			->modal('patient/chart/modal.tuberculosis', [ 'title' => 'Tuberculosis'] )
			->modal('appointment/modal.room',['title' => 'Seleccionar cuarto para paciente', 'size' => 'modal-md'])
			->js('patient/chart.index')
			->render('patient/chart/view.patient.chart.index' );
	}


	/**
	 * @route:init/(:num)
	 */
	function init( $ID )
	{
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Patient_History_Model' => 'Patient_History_DB',
			'Appointment_Model' => 'Appointment_DB',
			'Patient_Communication_Model' => 'Patient_Communication_DB',
			'Patient_Related_Files_Model' => 'Patient_Related_Files_DB',
			'Patient_Warnings_Model' => 'Patient_Warnings_DB',
			'Patient_Vaccines_Model' => 'Patient_Vaccines_DB',
			'Patient_Tuberculosis_Model' => 'Patient_Tuberculosis_DB',
			'Custom_Setting_Model' => 'Custom_Setting_Model',
		]);

		if(!( $patient = $this->Patient_DB->get_info( $ID ) ) )
		{
			
			show_error('Patient not found', 404);
		}

		if($patient->insurance_primary_status)
		{
			$catalog_insurances[] = "$patient->insurance_primary_plan_name|$patient->insurance_primary_identify";
		}
		if($patient->insurance_secondary_status)
		{
			$catalog_insurances[] = "$patient->insurance_secondary_plan_name|$patient->insurance_secondary_identify";
		}
		
		$patient_related_files          = $this->Patient_Related_Files_DB->getByPatient( $ID );
		$vitals_default_chief_complaint = "";
		
		foreach ($patient_related_files as $key => $value) 
		{
			$extension = explode("/",mime_content_type($this->patient_path($ID) .'/'. $patient_related_files[$key]->file_name))[0];
			$patient_related_files[$key]->file_name = $extension;
		} 

		if( $last_appointment =  $this->Appointment_DB->get_last_appointment( $ID ) )
		{
			
			$appointment  = $this->Appointment_DB->get_info( $last_appointment );
			$gender       = ["Male" => "Masculino", "Female" => "Femenino"];
			$data_replace = [
				'{visit_type}'    => $appointment->visit_type,
				'{id}'            => $patient->id,
				'{gender}'        => $gender[$patient->gender],
				'{age}'           => human_age($patient->date_of_birth ),
				'{name}'          => $patient->name,
				'{last_name}'     => $patient->last_name,
				'{date_of_birth}' => $patient->date_of_birth
			];
			
			$vitals_default_chief_complaint = str_replace( 
				array_keys($data_replace), 
				array_values($data_replace),
				\libraries\Administration::getValue('chief_complaint_default')
			);
			
		};

		$data = $this->db->select(['date_appointment'])->from('appointment')
						 ->where(["patient_id" => $ID,
									 "DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y') = " => date("m/d/Y"),])
						 ->get()->result();

		$response = [
			"appointments" => $data,

			'questions_ins_inmigration' => \libraries\Administration::getValue('questions_ins_inmigration'),
			
			'patient' => $patient,

			'last_appointment' => $last_appointment,

			'vitals_default_chief_complaint' => $vitals_default_chief_complaint,

			'status' => [
				
				'visit_types' => $this->Appointment_DB->get_visit_types(),

				'appointment' => $this->Appointment_DB->get_status_array()
			],
			
			'encounters' => $this->Encounter_DB->get_list_chart( $ID ),
			
			'catalog_allergies' => $this->Custom_Setting_Model->getElements('allergies',true),
			
			'patient_history' => $this->Patient_History_DB->get_active_diseases( $ID ),

			'communications' => $this->Patient_Communication_DB->get_by_patient( $ID ),
			
			'typesOfCommunications' => $this->Patient_Communication_DB->get_available_types(),
			
			'history_active' => $this->db->where( ['patient_id' => $ID ])->get('patient_history_active')->row(),

			'catalog_related_file_types' => $this->Patient_Related_Files_DB->get_types(),
			
			'related_files' => $patient_related_files,
			
			'warnings' => $this->Patient_Warnings_DB->get_data_patient($ID),
			
			'vaccines_data' => $this->Patient_Vaccines_DB->get_data( $ID ),
			
			'vaccines_settings' => $this->Patient_Vaccines_DB->init_options, 
			
			'patient_tuberculosis' => $this->Patient_Tuberculosis_DB->get( $ID )
		];
		
		
		$response['opened']       = \libraries\Administration::getValue('opend');
		$response['closed']       = \libraries\Administration::getValue('closed');
		$response['time']         = \libraries\Administration::getValue('appointment_time');

		$this->template->json(  $response ,'');
	}

	/**
	 * @route:diagnostics/(:num)
	 */
	function diagnostics( $ID )
	{
		$this->load->model(['Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB']);
		
		$this->template->json([
			'diagnostics' => $this->Encounter_Diagnosis_DB->current_diagnostics( $ID , FALSE )
		]);
	}

	/**
	 * @route:medications/(:num)
	 */
	function medications( $ID )
	{	
		$this->load->model(['Encounter_Medication_Model' => 'Encounter_Medication_DB']);
			
		$this->template->json([
			'medications' => $this->Encounter_Medication_DB->current_medications( $ID, FALSE  )
		]);
	}

}
