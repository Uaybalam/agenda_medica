<?php
class Patient_Communication_Model extends APP_Model
{

	private $type_communications = [
		0 => [
			'title' => 'Centro medico llamado',
			'class' => 'fa fa-arrow-left text-success',
			'subclass' => 'fa-phone'
		],
		1 => [
			'title' => 'Paciente llamado',
			'class' => 'fa fa-arrow-right text-warning',
			'subclass' => 'fa-phone'
		],
		2 => [
			'title' => 'Comentarios del expediente',
			'class' => 'fa fa-comments-o text-info',
			'subclass' => ''
		]
	];
	
	public function get_available_types()
	{
		return $this->type_communications;
	}

	function get_history_by_appointment( $appointment_id  )
	{	
		$this->db->select([
				'patient_communication.id',
				"patient_communication.create_at",
				'patient_communication.notes',
				'patient_communication.type',
				"patient_communication.created_by_user as user_full_name ",
				'patient_contact.reason as contact_reason'
			])
			->from('patient_communication')
			->join('patient_contact', 'patient_contact.id=patient_communication.patient_contact_id', 'left')
			->where([
				'patient_communication.appointment_id' => $appointment_id
			])
			->order_by('patient_communication.id DESC')
		;
		
		return $this->db->get()->result();
		
	}

	function get_history_by_patient( $patient_id , $from = '', $to = '')
	{	
		$this->db->select([
				'patient_communication.id',
				'patient_communication.create_at',
				'patient_communication.notes',
				'patient_communication.type',
				"patient_communication.created_by_user as user_full_name ",
				'patient_contact.reason as contact_reason'
			])
			->from('patient_communication')
			->join('patient_contact', 'patient_contact.id=patient_communication.patient_contact_id', 'left')
			->where([
				'patient_communication.patient_id' => $patient_id
			])
			->order_by('patient_communication.id DESC')
		;

		if( $from!='')
		{
			$this->db->where(["DATE_FORMAT(patient_communication.create_at,'%Y%m%d')>= " => $from]);
		}
		if( $to!='')
		{
			$this->db->where(["DATE_FORMAT(patient_communication.create_at,'%Y%m%d')<=" => $to ]);
		}
		
		return $this->db->get()->result();
		
	}

	function get_by_patient( $patient_id )
	{	
		$this->db->select([
				'patient_communication.id',
				'patient_communication.create_at',
				'patient_communication.notes',
				'patient_communication.created_by_user as user_full_name ',
				'patient_communication.type',
			])
			->from('patient_communication')
			->where([
				'patient_communication.patient_id' => $patient_id
			])
			->order_by('patient_communication.id DESC')
		;
		
		return $this->db->get()->result();
	}
	
	function get_by_id( $ID )
	{	
		$this->db->select([
				'patient_communication.id',
				'patient_communication.type',
				'patient_communication.create_at',
				'patient_communication.notes',
				'patient_communication.created_by_user as user_full_name '
			])
			->from('patient_communication')
			->where([
				'patient_communication.id' => $ID
			])
		;

		return $this->db->get()->row();
	}
}