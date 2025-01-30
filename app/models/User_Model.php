<?php
class User_Model extends APP_Model
{
	protected $timestamp = TRUE;
	
	private $fields_basic = [
		'id',
		'nick_name',
		'email',
		'names',
		'last_name',
		'date_of_birth',
		'access_type',
		'status',
		'medic_type',
		'medic_npi',
	];
	
	public $access_type_avalible = [
		'admin'     => 'Administrador',
		'medic'     => 'Médico',
		'nurse'     => 'Asistente Médico',
		'secretary' => 'Interno',
		'billing'   => 'Facturador',
		'reception' => 'Reception',
		//'manager'	=> 'Manager'
	];

	function getProviders()
	{
		$this->db->select(Array(
			'id',
			'nick_name',
			'names',
			'medic_type',
			'medic_npi',
			'access_type',
			'digital_signature'
		))->from('user');
		$this->db->where(['medic_npi !=' => '']);

		return $this->db->get()->result_array();
	}
	
	function get_list()
	{
		$this->db->select($this->fields_basic)->from('user');

		if($_SESSION['User_DB']->access_type != "root")
		{
			$this->db->where(["instance_id" => $_SESSION['User_DB']->instance_id]);
		}

		$this->db->order_by('"status=2"','',false);

		return $this->db->get()->result();
	}

	function get_basic( $ID )
	{
		$this->db->select($this->fields_basic)
			->from('user')
			->where(['id'=> $ID]);

		return $this->db->get()->row();
	}
	
	function get_access_types()
	{	
		return array_keys($this->access_type_avalible);
	}

	function getDigitalSignature( $user_id  )
	{
		$this->db->select('digital_signature')
			->from('user')
			->where(['id'=> $user_id]);

		if($result = $this->db->get()->row_array())
		{
			return $result['digital_signature'];
		}
		else
		{
			return '';
		}
	}
}