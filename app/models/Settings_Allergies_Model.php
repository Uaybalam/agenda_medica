<?php

class Settings_Allergies_Model extends APP_Model
{
	
	function get_currents()
	{	
		if( $allergies = $this->db->get( $this->table )->result() )
		{		
			$result = [];
			foreach ($allergies as $allergy) {
				$result[] = $allergy->title;
			}
			return $result;
		}
		else
		{
			return [];
		}
	}

}