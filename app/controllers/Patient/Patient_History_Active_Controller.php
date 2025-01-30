<?php
/**
 * @route:patient/history-active
 */
class Patient_History_Active_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->validate_access(['manager','nurse', 'medic']);

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_History_Active_Model' => 'Patient_History_Active_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Encounter_Medication_Model' => 'Encounter_Medication_DB'
		]);
	}

	
	/**
	 * @route:pdf/(:num)
	 */
	function pdf( $ID )
	{

		$this->load->library('print/PDF_Active_History');
		
		if( !$patient = $this->Patient_DB->get_info( $ID ) )
		{
			show_error('Patient not found',404);
		}
		
		$data = [
			'activeHX' => $this->Patient_History_Active_DB->getRowBy( ['patient_id' => $ID ] ),
			'diagnosis' => $this->Encounter_Diagnosis_DB->current_diagnostics( $ID , $chronic = false ),
			'medications' => $this->Encounter_Medication_DB->current_medications( $ID, $chronic = false )
		];

		$this->pdf_active_history->body( 
			$patient,
			$data
		);
		
		$this->pdf_active_history->output();
	}


	/**
	 * @route:{post}update
	 */
	function update()
	{
		
		$this->form_validation
			->set_rules('patient_id', 'Patient ID', 'required|exist_data[patient.id]')
			->set_rules('psa','PSA','trim|xss_clean|max_length[100]')
			->set_rules('last_influenza','Last influenza','trim|xss_clean|max_length[100]')
			->set_rules('last_chlamidia','Last chlamidia','trim|xss_clean|max_length[100]')
			->set_rules('last_physical','Last physical','trim|xss_clean|max_length[100]')
			->set_rules('last_sha','Last SHA','trim|xss_clean|max_length[100]')
			->set_rules('last_cholesterol','Last cholesterol','trim|xss_clean|max_length[100]')
			->set_rules('last_fobt','Last FOBT','trim|xss_clean|max_length[100]')
			->set_rules('last_colonoscopy','Last colonoscopy','trim|xss_clean|max_length[100]')
			->set_rules('last_sig','Last SIG','trim|xss_clean|max_length[100]')
			->set_rules('last_ecg','Last ECG','trim|xss_clean|max_length[100]')
			->set_rules('last_ppd','Last PPD','trim|xss_clean|max_length[100]')
			->set_rules('last_tetanous','Last Tetanous','trim|xss_clean|max_length[100]')
			->set_rules('last_pneumo','Last pneumo','trim|xss_clean|max_length[100]')
			
			->set_rules('pregnancy_birth_control','Birth control','trim|xss_clean|max_length[100]')
			->set_rules('pregnancy_last_pap','Last PAP','trim|xss_clean|max_length[100]')
			->set_rules('pregnancy_last_mamo','Last Mamo','trim|xss_clean|max_length[100]')
			
			->set_rules('last_ecg_normal','Last ecg normal','trim|xss_clean|in_list[Yes,No]')
			->set_rules('last_ppd_normal','Last ppd normal','trim|xss_clean|in_list[Yes,No]')
			->set_rules('last_tetanous_normal','Last tetanous normal','trim|xss_clean|in_list[Yes,No]')
			->set_rules('last_pneumo_normal','Last pneumo normal','trim|xss_clean|in_list[Yes,No]')
			->set_rules('last_pap_normal','Last pap normal','trim|xss_clean|in_list[Yes,No]')
			->set_rules('last_mamo_normal','Last mammo normal','trim|xss_clean|in_list[Yes,No]')

			->set_rules('pregnancy_count_succesfull','Pregnancies succesfull','trim|numeric')
			->set_rules('pregnancy_count_cesarean','Pregnancies C-Sections','trim|numeric')
			->set_rules('pregnancy_count_abortions','Pregnancies Abortions','trim|numeric')

			->set_rules('dexa_scan','Dexa scan','trim|xss_clean|max_length[100]')
			->set_rules('dexa_scan_normal','Dexa scan normal','trim|in_list[Yes,No]')
			->set_rules('hgba1c_hemoglobin','HGBA1C or Hemoglobin','trim|xss_clean|max_length[100]')
			->set_rules('hgba1c_hemoglobin_normal','Hemoglobin normal?','trim|in_list[Yes,No]')
			->set_rules('results','Results','trim|xss_clean|max_length[100]')
			->set_rules('results_normal','Results normal?','trim|in_list[Yes,No]')
			->set_rules('alcohol_history','','in_list[Yes,No,]')
			->set_rules('smoking_history','','in_list[Yes,No,]')
			->set_rules('vaccine_zoster','Zoster','trim|xss_clean|max_length[100]')
			->set_rules('vaccine_pneumo','Pneumo','trim|xss_clean|max_length[100]')
		;

		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{	
			$this->Patient_History_Active_DB->psa                        = $this->input->post('psa');
			$this->Patient_History_Active_DB->last_influenza             = $this->input->post('last_influenza');
			$this->Patient_History_Active_DB->last_chlamidia             = $this->input->post('last_chlamidia');
			$this->Patient_History_Active_DB->last_physical              = $this->input->post('last_physical');
			$this->Patient_History_Active_DB->last_sha                   = $this->input->post('last_sha');
			$this->Patient_History_Active_DB->last_cholesterol           = $this->input->post('last_cholesterol');
			$this->Patient_History_Active_DB->last_fobt                  = $this->input->post('last_fobt');
			$this->Patient_History_Active_DB->last_colonoscopy           = $this->input->post('last_colonoscopy');
			$this->Patient_History_Active_DB->last_sig                   = $this->input->post('last_sig');
			
			$this->Patient_History_Active_DB->last_ecg                   = $this->input->post('last_ecg');
			$this->Patient_History_Active_DB->last_ppd                   = $this->input->post('last_ppd');
			$this->Patient_History_Active_DB->last_tetanous              = $this->input->post('last_tetanous');
			$this->Patient_History_Active_DB->last_pneumo                = $this->input->post('last_pneumo');
			$this->Patient_History_Active_DB->pregnancy_birth_control    = $this->input->post('pregnancy_birth_control');
			$this->Patient_History_Active_DB->pregnancy_last_pap         = $this->input->post('pregnancy_last_pap');
			$this->Patient_History_Active_DB->pregnancy_last_mamo        = $this->input->post('pregnancy_last_mamo');
			$this->Patient_History_Active_DB->last_ecg_normal            = $this->input->post('last_ecg_normal');
			$this->Patient_History_Active_DB->last_ppd_normal            = $this->input->post('last_ppd_normal');
			$this->Patient_History_Active_DB->last_tetanous_normal       = $this->input->post('last_tetanous_normal');
			$this->Patient_History_Active_DB->last_pneumo_normal         = $this->input->post('last_pneumo_normal');
			$this->Patient_History_Active_DB->last_pap_normal            = $this->input->post('last_pap_normal');
			$this->Patient_History_Active_DB->last_mamo_normal           = $this->input->post('last_mamo_normal');
			$this->Patient_History_Active_DB->pregnancy_count_succesfull = $this->input->post('pregnancy_count_succesfull');
			$this->Patient_History_Active_DB->pregnancy_count_cesarean   = $this->input->post('pregnancy_count_cesarean');
			$this->Patient_History_Active_DB->pregnancy_count_abortions  = $this->input->post('pregnancy_count_abortions');
			
			$this->Patient_History_Active_DB->dexa_scan                = $this->input->post('dexa_scan');
			$this->Patient_History_Active_DB->dexa_scan_normal         = $this->input->post('dexa_scan_normal');
			$this->Patient_History_Active_DB->hgba1c_hemoglobin        = $this->input->post('hgba1c_hemoglobin');
			$this->Patient_History_Active_DB->hgba1c_hemoglobin_normal = $this->input->post('hgba1c_hemoglobin_normal');
			$this->Patient_History_Active_DB->results                  = $this->input->post('results');
			$this->Patient_History_Active_DB->results_normal           = $this->input->post('results_normal');
			$this->Patient_History_Active_DB->alcohol_history          = $this->input->post('alcohol_history');
			$this->Patient_History_Active_DB->smoking_history          = $this->input->post('smoking_history');
			$this->Patient_History_Active_DB->vaccine_zoster           = $this->input->post('vaccine_zoster');
			$this->Patient_History_Active_DB->vaccine_pneumo           = $this->input->post('vaccine_pneumo');
			
			$this->Patient_History_Active_DB->update([
				'patient_id' => $this->input->post('patient_id')
			]);
			
			$response = [
				'status' => 1,
				'message' => 'Se guardó el historial activo del paciente'
			];
		}

		$this->template->json( $response );
	}

}