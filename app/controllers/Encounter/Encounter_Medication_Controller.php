<?php
/**
* @route:encounter/medication
*/
class Encounter_Medication_Controller extends APP_User_Controller
{

	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Medication_Model' => 'Encounter_Medication_DB',
			'Patient_Model' => 'Patient_DB'
		]);

		$this->Encounter_DB->set_user( $this->current_user );
	}

	/**
	 * @route:printMedications/(:num)
	 */
	function print( $patientID )
	{
		if( !$patient = $this->Patient_DB->get_info( $patientID ) )
		{
			show_error('Paciente no encontrado',404);
		}
		
		$medications = $this->Encounter_Medication_DB->current_medications( $patientID, FALSE );
		$this->load->library('print/PDF_Medications');

		$params = Array(
			'patient' => $patient,
			'medications' => $medications,
		);

		$this->pdf_medications->body( Array(
			'patient' => $patient,
			'medications' => $medications
		));
		
		$this->pdf_medications->output();
	}
	
	/**
	 * @route:save/(:num)
	 */
	function save( $ID )
	{
		$response = ['status' => 0];

		if( ! ( $encounter = $this->Encounter_DB->get_info( $ID ) ) )
		{	
			show_error('Encounter not found', 404);
		}

		$this->form_validation
			->set_rules('title','Medicación','trim|required|xss_clean|max_length[70]|callback__valid_allergy['.$encounter->patient_id.']')
			->set_rules('amount','Cantidad','xss_clean|required|max_length[75]')
			->set_rules('refill','Renovación','xss_clean|trim|numeric')
			->set_rules('chronic','Crónico','required|in_list[Yes,No]')
			->set_rules('directions','Indicaciones','trim|required|xss_clean')
		;

		if($this->form_validation->run() === false )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Estatus de consulta firmado';
		}	
		else
		{		
			
			$this->Custom_Setting_DB->insertIfNew( $this->input->post('title'), 'setting_medication');
			
			if( (int)$this->input->post('id') > 0 )
			{		
				$this->Encounter_Medication_DB->title        = $this->input->post('title');
				$this->Encounter_Medication_DB->amount       = $this->input->post('amount');
				$this->Encounter_Medication_DB->refill       = $this->input->post('refill');
				$this->Encounter_Medication_DB->chronic      = $this->input->post('chronic');
				$this->Encounter_Medication_DB->directions   = $this->input->post('directions');
				$this->Encounter_Medication_DB->save( $this->input->post('id') );
				
				$response = [
					'status' => 1,
					'message' => 'Medicación fue actualizada',
					'medication' => $this->Encounter_Medication_DB->get(  $this->input->post('id') )
				];

				$this->Encounter_DB->set_activity( $encounter->id , 'encounter_medication_edit');
			}
			else
			{		
				$this->Encounter_Medication_DB->encounter_id = $encounter->id;
				$this->Encounter_Medication_DB->patient_id 	 = $encounter->patient_id;
				$this->Encounter_Medication_DB->title        = $this->input->post('title');
				$this->Encounter_Medication_DB->amount       = $this->input->post('amount');
				$this->Encounter_Medication_DB->refill       = $this->input->post('refill');
				$this->Encounter_Medication_DB->chronic      = $this->input->post('chronic');
				$this->Encounter_Medication_DB->directions   = $this->input->post('directions');
				$medication_id = $this->Encounter_Medication_DB->save();
				
				$response = [
					'status' => 1,
					'message' => 'Medicación agregada',
					'medication' => $this->Encounter_Medication_DB->get( $medication_id )
				];
				
				$this->Encounter_DB->set_activity( $encounter->id , 'encounter_medication_add');
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
		
		if( !($medication = $this->Encounter_Medication_DB->get($ID)) )
		{
			show_error('Medicación no encontrada', 404);
		}
		
		$encounter = $this->Encounter_DB->get( $medication->encounter_id );
			
		if( (int)$encounter->status != 1 )
		{
			show_error('Estatus de consulta firmado', 404);
		}
		/**
		 * get encounter
		 */

		$this->Encounter_Medication_DB->delete( $ID );
		$response = [
			'status' => 1, 
			'message' => 'Medicación eliminada'
		]; 
			
		$this->Encounter_DB->set_activity( $medication->encounter_id , 'encounter_medication_remove');

		$this->template->json( $response );
	}

	public function _valid_allergy( $str , $patient_id  = 0 )
	{
		
		
		if( $allergies = $this->Patient_DB->getAllergies( $patient_id) )
		{
			if( in_array(strtolower($str), array_map("strtolower",$allergies)) )
			{	
				$this->form_validation->set_message('_valid_allergy', "El paciente es alergico al medicamento");
				return FALSE;
			}
		}
        
        return TRUE;   
	}
}