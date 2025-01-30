<?php
/**
 * summary
 */
class Custom_Setting_Model extends APP_Model
{
	private $types = [
		'setting_bill_insurance_plans' => [
			'title' => 'Billing | plans name',
			'helper' => 'Billing insurance plan name'
		],
		'setting_insurance' => [
			'title' => 'Insurance Plans',
			'helper' => 'Shows on Patient Demographics - Sections Primary and Secondary Insurances'
		],
		'setting_allergie' => [
			'title' => 'Allergies',
			'helper' => 'Autosaved on Patient Demographics - Section Preventions'
		],
		'setting_language' => [
			'title' => 'Languages',
			'helper' => 'Autosaved on Patient Demographics - Section About Patient'
		],
		'setting_how_found_us' => [
			'title' => 'How You Found Us',
			'helper' => 'Autosaved on Patient Demographics - Section About Patient'
		],
		'setting_education' => [
			'title' => 'Educations',
			'helper' => 'Shows on Encounter Educations'
		],
		'setting_referral_service' => [
			'title' => 'Referral Services',
			'helper' => 'Autosaved on Encounter Referrals'
		],
		'setting_referral_specialty' => [
			'title' => 'Referral Specialty',
			'helper' => 'Autosaved on Encounter Referrals'
		],
		'setting_medication' => [
			'title' => 'Medications',
			'helper' => 'Autosaved on Encounter Medications'
		],
		'setting_request' => [
			'title' => 'Requests',
			'helper' => 'Autosaved on Encounter Request'
		],

	];

	private $currentType = '';

	/**
	 * 
	 */
	public function setType( $type = '' )
	{
		if( in_array( $type, $this->getTypes() ))
		{
			$this->currentType = $type;
			return $this;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 
	 */
	public function getElements( $settingType, $array = false  )
	{
		$this->db->select( [
			'custom_setting.id',
			'custom_setting.type',
			'custom_setting.name'
		])->from('custom_setting');

		$this->db->where(['custom_setting.type' => $settingType, "instance_id" => $_SESSION['User_DB']->instance_id ]);

		$this->db->order_by('custom_setting.name');
		
		$elements = $this->db->get()->result_array();

		if($array)
		{
			$response = [];
			foreach ($elements as $el) {
				$response[] = $el['name'];
			}
			return $response;
		}
		else
		{
			return $elements;
		}
	}

	/**
	 * 
	 */
	public function getTypes()
	{

		$types = array_keys($this->types);

		return $types;
	}

	/**
	 * 
	 */
	public function getSettings()
	{
		return $this->types;
	}

	/**
	 * 
	 */
	public function total_count( $where = array() )
	{
		$this->_filter( $where );

        $this->db->from('custom_setting');

        return $this->db->count_all_results();	
	}

	/**
	 * 
	 */
	public function get_data( $limit,  $start, $where = array() ) 
    {
    	$this->_filter( $where ); 
    	
        $this->db->select( [
			'custom_setting.id',
			'custom_setting.type',
			'custom_setting.name',
			'custom_setting.fullname'
		])
        ->where(["instance_id" => $_SESSION['User_DB']->instance_id])
		->from('custom_setting');
        
       	$this->db->limit(abs($limit),abs($start) );

        $this->db->order_by('custom_setting.type, custom_setting.name');
       	
    	return $this->db->get()->result_array();	
    }

	/**
	 * 
	 */
	private function _filter( $where )
	{
		if(isset($where['type']) && $where['type'] )
		{
			$this->db->where( ['type' => $where['type'],"instance_id" => $_SESSION['User_DB']->instance_id]);
		}
		else if($this->currentType)
		{
			$this->db->where(['type' => $this->currentType ,"instance_id" => $_SESSION['User_DB']->instance_id]);	
		}

		if(isset($where['name']) && $where['name'])
		{
			$this->db->where(['name' => $where['name'],"instance_id" => $_SESSION['User_DB']->instance_id]);
		}
	}

	/**
	 * 
	 */
	public function insertIfNew( $name, $type, $explodeStr = '' )
	{
		$name = trim((String)$name);
		
		if(!$name)
		{
			return false;
		}
		
		if($explodeStr)
		{
			$names = explode($explodeStr, $name );
			
			foreach ($names as $name) {
				$this->db->from('custom_setting');
				$this->db->where( ['name' => $name, 'type' => $type ]);

				if( !$this->db->get()->row() )
				{
					$this->name = $name;
					$this->type = $type;
					$this->instance_id = $_SESSION['User_DB']->instance_id;
					$this->save();
				}
			}
		}
		else
		{
			$this->db->from('custom_setting');
			$this->db->where( ['name' => $name, 'type' => $type ]);
			
			if( !$this->db->get()->row() )
			{
				$this->name = $name;
				$this->type = $type;
				$this->instance_id = $_SESSION['User_DB']->instance_id;

				$this->save();
			}
		}

		return true;
	}

	public function getFullname( $name )
	{
		$this->db->select( [ 
			'custom_setting.fullname'
		])->from('custom_setting');

		$this->db->where(['custom_setting.name' => $name ]);
		
		$elements = $this->db->get()->result_array();

		if(empty($elements))
		{
			return "";
		}
		else
		{

			return $elements[0]['fullname'];
		}
	}

}