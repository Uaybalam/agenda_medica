<?php
/**
* @route:patient/vaccine
*/
class Patient_Vaccine_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Vaccines_Model' => 'Patient_Vaccines_DB',
			'Patient_Tuberculosis_Model' => 'Patient_Tuberculosis_DB'
		]);
	}

	/**
	 * @route:pdf/(:num)
	 */
	function pdf( $ID )
	{	

		if( !$patient = $this->Patient_DB->get_info( $ID ) )
		{
			show_error('Patient not found',404);
		}

		$this->load->library('print/PDF_Vaccine');

		$this->pdf_vaccine->body( $patient,  [

			'vaccines_data' => $this->Patient_Vaccines_DB->get_data( $ID ),
			
			'vaccines_settings' => $this->Patient_Vaccines_DB->init_options, 
			
			'tb' => $this->Patient_Tuberculosis_DB->get( $ID )	
		]);

		$this->pdf_vaccine->output();
	}

	/**
	 * @route:autosave/(:num)
	 */
	function autosave( $ID )
	{
		$response = ['status' => 0 ];

		if( !$this->Patient_DB->existID( $ID ))
		{
			show_error('Patient not found');
		}

		$this->form_validation
			->set_rules('edit_title','Edit Title','required')
			->set_rules('title','title','xss_clean|trim|max_length[120]')
			->set_rules('number','number','required|trim|numeric')
			->set_rules('code','Code vaccine','xss_clean|trim|max_length[35]')
			->set_rules('site','Site','xss_clean|trim|max_length[20]')
			->set_rules('date_given','Date given','xss_clean|trim|max_length[10]')
			->set_rules('exp_date','Exp. Date','xss_clean|trim|max_length[20]')
			->set_rules('vis_date','VIS Date','xss_clean|trim|max_length[20]')
			->set_rules('administered_by','Administered by','xss_clean|trim|max_length[256]')
		;
		
		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{	

			if($this->input->post('intern')==='Yes')
			{
				$_POST['administered_by'] =  $this->current_user->names.' '.$this->current_user->last_name;
			}

			if($this->input->post('edit_title') ==1 )
			{
				$patient_vaccine = $this->Patient_Vaccines_DB->getRowBy([
					'patient_id' => $ID,
					'field_name' => $this->input->post('field_name'),
					'number' => $this->input->post('number')
				]);
			}
			else
			{
				$patient_vaccine = $this->Patient_Vaccines_DB->getRowBy([
					'patient_id' => $ID,
					'title' => $this->input->post('title'),
					'number' => $this->input->post('number')
				]);
			}
			
			$this->Patient_Vaccines_DB->vis_date = $this->input->post('vis_date');
			$this->Patient_Vaccines_DB->exp_date = $this->input->post('exp_date');
			
			if( $patient_vaccine )
			{
				
				$this->Patient_Vaccines_DB->site            = $this->input->post('site');
				$this->Patient_Vaccines_DB->code            = $this->input->post('code');
				$this->Patient_Vaccines_DB->subtitle        = $this->input->post('subtitle');
				$this->Patient_Vaccines_DB->intern          = $this->input->post('intern');
				$this->Patient_Vaccines_DB->administered_by = $this->input->post('administered_by');
				$this->Patient_Vaccines_DB->date_given      = $this->input->post('date_given');
				$this->Patient_Vaccines_DB->title           = $this->input->post('title');
				
				$this->Patient_Vaccines_DB->save( $patient_vaccine->id );
			}
			else
			{	
				$this->Patient_Vaccines_DB->patient_id      = $ID;
				$this->Patient_Vaccines_DB->title           = $this->input->post('title');
				$this->Patient_Vaccines_DB->date_given      = $this->input->post('date_given');
				$this->Patient_Vaccines_DB->number          = $this->input->post('number');
				$this->Patient_Vaccines_DB->site            = $this->input->post('site');
				$this->Patient_Vaccines_DB->code            = $this->input->post('code');
				$this->Patient_Vaccines_DB->subtitle        = $this->input->post('subtitle');
				$this->Patient_Vaccines_DB->intern          = $this->input->post('intern');
				$this->Patient_Vaccines_DB->administered_by = $this->input->post('administered_by');
				$this->Patient_Vaccines_DB->field_name      = $this->input->post('field_name');
				$this->Patient_Vaccines_DB->save();
			}
			
			$last_update = date('Y-m-d h:i A');
			
			if($this->input->post('edit_title') ==1 )
			{
				$patient_vaccine = $this->Patient_Vaccines_DB->getRowBy([
					'patient_id' => $ID,
					'field_name' => $this->input->post('field_name'),
					'number' => $this->input->post('number')
				]);
				$patient_vaccine->edit_title = 1;
			}
			else
			{
				$patient_vaccine = $this->Patient_Vaccines_DB->getRowBy([
					'patient_id' => $ID,
					'title' => $this->input->post('title'),
					'number' => $this->input->post('number')
				]);
				$patient_vaccine->edit_title = 0;
			}

			$response = [
				'status' => 1,
				'last_update' => $last_update,
				'vaccine_update' => $patient_vaccine
			];
		}

		$this->template->json( $response );
	}
}