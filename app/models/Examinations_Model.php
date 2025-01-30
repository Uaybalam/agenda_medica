<?php

class Examinations_Model extends APP_Model
{
	function get_data( $instance_id )
	{
		$this->db
			->select([
				'id',
				'title',
				'content',
				'title as title_initial',
				'content as content_initial'
			])
			->from($this->table)
			->where(['instance_id' => $instance_id ])
			->order_by('title');

		return $this->db->get()->result();
	}
}