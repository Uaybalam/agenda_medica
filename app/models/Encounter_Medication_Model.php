<?php

class Encounter_Medication_Model extends APP_Model
{
	public function CatMedication( $title )
	{
		$exist = $this->db
					->from('medications')
					->where(['title' => $title ])
					->count_all_results();
		if( (int)$exist === 0 )
		{	
			return $this->db->insert('settings_medications', ['title' => $title ] );
		}
		else
		{
			return false;
		}
	}

	function current_medications( $patient_id, $chronic = true )
	{
		$this->db->select([
				'encounter_medication.id',
				'encounter_medication.title',
				'encounter_medication.dose',
				'encounter_medication.amount',
				'encounter_medication.directions',
				'encounter_medication.chronic',
				"DATE_FORMAT(encounter.signed_at, '%m/%d/%Y') as date",
			])
			->from('encounter_medication')
			->join('encounter','encounter.id=encounter_medication.encounter_id', 'inner')
			->where([
				'encounter_medication.patient_id' => $patient_id,
				'encounter_medication.status' => 1
			])
			->order_by('encounter.signed_at', 'desc');
		;
		
		if ( $chronic )
		{
			$this->db->where(['encounter_medication.chronic' => 'Yes']);
		}

		return $this->db->get()->result();
	}
}