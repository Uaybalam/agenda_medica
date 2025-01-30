<?php
/**
* @route:diagnosis
*/
class Encounter_Diagnosis_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Patient_Model' => 'Patient_DB'
		]);

		$this->Encounter_DB->set_user( $this->current_user );
	}

	/**
	 * @route:printDiagnosis/(:num)
	 */
	function print( $patientID )
	{
		if( !$patient = $this->Patient_DB->get_info( $patientID ) )
		{
			show_error('Paciente no encontrado',404);
		}
		
		$diagnosis = $this->Encounter_Diagnosis_DB->current_diagnostics( $patientID, FALSE );
		$this->load->library('print/PDF_Diagnosis');

		$params = Array(
			'patient' => $patient,
			'diagnosis' => $diagnosis,
		);

		$this->pdf_diagnosis->body( Array(
			'patient' => $patient,
			'diagnosis' => $diagnosis
		));
		
		$this->pdf_diagnosis->output();
	}
	
	/**
	 * @route:save/(:num)
	 */
	function save( $encounter_id )
	{
		if( ! ( $encounter = $this->Encounter_DB->get($encounter_id) ) )
		{
			show_error('Diagnóstico no encontrado' , 404);
		}

		$this->form_validation
			->set_rules('comment','Comentarios del diagnóstico','required|xss_clean|trim')
			->set_rules('chronic','¿Es cronico?','xss_clean|trim')
		;

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{		
			$diagnosis_id = 0;

			if( (int)$this->input->post('id') > 0 )
			{
				$diagnosis_id = $this->input->post('id');
				
				$this->Encounter_Diagnosis_DB->comment      = $this->input->post('comment');
				$this->Encounter_Diagnosis_DB->chronic      = ( $this->input->post('chronic') ) ? 1 : 0;
				$this->Encounter_Diagnosis_DB->save( $diagnosis_id );

				$this->Encounter_DB->set_activity( $encounter->id, 'encounter_diagnosis_edit');
			} 
			else
			{
				$this->Encounter_Diagnosis_DB->patient_id   = $encounter->patient_id;
				$this->Encounter_Diagnosis_DB->encounter_id = $encounter->id;
				$this->Encounter_Diagnosis_DB->comment      = $this->input->post('comment');
				$this->Encounter_Diagnosis_DB->chronic      = ( $this->input->post('chronic') ) ? 1 : 0;
				$diagnosis_id = $this->Encounter_Diagnosis_DB->save();
				
				$this->Encounter_DB->set_activity( $encounter->id, 'encounter_diagnosis_add');
			}
			
			$response = Array(
				'status' => 1,
				'message' => ( $this->input->post('id') > 0) ? 'Diagnóstico actualizado' : 'Diagnóstico agregado',
				'diagnosis' => $this->Encounter_Diagnosis_DB->get( $diagnosis_id ),
				'diagnosis_id' => $diagnosis_id,
			);
		}
		
		$this->template->json( $response ); 
	}

	/**
	 * @route:delete/(:num)
	 */
	function delete( $ID )
	{
		$response['status'] = 0;
		
		if( !($diagnosis = $this->Encounter_Diagnosis_DB->get($ID)) )
		{
			show_error('Diagnóstico no encontrado', 404);
		}

		if( (int)$diagnosis->status === 0)
		{
			$response['message'] = 'There is a chronic diagnosis';
		}
		else
		{	
			$this->Encounter_Diagnosis_DB->delete( $ID );
			$response = [
				'status' => 1, 
				'message' => 'Diagnóstico fue eliminado'
			]; 

			$this->Encounter_DB->set_activity( $diagnosis->encounter_id, 'encounter_diagnosis_remove');
		}

		$this->template->json( $response );
	}
}