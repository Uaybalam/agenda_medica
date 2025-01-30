<?php

class Encounter_Diagnosis_Model extends APP_Model
{
		
	function current_diagnostics( $patient_id , $chronic = true )
	{	

		$fields = [
			"DATE_FORMAT(encounter.signed_at,'%m/%d/%Y') as signed_at",
			'encounter_diagnosis.comment',
			'encounter_diagnosis.id',
			"IF(encounter_diagnosis.chronic,'Yes','No') as chronic",
			'encounter_diagnosis.encounter_id',
		];
		
		$this->db->select( $fields )
			->from('encounter_diagnosis')
			->join('encounter', 'encounter.id=encounter_diagnosis.encounter_id' , 'inner')
			->where([
				'encounter_diagnosis.patient_id' => $patient_id,
				'encounter.status' => 2,
			])
			->order_by('encounter.id', 'desc');

		if( $chronic )
		{
			$this->db->where(['encounter_diagnosis.chronic' => 1 ]);
		}

		return $this->db->get()->result();
	}

	function diagnosisByEncounter( $encounter_id )
	{
		$fields = [
			'encounter_diagnosis.comment',
			'encounter_diagnosis.id',
			'encounter_diagnosis.encounter_id',
		];
		
		$this->db->select( $fields )
			->from('encounter_diagnosis')
			->where([
				'encounter_diagnosis.encounter_id' => $encounter_id
			])
			->order_by('encounter_diagnosis.comment', 'ASC');

		return $this->db->get()->result();
	}

}