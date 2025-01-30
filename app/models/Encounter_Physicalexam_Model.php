<?php

class Encounter_Physicalexam_Model extends APP_Model
{	
	public function getCatExaminations()
	{	
		return $this->db->get('examinations')->result();
	}
}
?>

