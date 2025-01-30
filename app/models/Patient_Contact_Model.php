<?php
class Patient_Contact_Model extends APP_Model
{	
	private $_status = [
		0 => 'Pending',
		1 => 'Complete'
	];

	function get_pending()
	{	
		$this->db->select([
				'patient_contact.id',
				'patient_contact.patient_id',
				'patient_contact.reason',
				'patient.phone as patient_phone',
				'patient.email as patient_email',
				"CONCAT(patient.name,' ',patient.middle_name,' ',patient.last_name) as patient_full_name",
				'patient.gender as patient_gender',
				'user.nick_name as user_nick_name',
				"CONCAT(user.names,' ',user.last_name) as user_full_name",
				"DATE_FORMAT(patient_contact.create_at,'%Y-%m-%d %h:%i %p') as create_at"
			])
			->from('patient_contact')
			->join('patient', 'patient.id=patient_contact.patient_id', 'inner')
			->join('user', 'user.id=patient_contact.create_user_by', 'inner')
			->where(['patient_contact.status' => 0 ])
			->order_by('patient_contact.id ASC')
		;
		
		return $this->db->get()->result();
	}
	
	function get_by_patient( $patient_id )
	{
		$this->db->select([
				'patient_contact.id',
				'patient_contact.patient_id',
				'patient_contact.reason',
				'patient_contact.create_at',
				'user.nick_name as user_nick_name',
				"CONCAT(user.names,' ',user.last_name) as user_full_name",
			])
			->from('patient_contact')
			->join('user', 'user.id=patient_contact.create_user_by', 'inner')
			->where(['patient_contact.patient_id' => $patient_id ])
			->order_by('patient_contact.id ASC')
		;

		return $this->db->get()->result();
	}

	/**
	 * 
	 */
	function getPagination( $itemsPerPage, $page, $sort, $filters )
	{
		$config = [
			'table' => $this->tableName(),
			'orderAvailable' => [
				'created_at' => 'DATE_FORMAT(patient_contact.create_at,"%Y%m%d%H%i")'
			],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];

		$columns = [
			'patient_contact.related_file_id',
			'patient_contact.id',
			'patient_contact.patient_id',
			'patient_contact.reason',
			'patient_contact.status',
			'patient.phone as patient_phone',
			'patient.email as patient_email',
			'patient.date_of_birth as patient_dob',
			'patient.gender as patient_gender',
			'CONCAT(patient.insurance_primary_identify," - ",patient.insurance_primary_plan_name) as patient_insurance',
			"CONCAT(patient.name,' ',patient.middle_name,' ',patient.last_name) as patient_full_name",
			'user.nick_name as user_nick_name',
			"CONCAT(user.names,' ',user.last_name) as user_full_name",
			"DATE_FORMAT(patient_contact.create_at,'%Y-%m-%d %h:%i %p') as create_at",
			'patient_related_files.file_name'
		];
		
		$pagination = new \libraries\Pagination( $config, $columns );
		
		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->join('patient', 'patient.id=patient_contact.patient_id', 'inner');
			$qb->join('user', 'user.id=patient_contact.create_user_by', 'inner');
			$qb->join('patient_related_files', 'patient_related_files.id=patient_contact.id', 'left');
			
			$status = $pag->getFilter('status');

			if( $status!==FALSE && $status>=0)
			{
				$qb->where(['patient_contact.status' => $status ]);
			}

			if( $createdAt = $pag->getFilter('created_at') )
	        {
	            $qb->like( 'DATE_FORMAT(patient_contact.create_at,"%m/%d/%Y")', $createdAt );
	        }

	        if( $createdBy = $pag->getFilter('created_by') )
	        {
	            $qb->like( "user.nick_name", $createdBy );
	        }
			if( $patient = $pag->getFilter('patient') )
	        {
	        	//prevent blank spaces
	        	$fullNames = Array(
	        		"IF(patient.name != '', patient.name, NULL)",
	        		"IF(patient.middle_name != '', patient.middle_name, NULL)",
	        		"IF(patient.last_name != '', patient.last_name, NULL)",
	        	);
	            $qb->like( 'CONCAT_WS(" ",'.implode(",",$fullNames).')', preg_replace('/\s+/', ' ', $patient ) );
	            //$qb->like( "CONCAT(patient.name,' ',patient.middle_name,' ',patient.last_name)", $patient );
	        }
	        if( $reason = $pag->getFilter('reason') )
	        {
	            $qb->like( "patient_contact.reason", $reason );
	        }
	       
	        
			return $qb;
		});
	}
}