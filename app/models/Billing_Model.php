<?php

class Billing_Model extends APP_Model
{
	private $type_provider = Array( 
		0 => 'Select User'
	);
	
	private $place_of_service_default = '11';

	function additionalClaimData()
	{		
		$this->db->distinct();

		$this->db->select('aditional_claim')->from('billing');
			
		$this->db->where(['aditional_claim != ','']);

		$response = [];

		if ( $data = $this->db->get()->result_array() ) {
			
			foreach ($data as $value) {
				$response[] = $value['aditional_claim'];
			}
		}
		
		return $response;	
		
	}	

	function getService( $billing_id , $number )
	{
		
		$this->db->select([
				'active',
				'number',
				'place_of_service',
				'emg',
				'procedure_cpt_hcpcs',
				'modifier_a',
				'modifier_b',
				'modifier_c',
				'modifier_d',
				'diagnosis_pointer',
				'charges',
				'days_units',
				'family_plan',
				'id_qual',
				'rendering_provider_id',
				'rendering_provider_npi',
				'date_of_service',
				'notes_unit'
			])
			->from('billing_detail')
			->where(['billing_id' => $billing_id , 'number' => $number ])
		;
		
		return $this->db->get()->row();
	}

	function get_info( $id )
	{
		$data = $this->get( $id );

		if( $data )
		{	
			$arr_status       = $this->get_status( $data->status);
			$data->status_str = isset($arr_status[$data->status]) ? $arr_status[$data->status] : 'STATUS NOT FOUND';
			
			if($data->type_provider==1)
			{
				$data->select_provider = $data->provider_name.'|'.$data->provider_npi;
			}
		}
		
		$balanceDue      = $data->total_charge - ($data->total_paid + $data->total_write_off);
		$data->total_due =  sprintf("%01.2f" , floatval($balanceDue) );

		return $data;
	}
	
	function create_default( $encounter  )
	{
		$param = [
			'encounter_id' => $encounter->id,
			'patient_id' => $encounter->patient_id,
			'insurance_title' => $encounter->insurance_title,
			'insurance_number' => $encounter->insurance_number,
			'create_at' => date('Y-m-d H:i:s'),
			'create_time_at' =>  strtotime( date('Y-m-d') )
		];

		$patient = $this->get_patient_data( $encounter->patient_id);
		
		$param['patient_address']        = $patient->address;
		$param['patient_city']           = $patient->address_city;
		$param['patient_state']          = $patient->address_state;
		$param['patient_zipcode']        = $patient->address_zipcode;
		$param['patient_telephone']      = $patient->phone;
		$param['patient_marital_status'] = $patient->marital_status;
		
		$this->db->insert('billing', $param );
		
		$billing_id = $this->db->insert_id();
		
		for( $i=1 ; $i < 7 ; $i++)
		{	
			$param_det = [
				'billing_id'       => $billing_id,
				'number'           => $i,
				'place_of_service' => ''
			];
			
			$this->db->insert('billing_detail', $param_det );
		}
	}

	function get_information( $ID )
	{
		$this->db
			->select([
				'billing.encounter_id',
				'billing.insurance_title',
				'billing.insurance_number',
				'billing.insurance_number',
				'patient.name',
				'patient.last_name',
				'patient.middle_name',
				'billing.patient_id'
			])
			->from('billing')
			->join('patient', 'patient.id=billing.patient_id' , 'inner')
			->where([
				'billing.id' => $ID
			])
		;
		
		return $this->db->get()->row();
	}


	function get_plan_types()
	{
		return [
			0 => 'Medicare #',
			1 => 'Medicaid',
			2 => 'Tricare',
			3 => 'Member',
			4 => 'Group health plan',
			5 => 'FECA BLK LUNG',
			6 => 'Other ID'
		];
	}

	function get_patient_relationship()
	{
		return [
		    0 => 'Propio',
		    1 => 'Cónyuge',
		    2 => 'Hijo',
		    3 => 'Otro'
		];
	}

	function get_status()
	{	
		return [
			0 => 'Pendiente',
			1 => 'Completa',
			2 => 'Enviado',
			3 => 'Pago parcial',
			4 => 'Refacturado',
			5 => 'Pagado',
			6 => 'Negado',
			7 => ''
		];
	}
	/*
	function filter_billing( $filter = null )
	{

		if($filter)
		{	
			$this->db->where( $filter );
		}

		$this->db
			->select([
				'billing.id',
				'billing.encounter_id',
				'billing.patient_id',
				'billing.insurance_title',
				"DATE_FORMAT(billing.create_at, '%M/%d/%Y') as date",
				'billing.status',
				'IFNULL(user.names,"") as biller',
				'billing.print'
			])
			->from('billing')
			->join('user', 'user.id=billing.user_id' , 'left')
		;

		return $this->db->get()->result();
	}
	*/

	function get_detail( $billing_id )
	{
		
		$this->db->select([
				'active',
				'number',
				'place_of_service',
				'emg',
				'procedure_cpt_hcpcs',
				'modifier_a',
				'modifier_b',
				'modifier_c',
				'modifier_d',
				'diagnosis_pointer',
				'charges',
				'paid',
				'write_off',
				'days_units',
				'family_plan',
				'id_qual',
				'rendering_provider_id',
				'rendering_provider_npi',
				'date_of_service',
				'notes_unit'
			])
			->from('billing_detail')
			->where(['billing_id' => $billing_id ])
			->order_by('number ASC');
			
		return $this->db->get()->result();
	}

	function save_details_status( $details , $billing_id )
	{
		
		foreach ($details as $pos => $det ) {
			$params = [
				'active'              => $det['active'],
				'procedure_cpt_hcpcs' => $det['procedure_cpt_hcpcs'],
				'paid'                => $det['paid'],
				'write_off'           => $det['write_off'],
			];
			
			$this->db
				->where( [
					'billing_id' => $billing_id, 
					'number' => $det['number']
				])
            	->update('billing_detail', $params );

		}
		
		return true;
	}

	function save_details( $details , $billing_id )
	{
		
		foreach ($details as $pos => $det ) {
			
			$placeOfService = isset( $det['place_of_service'] ) ? $det['place_of_service'] :  $this->place_of_service_default;
			
			$params = [
				'billing_id'             => $billing_id,
				'place_of_service'       => $placeOfService,
				'active'                 => $det['active'],
				'emg'                    => $det['emg'],
				'procedure_cpt_hcpcs'    => $det['procedure_cpt_hcpcs'],
				'modifier_a'             => $det['modifier_a'],
				'modifier_b'             => $det['modifier_b'],
				'modifier_c'             => $det['modifier_c'],
				'modifier_d'             => $det['modifier_d'],
				'diagnosis_pointer'      => $det['diagnosis_pointer'],
				'charges'                => floatval($det['charges']),
				'days_units'             => $det['days_units'],
				'family_plan'            => $det['family_plan'],
				'id_qual'                => $det['id_qual'],
				'rendering_provider_id'  => $det['rendering_provider_id'],
				'rendering_provider_npi' => $det['rendering_provider_npi'],
				'date_of_service'        => $det['date_of_service'],
				'notes_unit'             => $det['notes_unit']
			];
			
			$this->db
				->where( [
					'billing_id' => $billing_id, 
					'number' => $det['number']
				])
            	->update('billing_detail', $params );

		}
		
		return true;
	}

	private function get_time( $date )
	{
		$e =  explode("/", $date)  ;
		
		if( count($e)!=3 )
		{
			return 0;
		}
		$y = $e[2];
		$m = $e[0];
		$d = $e[1];
		return strtotime("$y-$m-$d");
	}

	private function get_patient_data( $patient_id )
	{
		$patientArgs = [
			'name',
			'last_name',
			'middle_name',
			'address',
			'address_zipcode',
			'address_state',
			'address_city',
			'phone',
			'marital_status',
			'insurance_primary_plan_name',
			'insurance_primary_identify'
		];
		
		$this->db->select($patientArgs)
			->from('patient')
			->where(['id' => $patient_id]);

		$patient = $this->db->get()->row();

		$patient->phone = str_replace(' ', '', $patient->phone);

		return $patient;
	}

	public function getPagination( $itemsPerPage, $page, $sort = Array(), $filters = Array() )
	{
		$config = [
			'table' => $this->tableName(),
			'orderAvailable' => [
	    		'encounter_id' => 'billing.encounter_id',
				'insurance'    => 'billing.insurance_title',
				'date'         => 'DATE_FORMAT(billing.create_at,"%Y%m%d")',
				'status'       => 'billing.status',
				'biller'       => 'biller'
	    	],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];
		
		$columns = [
			'billing.id',
			'billing.encounter_id',
			'billing.patient_id',
			'billing.insurance_title',
			"billing.create_at as date_bill",
			'billing.status',
			'IFNULL(user.names,"") as biller',
			'billing.print',
			'billing.total_charge',
			'billing.total_paid',
			'billing.total_write_off',
			'billing.total_due',
			'billing.comments',
			'billing.print_date',
			'billing.print_user_nickname',
		];

		$pagination = new \libraries\Pagination( $config, $columns );
		
		$dataResult= $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->join('user', 'user.id=billing.user_id' , 'left');

			if( $encounterID = $pag->getFilter('encounter_id') )
       	 	{	
	            $qb->where( [ 'billing.encounter_id' => $encounterID ] );
	        }
	        if( $insurance = $pag->getFilter('insurance') )
	        {
	          	$qb->like('billing.insurance_title', $insurance  );
	        }
	        if( $start = $pag->getFilter('start_date') )
	        {
	            $qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') >=" => $start ] );
	        }
	       	if( $end = $pag->getFilter('end_date') )
	        {
	            $qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') <= " => $end ] );
	        }
	        if( $printDate = $pag->getFilter('print_date') )
	        {
	            $qb->where( [ "DATE_FORMAT(billing.print_date, '%m/%d/%Y') = " => $printDate ] );
	        }

	        $status = $pag->getFilter('status');
	        $currentStatus = $this->get_status();
	        if( isset($currentStatus[$status]))
	        {
	        	$qb->where( [ 'billing.status' => $status  ] );
	        }
	        if( $biller = $pag->getFilter('biller'))
	        {
	            $qb->like( [ 'IFNULL(user.names,"")' => $biller ] );
	        }

			return $qb;
		});


		foreach ($dataResult['result_data'] as &$bill) {

			$balanceDue = $bill['total_charge'] - ($bill['total_paid'] + $bill['total_write_off']);
			$bill['total_due'] =  sprintf("%01.2f" , floatval($balanceDue) );
		}

		return $dataResult;
	}
	
	public function refreshPatientData( $billing )
	{
		if( !in_array($billing->status, [ 0, 1, 2, 3]))
			return $billing;
		
		$patient = $this->get_patient_data( $billing->patient_id );
		
		$param = Array();
		if(!$billing->patient_address)
			$billing->patient_address = $param['patient_address'] = $patient->address;
		if(!$billing->patient_city)
			$billing->patient_city = $param['patient_city'] = $patient->address_city;
		if(!$billing->patient_state)
			$billing->patient_state = $param['patient_state'] = $patient->address_state;
		if(!$billing->patient_zipcode)
			$billing->patient_zipcode = $param['patient_zipcode'] = $patient->address_zipcode;
		if(!$billing->patient_telephone)
			$billing->patient_telephone = $param['patient_telephone'] = $patient->phone;
		if(!$billing->patient_marital_status)
			$billing->patient_marital_status = $param['patient_marital_status'] = $patient->marital_status;
		if(!$billing->insurance_title && $billing->insurance_title!=='CASH')
			$billing->insurance_title = $param['insurance_title'] = $patient->insurance_primary_plan_name;
		if(!$billing->insurance_number && $billing->insurance_title!=='CASH' )
			$billing->insurance_number = $param['insurance_number'] = $patient->insurance_primary_identify;
		
		if( count($param) > 0 )
		{
			$this->db->where( [ 'id' => $billing->id ])
				->update('billing', $param );
		}

		return $billing;
	}
}