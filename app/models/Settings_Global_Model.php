<?php
/**
* 
*/
class Settings_Global_Model extends APP_Model
{
	function get_data()
	{	
		$this->db->select([
				'id',
				'title',
				'value',
				'value as value_initial',
			])
			->from($this->table);
		
		return $this->db->get()->result();
	}
}