<?php
/**
* @route:patient/appointments
*/
class Patient_Appointments_Controller extends APP_User_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Appointment_Model' => 'Appointment_DB'
		]);	
	}

	/**
	 * @route:{get}(:num)
	 */
	function index( $ID )
	{	
		$this->template
			->set_title('Patient appointments')
			->body([
				'ng-app' => 'app_patient_appointments',
				'ng-controller' => 'ctrl_patient_appointments',
				'ng-init' => 'initialize('.$ID.')'
			])	
			->js('patient/patient.appointments.records')
			->render('patient/view.panel.patient.appointments.records');
	}

	/**
	 * @route:{get}(:num)/initialize
	 */
	function initialize( $ID )
	{		
		
		$patient = $this->db->select([
			'patient.id',
			"CONCAT(patient.name,' ',patient.middle_name,' ',patient.last_name) full_name",
			'patient.date_of_birth'
		])->where(['id' => $ID ])->get('patient')->row();

		$data = [
			'appointments' => $this->Appointment_DB->get_by_patient( $ID ),
			'array_status' => $this->Appointment_DB->get_status_array(),
			'patient' => $patient
		];

		$this->template->json( $data );
	}

	/**
	 * @route:{post}getAppointments
	 */
	function getAppointments()
	{		
		$data = $this->db->select(['date_appointment'])->from('appointment')
						 ->where(["patient_id" => $this->input->post('patient_id'),
									 "DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y') = " => $this->input->post('date'),])
						 ->get()->result();

		$this->template->json(["appointments" => $data]);
	}
}