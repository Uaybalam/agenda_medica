<?php

class Encounter_Addendum_Model extends APP_Model
{
	private $_fields = [
			'encounter_addendum.id',
			'encounter_addendum.encounter_id',
			'encounter_addendum.user_id',
			'encounter_addendum.notes',
			'encounter_addendum.create_at',
			"DATE_FORMAT(encounter_addendum.create_at,'%m/%d/%Y') as date",
			"DATE_FORMAT(encounter_addendum.create_at,'%h:%i %p') as time",
			'user.nick_name',
			"CONCAT(user.names,' ',user.last_name ) as user ",
		];

	function get_data( $encounter_id , $only_request = FALSE ) 
	{
			
		$this->db
			->select( $this->_fields )
			->from('encounter_addendum')
			->join('user', 'user.id=encounter_addendum.user_id' , 'inner')
			->where( ['encounter_addendum.encounter_id' => $encounter_id ] );
		
		if($only_request)
		{
			$this->db->where(['encounter_addendum.is_request' => 1 ]);
		}

		$this->db->order_by('encounter_addendum.create_at DESC');

		return $this->db->get()->result();
	}

	function get_detail( $id )
	{
		$this->db
			->select( $this->_fields )
			->from('encounter_addendum')
			->join('user', 'user.id=encounter_addendum.user_id' , 'inner')
			->where( ['encounter_addendum.id' => $id ] );

		return $this->db->get()->row();
	}
}