<?php
/**
* @route:patient/tuberculosis
*/
class Patient_Tuberculosis_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Patient_Tuberculosis_Model' => 'Patient_Tuberculosis_DB'
		]);
	}

	/**
	 * @route:{get}(:num)/pdf
	 */
	function pdf( $ID )
	{
		$this->load->library('print/PDF_Patient_Tuberculosis');
		
		if( !$patient = $this->Patient_DB->get_info( $ID ) )
		{
			show_error('Patient not found',404);
		}

		$param = [
			'tb' => $this->Patient_Tuberculosis_DB->get( $ID )
		];
		
		$this->pdf_patient_tuberculosis->body( $patient , $param);
		$this->pdf_patient_tuberculosis->output();
	}

	/**
	 * @route:{post}update/(:num)
	 */
	function update( $ID )
	{

		if (! $this->Patient_DB->existID( $ID ) )
		{
			show_error('Patient not found');
		}

		$this->form_validation
			->set_rules('type','type','xss_clean|trim|max_length[120]')
			->set_rules('result','result','xss_clean|trim|max_length[120]')
			->set_rules('size','size','xss_clean|trim|max_length[120]')
			->set_rules('date','date','xss_clean|trim|max_length[10]')
			->set_rules('induration','induration','xss_clean|trim|max_length[120]')
			->set_rules('read_by','read by','xss_clean|trim|max_length[120]')
			->set_rules('date_read','date read','xss_clean|trim|max_length[10]')
			->set_rules('risk_assessment','risk assessment',"xss_clean|trim|in_list['',Yes,No]")
			->set_rules('chest_x_ray','chest_x_ray','xss_clean|trim|max_length[120]')
			->set_rules('treatment_given','treatment given',"xss_clean|trim|in_list['',Yes,No]")
			->set_rules('treatment_start_date','Trt. start date','xss_clean|trim|max_length[10]')
			->set_rules('treatment_end_date','Trt. end date','xss_clean|trim|max_length[10]')
		;

		if($this->form_validation->run() === FALSE )
		{
			$this->template->json([
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{
			
			$this->Patient_Tuberculosis_DB->type                 = $this->input->post('type');
			$this->Patient_Tuberculosis_DB->result               = $this->input->post('result');
			$this->Patient_Tuberculosis_DB->size                 = $this->input->post('size');
			$this->Patient_Tuberculosis_DB->date                 = $this->input->post('date');
			$this->Patient_Tuberculosis_DB->induration           = $this->input->post('induration');
			$this->Patient_Tuberculosis_DB->read_by              = $this->input->post('read_by');
			$this->Patient_Tuberculosis_DB->date_read            = $this->input->post('date_read');
			$this->Patient_Tuberculosis_DB->risk_assessment      = $this->input->post('risk_assessment');
			$this->Patient_Tuberculosis_DB->chest_x_ray          = $this->input->post('chest_x_ray');
			$this->Patient_Tuberculosis_DB->treatment_given      = $this->input->post('treatment_given');
			$this->Patient_Tuberculosis_DB->treatment_start_date = $this->input->post('treatment_start_date');
			$this->Patient_Tuberculosis_DB->treatment_end_date   = $this->input->post('treatment_end_date');

			$this->Patient_Tuberculosis_DB->save( $ID );
			
			$this->template->json([
				'status' => 1,
				'message' => 'Tuberculosis updated'
			]);
		}
	}
}

function split_word($string, $string_start = '', $string_end = '' )
{	
	$arr_split = str_split($string);
	$contains  = '';
	foreach ($arr_split as $value) {
		if($value === $string_start)
			continue;
		if($value === $string_end)
			break;
		$contains.= $value;
	}

	return $contains;
}