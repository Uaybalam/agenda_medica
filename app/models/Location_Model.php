<?php

class Location_Model extends APP_Model
{	
	function get_info( $id )
	{
		$this->db
			->from('location')
			->where(['id' => $id]);

		if( $row = $this->db->get()->row() )
		{	
			return $row;
		}
		else
		{
			$row = new StdClass;
			$row->city        = '';
			$row->state_short = '';
			$row->county      = '';
			$row->state_full  = '';

			return $row;
		}
	}	
}