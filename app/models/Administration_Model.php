<?php
class Administration_Model extends APP_Model {
	
	public function getData()
	{

		$this->db->select( $this->list_fields );
		$this->db->from($this->table);
		$this->db->where( [ 'instance_id' => $_SESSION['User_DB']->instance_id ] );
		$this->db->order_by('group, name ASC');
		$data = $this->db->get()->result();

		$groups = [];

		foreach ($data as  $config ) {
			$groups[$config->group][] = $config; 
		}
		
		return $groups;
	}
	
}