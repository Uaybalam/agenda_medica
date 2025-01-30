<?php
class Appointment_Model extends APP_Model
{
	protected $timestamp = TRUE;
	
	private $_fields = [
		'appointment.encounter_id',
		'appointment.visit_type',
		'appointment.id',
		'appointment.patient_id',
		'appointment.type_appointment',
		'appointment.date_appointment',
		'appointment.time_arrival',
		'appointment.time_signed',
		'appointment.time_nurse',
		'appointment.time_room',
		'appointment.time_open',
		'appointment.time_done',
		'appointment.code',
		'appointment.has_insurance',
		'appointment.insurance_type',
		"DATE_FORMAT(appointment.date_confirm,'%b/%d %h:%i %p') as date_confirm",
		'appointment.confirm',
		'appointment.reminder_message',
		'0 as waiting_unix_time',
		'appointment.notes',
		'appointment.create_at',
		'appointment.reason_cancel',
		'appointment.status',
		'appointment.room',
		"DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y %h:%i %p') as full_date",
		"DATE_FORMAT(appointment.date_appointment,'%Y%m%d%H%i') as full_date_sort",
		"DATE_FORMAT(appointment.date_appointment,'%h:%i %p') as time",
		"concat(patient.name,' ',patient.last_name) as patient",
		'patient.phone',
		'patient.phone_alt',
		'patient.date_of_birth',
		'patient.language',
		'patient.phone_memo',
		'patient.phone_alt_memo',
		'0 as next_appt'
	];
	/*
	private $_status = [
		1 =>  ['name' => 'Pending Arrival',		'access' => 'secretary|reception'],
		2 =>  ['name' => 'Arrival', 			'access' => 'nurse|secretary' ],	
		3 =>  ['name' => 'With M.A.', 			'access' => 'nurse|secretary' ],
		4 =>  ['name' => 'Vitals Created', 		'access' => 'nurse|secretary' ],
		5 =>  ['name' => 'in room',     		'access' => 'medic|nurse' ],
		6 =>  ['name' => 'Waiting Checkout', 	'access' => 'medic|nurse|secretary' ],
		7 =>  ['name' => 'Done', 				'access' => 'nobody' ],
		8 =>  ['name' => 'Cancelled',			'access' => 'nobody' ],
		9 =>  ['name' => 'No Show',				'access' => 'nobody' ],
		10 => ['name' => 'Chart Up',			'access' => 'nurse|secretary' ]
	];*/

	private $_status = [
		1 =>  ['name' => 'Pendiente de Llegada',    'access' => 'secretary|reception'],
		2 =>  ['name' => 'Llegada', 			    'access' => 'nurse|secretary' ],	
		3 =>  ['name' => 'Con Asistente Médico',    'access' => 'nurse|secretary' ],
		4 =>  ['name' => 'Signos Vitales Creados',  'access' => 'nurse|secretary' ],
		5 =>  ['name' => 'En Consultorio',     	    'access' => 'medic|nurse' ],
		6 =>  ['name' => 'Esperando Pago/Checkout', 'access' => 'medic|nurse|secretary' ],
		7 =>  ['name' => 'Completado', 				'access' => 'nobody' ],
		8 =>  ['name' => 'Cancelado',				'access' => 'nobody' ],
		9 =>  ['name' => 'No se Presentó',			'access' => 'nobody' ],
		10 => ['name' => 'Expediente Preparado',	'access' => 'nurse|secretary' ]
	];
	
	/**
	|
	| By Default all these fields can edit on pending arrival [1]
	|
	**/
	private $can_edit_fields = [
		'code' => [1,2,3,4,5,6],
		'insurance_type' => [1,2,3,4,5,6],
		'visit_type' => [1,2,3,4,5,6],
		'notes' => [1,2,3],
		'appointment_date' => [1],
	];

	function get_can_edit( $current_status  )
	{

		$fields   = $this->can_edit_fields;
		$response = [];

		foreach ($fields as $key => $value) {
			$response[$key] = in_array($current_status, $value);
		}
		
		return $response;
	}

	function get_can_edit_str()
	{
		$response         = [];
		$available_status = $this->_status;
		$fields           = $this->can_edit_fields;
		
		foreach ($fields as $key => $values) {

			$response[$key] = array_map( function($status_id) use ($available_status) {
				if(!isset($available_status[$status_id]))
				{
					return '';
				}
				
				return $available_status[$status_id]['name'];

			}, $values );
		}

		return $response;
	}

	function get_visit_types()
	{	
		return [
			0 => 'Nueva',
			1 => 'Programada',
			2 => 'Seguimiento',
			3 => 'Unicamente laboratorio'
		];
	}

	function get_status( $access_user  = '')
	{
		$arr_status = [];
		foreach ($this->_status as $key => $status) {
			$sta       	  = new StdClass;
			$sta->id      = $key;
			$sta->name 	  = $status['name'];
			$sta->checked = (in_array($access_user,explode('|',$status['access'] ) ) ) ? true : false;
			$arr_status[] = $sta;
		}
		return $arr_status;
	}

	function get_status_array( $name = '' )
	{
		if($name!='')
		{
			return $this->_status[$name]['name'];
		}

		$status = [];
		foreach ($this->_status as $key => $sta) {
			$status[$key] = $sta['name'];
		}
		return $status;
	}
	
	function get_by_date( $date )
	{
			
		
		$this->db
			->select( $this->_fields )
			->from('appointment')
			->join('patient', 'patient.id=appointment.patient_id','inner')
			->where([
				"DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y') = " => $date,
				//'appointment.status !=' => 8
			])	
			->order_by("date_appointment", 'ASC');
		
		return $this->db->get()->result();
	}

	function get_by_patient( $patient_id )
	{

		array_unshift($this->_fields, "DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y') as date");
		

		$this->db
			->select( $this->_fields )
			->from('appointment')
			->join('patient', 'patient.id=appointment.patient_id','inner')
			->where([
				'patient_id' => $patient_id
			])
			->order_by("date_appointment", 'asc');
		
		return $this->db->get()->result();
	}

	function get_info( $ID )
	{

		$this->db
			->select( $this->_fields )
			->from('appointment')
			->join('patient', 'patient.id=appointment.patient_id','inner')
			->where(["appointment.id" => $ID])
		;

		return $this->db->get()->row();
	}

	function get_all_info( $patient_id )
	{ 
		$this->db
			->select( [
				'appointment.id',
				'appointment.patient_id',
				'appointment.type_appointment',
				'appointment.date_appointment',
				"appointment.time_arrival",
				"appointment.time_signed",
				'appointment.notes',
				'appointment.create_at',
				'appointment.status',
				'appointment.room',
				'appointment.code',
				'appointment.visit_type',
				"DATE_FORMAT(appointment.date_appointment,'%m/%d/%Y') as date",
				"DATE_FORMAT(appointment.date_appointment,'%h:%i %p') as time",
			] )
			->from('appointment')
			->where([
				'appointment.patient_id' => $patient_id
			])
			->order_by('appointment.date_appointment', 'desc');

		return $this->db->get()->result();
	}

	function get_pending()
	{
		$date = date('Ymd');

		$this->db
			->select( $this->_fields )
			->from('appointment')
			->join('patient', 'patient.id=appointment.patient_id','inner')
			->where([
				"DATE_FORMAT(appointment.date_appointment,'%Y%m%d') < " => $date,
				'appointment.status != ' => 7
			])
			->order_by("date_appointment", 'asc');

		return $this->db->get()->result();
	}

	function get_info_detail( $appointment_id  )
	{
		if( $appointment = $this->get( $appointment_id  ) )
		{
			$time_length_string = '';

			if($appointment->time_arrival!='')
			{
				//time_done = status done (Patient is out clinic)
				if($appointment->time_done)
				{
					$end_time = $appointment->time_done;
				}
				else
				{
					$end_time = date('h:i A');
				}

				$D1 = new DateTime('2000-01-01 '.$appointment->time_arrival );
				$D2 = new DateTime('2000-01-01 '.$end_time );

				$interval = $D1->diff( $D2 );
				

				if( $days = $interval->format('%d') )
				{
					$time_length_string = "$days Días,";
				}
				if( $hours = $interval->format('%h') )
				{
					$time_length_string.= "$hours Horas,";
				}
				
				$minutes = $interval->format('%i');
				$time_length_string.= "$minutes Minutos";
			}
				
			$appointment->time_length = $time_length_string;
		}
		
		return $appointment;	
	}
	
	function get_last_appointment( $patient_id )
	{
		$this->db
			->select('id')
			->from('appointment')
			->where([
				'status' => 3,
				'patient_id' => $patient_id,
				//'visit_type !=' => 'Lab only' 
			]);
		
		$apt = $this->db->get()->row() ;
		
		//PR( $apt );
		//PR($this->db->last_query());
		//exit;
		if( $apt )
		{	
			return $apt->id;
		}
		else
		{
			return 0;
		} 
	}


	function get_insurance_types()
	{
		
		$this->db->distinct();

		$this->db->select('insurance_type')
			->from('appointment')
			->where(['insurance_type !=' => ""])
			->order_by('insurance_type ASC');

		$insurance_types = [];
		if($results = $this->db->get()->result())
		{
			foreach ($results as  $value) {
				$insurance_types[] = $value->insurance_type;
			}
		}
		
		return $insurance_types;
	}

	function near( $patient_id )
	{
		$this->db->select('date_appointment,id')
			->from('appointment')
			->where(['patient_id ' => $patient_id])
			->limit(1)
			->order_by('date_appointment DESC');

		if($row = $this->db->get()->row() )
		{
			return (array)$row;
		}
		else
		{
			return ['date_appointment' => '','id' => 0];
		}
	}
	public function get_by_user_and_date($user_id, $date) {
		$this->db->where('create_user_by', $user_id);
		$this->db->where('date_appointment', $date);
		return $this->db->get('appointment')->result_array();
	}


}	
