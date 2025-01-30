<?php
/**
*	@comments: get history encounter by function selected and patient_id
*/
class Encounter_Compare_Model extends CI_Model
{	
	function blood_pressure( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.blood_pressure_sys',
				'encounter.blood_pressure_dia',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter.blood_pressure_sys > ' => 0,
				'encounter.blood_pressure_dia > ' => 0,
			])
			->order_by('encounter.id ASC')
			->limit(8)
		;

		return $this->db->get()->result();
	}

	function vitals_basic( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.chief_complaint',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;

		return $this->db->get()->result();
	}


	function vitals_heart( $patient_id )
	{	
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.heart_pulse',
				'encounter.heart_respiratory',
				'encounter.heart_temperature',
				'encounter.heart_hemoglobin',
				'encounter.heart_hematocrit',
				'encounter.heart_head_circ',
				'encounter.heart_last_menstrual_period',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter.heart_pulse > ' => 0,
				'encounter.heart_respiratory > ' => 0,
				'encounter.heart_temperature > ' => 0,
			])
			->order_by('encounter.id ASC')
			->limit(8)
		;

		return $this->db->get()->result();
	}

	function vitals_physical( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.physical_birth_weight',
				'encounter.physical_weight',
				'encounter.physical_height',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter.physical_weight > ' => 0,
				'encounter.physical_height > ' => 0
			])
			->order_by('encounter.id ASC')
			->limit(8)
		;

		return $this->db->get()->result();
	}

	function vitals_eyes( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.eye_withglasses_left',
				'encounter.eye_withglasses_right',
				'encounter.eye_withglasses_both',
				'encounter.eye_withoutglasses_left',
				'encounter.eye_withoutglasses_right',
				'encounter.eye_withoutglasses_both',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;

		$or_where = Array(
			"encounter.eye_withglasses_left !='' ",
			"encounter.eye_withglasses_right !='' ",
			"encounter.eye_withglasses_both !='' ",
			"encounter.eye_withoutglasses_left !='' ",
			"encounter.eye_withoutglasses_right !='' ",
			"encounter.eye_withoutglasses_both !='' ",
		);
		
		$this->db->where('('.implode(" OR ", $or_where ).')');
		
		return $this->db->get()->result();
		
	}

	function vitals_audio( $patient_id )
	{	
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.audio_left_1000',
				'encounter.audio_left_2000',
				'encounter.audio_left_3000',
				'encounter.audio_left_4000',
				'encounter.audio_right_1000',
				'encounter.audio_right_2000',
				'encounter.audio_right_3000',
				'encounter.audio_right_4000',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;

		$or_where = Array(
			'encounter.audio_left_1000 > 0 ',
			'encounter.audio_left_2000 > 0 ',
			'encounter.audio_left_3000 > 0 ',
			'encounter.audio_left_4000 > 0 ',
			'encounter.audio_right_1000 > 0 ',
			'encounter.audio_right_2000 > 0 ',
			'encounter.audio_right_3000 > 0 ',
			'encounter.audio_right_4000 > 0 ',
		);
		
		$this->db->where('('.implode(" OR ", $or_where ).')');

		return $this->db->get()->result();
	}


	function vitals_urinalysis( $patient_id )
	{	
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.urinalysis_color',
				'encounter.urinalysis_specific_gravity',
				'encounter.urinalysis_ph',
				'encounter.urinalysis_protein',
				'encounter.urinalysis_glucose',
				'encounter.urinalysis_ketones',
				'encounter.urinalysis_bilirubim',
				'encounter.urinalysis_blood',
				'encounter.urinalysis_leuktocytes',
				'encounter.urinalysis_nitrite',
				'encounter.urinalysis_human_chorionic_gonadotropin',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;

		$or_where = Array(
			"encounter.urinalysis_color !='' ",
			'encounter.urinalysis_specific_gravity >0',
			"encounter.urinalysis_ph !=''",
			"encounter.urinalysis_protein !='' ",
			"encounter.urinalysis_glucose !='' ",
			"encounter.urinalysis_ketones !='' ",
			"encounter.urinalysis_bilirubim !='' ",
			"encounter.urinalysis_blood !='' ",
			"encounter.urinalysis_leuktocytes !='' ",
			"encounter.urinalysis_nitrite !='' ",
			"encounter.urinalysis_human_chorionic_gonadotropin !='' ",
		);
		
		$this->db->where('('.implode(" OR ", $or_where ).')');

		return $this->db->get()->result();
	}

	function illness_history( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.present_illness_history',
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id,
			])
			->order_by('encounter.id DESC')
		;

		$this->db->where("encounter.present_illness_history !='' ");

		return $this->db->get()->result();
	}

	function physical_exam( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter_physicalexam.title',
				'encounter_physicalexam.content',
			])
			->from('encounter_physicalexam')
			->join('encounter', 'encounter.id=encounter_physicalexam.encounter_id', 'inner')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter_physicalexam.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;
		
		return $this->db->get()->result();
	}

	function diagnosis( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter_diagnosis.chronic',
				'encounter_diagnosis.comment',
			])
			->from('encounter_diagnosis')
			->join('encounter', 'encounter.id=encounter_diagnosis.encounter_id', 'inner')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter_diagnosis.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;
		
		return $this->db->get()->result();
	}

	function procedure( $patient_id )
	{	
		$or_where = Array(
			"encounter.procedure_text !='' ",
			"encounter.procedure_patient_education !='' ",
		);

		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter.procedure_text',
				'encounter.procedure_patient_education'
			])
			->from('encounter')
			->where([
				'encounter.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;

		$this->db->where('('.implode(" OR ", $or_where ).')');
		
		return $this->db->get()->result();
	}

	function medications( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at',
				'encounter_medication.title',
				'encounter_medication.chronic',
				'encounter_medication.dose',
				'encounter_medication.amount',
				'encounter_medication.directions',
			])
			->from('encounter_medication')
			->join('encounter', 'encounter.id=encounter_medication.encounter_id', 'inner')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter_medication.patient_id' => $patient_id
			])
			->order_by('encounter.create_at DESC')
		;
		
		return $this->db->get()->result();
	}

	function results( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter_results.type_result',
				'encounter_results.title',
				'encounter_results.comments',
				'encounter_results.file_name',
				'encounter_results.id as encounter_result_id'
			])
			->from('encounter_results')
			->join('encounter', 'encounter.id=encounter_results.encounter_id', 'inner')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter_results.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;
		
		return $this->db->get()->result();
	}
	
	function referrals( $patient_id )
	{
		$this->db->select([
				'encounter.id',
				'encounter.create_at as date',
				'encounter_referrals.speciality',
				'encounter_referrals.service',
				'encounter_referrals.reason'
			])
			->from('encounter_referrals')
			->join('encounter', 'encounter.id=encounter_referrals.encounter_id', 'inner')
			->where([
				'encounter.patient_id' => $patient_id,
				'encounter_referrals.patient_id' => $patient_id
			])
			->order_by('encounter.id DESC')
		;
		
		return $this->db->get()->result();
	}
}