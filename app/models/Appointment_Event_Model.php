<?php
/**
* 
*/
class Appointment_Event_Model extends APP_Model
{
	private $config_events = [
		'arrival' => [
			'name' => 'Llegada','description' => 'El paciente ha llegado'
		],
		'chartup' => [
			'name' => 'Expediente Preparado','description' => 'Expediente de paciente realizdo'
		],
		'cancel' => [
			'name' => 'Cancelacion','description' => 'Cita cancelada, no hay más eventos'
		],
		'checkout' => [
			'name' => 'Salida','description' => 'Consulta completada, no hay más eventos'
		],
		'create' => [
			'name' => 'Creada','description' => 'Nueva cita'
		],
		'encounter_signed' => [
			'name' => 'Consulta firmada','description' => 'Encuentro firmado y el paciente está esperando solicitudes'
		],
		'open_encounter' => [
			'name' => 'Consulta abierta',	'description' => 'El paciente esta con el médico'
		],
		'patient_room' => [
			'name' => 'Paciente en cuarto','description' => 'Se asigno el cuarto'
		],
		'reminder_confirm' => [
			'name' => 'Recordatorio con confirmación','description' => 'Cita confirmada por el paciente'
		],
		'reminder_not_confirm' => [
			'name' => 'Recordatorio sin Confirmación',	'description' => "Confirmación enviada"
		],
		'set_nurse' => [
			'name' => 'Con asistente médico','description' => 'Paciente en registro'
		],
		'update_code' => [
			'name' => 'Editar Codigo','description' => 'Disponible en citas status[%s], incluir notas'
		],
		'update_date' => [
			'name' => 'Editar Fecha','description' => 'Disponible en citas status[%s], incluir notas'
		],
		'update_insurance_type'=> [
			'name' => 'Editar tipo de seguro','description' => 'Actualización disponible en citas status[%s], incluir notas'
		],
		'update_notes' => [
			'name' => 'Editar notas', 'description' => 'Disponible en citas status[%s], incluir notas'
		],
		'update_visit_type' => [
			'name' => 'Editar tipo de visita','description' => 'Actualización disponible en citas status[%s], incluir notas'
		],
		'vitals_created' => [
			'name' => 'Vitales creados','description' => 'Consulta creada con vitales.'
		],
		'not_show' => [
			'name' => 'No-Mostrar', 'description' => 'Puede asignarse únicamente por el sistema (por la aplicación), sin más eventos.'
		],
		'cancel_checkout' => [
			'name' => 'Reiniciar salida', 'description' => 'Cancelar la salida del paciente.'
		],
		'created_from_communication' => [
			'name' => 'Creado a partir de contacto', 'description' => 'Cita creada despúes de comunicarse con el paciente'
		]
	];
	
	function get_data( $appointment_id , $sort = "DESC")
	{	
		$this->db->select('*')
			->from($this->table)->where([
				'appointment_id' => $appointment_id
			])
			->order_by('id '.$sort);

		return $this->db->get()->result();
		
	}

	function get_events( $data = null )
	{	

		asort($this->config_events);

		if($data)
		{
			$this->config_events['update_code']['description']           = 'Actualización disponible en citas estatus: '.implode(", ",$data['can_edit']['code'] ).".";
			$this->config_events['update_date']['description']           = 'Actualización disponible en citas estatus: '.implode(", ",$data['can_edit']['appointment_date'] ).".";
			$this->config_events['update_insurance_type']['description'] = 'Actualización disponible en citas estatus: '.implode(", ",$data['can_edit']['insurance_type'] ).".";
			$this->config_events['update_notes']['description']          = 'Actualización disponible en citas estatus: '.implode(", ",$data['can_edit']['notes'] ).".";
			$this->config_events['update_visit_type']['description']     = 'Actualización disponible en citas estatus: '.implode(", ",$data['can_edit']['visit_type'] ).".";
		}
		

		return $this->config_events;
	}
}
