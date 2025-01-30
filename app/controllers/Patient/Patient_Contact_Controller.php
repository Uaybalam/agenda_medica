<?php
/**
* @route:patient/contact
*/
class Patient_Contact_Controller extends APP_User_Controller
{
	
	function __construct()
	{	
		parent::__construct();
		$this->load->model([
			'Patient_Contact_Model' => 'Patient_Contact_DB'
		]);
	}
	
	/**
	 * @route:insert
	 */
	function insert()
	{
		$this->form_validation
			->set_rules('patient_id','Paciente','trim|xss_clean|required|exist_data[patient.id]')
			->set_rules('reason','Razón','trim|xss_clean|required')
		;

		
		if( $this->form_validation->run() === FALSE )
		{	
			return $this->template->json([
				'status' => 0,
				'patient_id' => $this->input->post('patient_id'),
				'message' => $this->form_validation->error_string()
			] ); 
		}

		if($this->input->post('related_file_id'))
		{
			$query = $this->db;
			$query->select('id')
				->from('patient_related_files')
				->where('id', intval($this->input->post('related_file_id') ));

			$data = $query->get()->row_array();
			if(!$data)
			{
				return $this->template->json([
					'status' => 0,
					'patient_id' => $this->input->post('patient_id'),
					'message' => 'Archivo no encontrado'
				] ); 
			}
			
			$this->Patient_Contact_DB->related_file_id = $this->input->post('related_file_id');
		}
		
		$this->Patient_Contact_DB->create_user_by = $this->current_user->id;
		$this->Patient_Contact_DB->reason         = $this->input->post('reason');
		$this->Patient_Contact_DB->patient_id     = $this->input->post('patient_id');
		$this->Patient_Contact_DB->create_at      = date('Y-m-d H:i:s');

		$response = [
			'status'  => 1,
			'patient_id' => $this->input->post('patient_id'),
			'message' => 'solicitud de contacto fue agregda',
			'item'  => $this->Patient_Contact_DB->save(),
			'pending' => $this->Menu_DB->get_pending_contacts()
		];

		return $this->template->json( $response );

	}
}