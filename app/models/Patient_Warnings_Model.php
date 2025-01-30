<?php
class Patient_Warnings_Model extends APP_Model
{	
	public $timestamp = TRUE;

	function data_status()
	{
		return [
		    0 => 'Notificación normal',      //#se puede eliminar - editar
		    1 => 'Eliminado',               //#notificación finalizada
		    2 => 'Enviar solicitud',        //#se puede editar (médico, administrador)
		    3 => 'Solicitar respuesta'      //#se puede eliminar - editar (médico, administrador)
		];
	}

	function get_pending( $current_user )
	{

		$status = (in_array($current_user->access_type, ['root','admin','medic'])) ? 2 : 3;
		
		$this->db->select([
				'patient_warnings.id',
				'patient_warnings.patient_id',
				'patient_warnings.description',
				'patient_warnings.status',
				'patient_warnings.user_create',
				'patient_warnings.create_at',
				'patient_warnings.user_reply',
				"CONCAT(patient.last_name,' ',patient.name, ' ', patient.middle_name) as patient ",
				'patient_warnings.request_reply',
			])
			->from('patient_warnings')
			->join('patient','patient.id=patient_warnings.patient_id','inner')
			->where([
				'patient_warnings.status' => $status
			])
		;
			
		return $this->db->get()->result();
	}

	function get_data_patient( $patient_id )
	{	
		$this->db->select([
				'patient_warnings.id',
				'patient_warnings.patient_id',
				'patient_warnings.description',
				'patient_warnings.description_reply',
				'patient_warnings.status',
				'patient_warnings.user_create',
				'patient_warnings.create_at',
				'patient_warnings.request_reply',
				'patient_warnings.update_at',
				'patient_warnings.user_reply',
			])
			->from('patient_warnings')
			->where([
				'patient_warnings.patient_id' => $patient_id,
				'patient_warnings.status != ' => 1
			])
		;
		
		return $this->db->get()->result();
	}
}