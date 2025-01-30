<?php
/**
* @route:encounter/physicalexam
*/
class Encounter_Physicalexam_Controller extends APP_User_Controller
{
		
	function __construct(){
		parent::__construct();

		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Physicalexam_Model' => 'Encounter_Physicalexam_DB',
		]);
		
		$this->Encounter_DB->set_user($this->current_user );
	}

	/**
	 * @route:records
	 */
	function records()
	{
		echo 'records';
	}

	/**
	 * @route:save/(:num)
	 */
	function save( $ID )
	{
		$response = ['status' => 0];

		if( ! ( $encounter = $this->Encounter_DB->get_info( $ID ) ) )
		{	
			show_error('Consulta no encontrada', 404);
		}
		
		$this->form_validation
			->set_rules('title','Title','trim|required|xss_clean|max_length[70]')
			->set_rules('content','Content','trim|required|xss_clean')
		;

		if($this->form_validation->run() === false )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else if( (int)$encounter->status != 1 )
		{		
			$response['message'] = 'Consulta con estatus firmada';
		}
		else
		{		
			if( $this->input->post('id') > 0 )
			{		
			

				$this->Encounter_Physicalexam_DB->title        = $this->input->post('title');
				$this->Encounter_Physicalexam_DB->content      = $this->input->post('content');

				$physicalexam_id = $this->Encounter_Physicalexam_DB->save( $this->input->post('id') );

				$response = [
					'status' => 1,
					'message' => 'La exploración física fue actualizada',
					'physicalexam' => $this->Encounter_Physicalexam_DB->get(  $this->input->post('id') )
				];

				$this->Encounter_DB->set_activity( $ID , 'encounter_physicalexam_edit');
			}	
			else
			{	
				$this->Encounter_Physicalexam_DB->encounter_id = $encounter->id;
				$this->Encounter_Physicalexam_DB->patient_id   = $encounter->patient_id;	
				$this->Encounter_Physicalexam_DB->title        = $this->input->post('title');
				$this->Encounter_Physicalexam_DB->content      = $this->input->post('content'); 

				$physicalexam_id = $this->Encounter_Physicalexam_DB->save();
				
				$response = [
					'status' => 1,
					'message' => 'La exploración física fue añadida',
					'physicalexam' => $this->Encounter_Physicalexam_DB->get( $physicalexam_id )
				];

				$this->Encounter_DB->set_activity( $ID , 'encounter_physicalexam_add');
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
		
		if( !($exam = $this->Encounter_Physicalexam_DB->get($ID)) )
		{
			show_error('Medicamento no encontrado', 404);
		}
		
		$encounter = $this->Encounter_DB->get( $exam->encounter_id );
		if($encounter->status != 1 )
		{		
			show_error('Estados de consulta firmada', 404);
		}
		
		$this->Encounter_Physicalexam_DB->delete( $ID );
		$response = [
			'status' => 1, 
			'message' => 'Examen fisica eliminado'
		];

		$this->Encounter_DB->set_activity( $exam->encounter_id , 'encounter_physicalexam_remove');

		$this->template->json( $response );
	}

}