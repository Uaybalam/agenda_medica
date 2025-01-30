<?php
/**
* @route:preventions
*/
class Patient_Prevention_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Custom_Setting_Model' => 'Custom_Setting_Model'
		]);
	}
	
	/**
	 * @route:update/(:num)
	 */
	function update( $patient_id )
	{
		$response['status'] = 0;

		$this->form_validation
			->set_rules('allergies', 'Allergies','trim|xss_clean')
			->set_rules('alcohol', 'Alcohol','trim|xss_clean|max_length[100]')
			->set_rules('drugs', 'Drugs','trim|xss_clean|max_length[100]')
			->set_rules('tobacco', 'Tobacco','trim|xss_clean|max_length[100]')
		;

		if( !($patient = $this->Patient_DB->get( $patient_id )) )
		{
			$response['message'] = 'Patient not found';
		}
		else if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{
			
			$this->Custom_Setting_Model->insertIfNew($this->input->post('allergies'), 'setting_allergie',',');
			
			//
			$this->Patient_DB->prevention_allergies = $this->input->post('allergies');
			$this->Patient_DB->prevention_alcohol   = $this->input->post('alcohol');
			$this->Patient_DB->prevention_drugs     = $this->input->post('drugs');
			$this->Patient_DB->prevention_tobacco   = $this->input->post('tobacco');
			$this->Patient_DB->update( ['id' => $patient_id ]);
			
			$response = [
				'status' => 1,
				'patient' => $this->Patient_DB->get_info($patient_id ),
				'message' => 'Preventions updated'
			];
		}

		$this->template->json( $response );
	}
}