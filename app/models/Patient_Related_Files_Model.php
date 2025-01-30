<?php

class Patient_Related_Files_Model extends APP_Model
{	

	protected $timestamp = true;

	public function get_types(){
		return [
		    'Laboratorio',
		    'Procedimientos',
		    'Radiografía',
		    'ECG',
		    'Ecografía',
		    'RX',
		    'Referencias',
		    'Varios',
		    'Historial Médico',
		    'Legal',
		    'Elegibilidad',
		];
	}
	
	public function getByPatient( $patient_id )
	{
		$this->db->select([
				'patient_related_files.encounter_id',
				'patient_related_files.id',
				'patient_related_files.type',
				'patient_related_files.title',
				'patient_related_files.create_at',
				'patient_related_files.user_id_created',
				'user.nick_name as user_created',
				'patient_related_files.file_name',
			])->from('patient_related_files')
			->join('user', 'patient_related_files.user_id_created=user.id','left')
			->join('patient', 'patient_related_files.patient_id=patient.id','left')
			->where( ['patient_related_files.patient_id' => $patient_id,'patient.instance_id' => $_SESSION['User_DB']->instance_id ] )
			->order_by('patient_related_files.create_at', 'DESC')
			->order_by('patient_related_files.id', 'DESC')
		;

		return $this->db->get()->result();
	}

	public function getInfo( $id )
	{	
		$this->db->select([
				'patient_related_files.encounter_id',
				'patient_related_files.id',
				'patient_related_files.type',
				'patient_related_files.title',
				'patient_related_files.create_at',
				'patient_related_files.user_id_created',
				'user.nick_name as user_created',
			])->from('patient_related_files')
			->join('user', 'patient_related_files.user_id_created=user.id','left')
			->join('patient', 'patient_related_files.patient_id=patient.id','left')
			->where( ['patient_related_files.id' => $id,'patient.instance_id' => $_SESSION['User_DB']->instance_id ] )
		;

		return $this->db->get()->row();
	}

	public function getPagination( $itemsPerPage, $page, $sort, $filters )
	{

		$config = [
			'table' => $this->tableName(),
			'orderAvailable' => [
	    		'created_at' => 'DATE_FORMAT(patient_related_files.create_at, "%Y%m%d%H%i%s")',
	    	],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];

		$columns = [
			'patient_related_files.encounter_id',
			'patient_related_files.id',
			'patient_related_files.patient_id',
			'patient_related_files.create_at',
			'patient_related_files.title',
			'patient_related_files.type',
			'patient_related_files.file_name',
			'CONCAT(patient.name," ", patient.middle_name," ", patient.last_name) as patient',
			'patient.date_of_birth as patient_dob',
			'user.nick_name as created_by'
		];
		
		$pagination = new \libraries\Pagination( $config, $columns );

		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->join('patient', 'patient.id=patient_related_files.patient_id', 'inner');
			$qb->join('user', 'user.id=patient_related_files.user_id_created', 'left');
			$qb->where(['patient.instance_id' => $_SESSION['User_DB']->instance_id]);
			
			$document_for_done = $pag->getFilter('document_for_done');
			if($document_for_done && $document_for_done > 0 )
			{
				$qb->where('document_for_done', $document_for_done);
			}
			if( $created_at = $pag->getFilter('created_at') )
	        {
	            $qb->like( 'DATE_FORMAT(patient_related_files.create_at, "%m/%d/%Y")', $created_at );
	        }
	        if( $title = $pag->getFilter('title') )
	        {
	            $qb->like( 'patient_related_files.title', $title );
	        }
	        if( $patient = $pag->getFilter('patient') )
	        {
	        	//prevent blank spaces
	        	$fullNames = Array(
	        		"IF(patient.name != '', patient.name, NULL)",
	        		"IF(patient.middle_name != '', patient.middle_name, NULL)",
	        		"IF(patient.last_name != '', patient.last_name, NULL)",
	        	);
	            $qb->like( 'CONCAT_WS(" ",'.implode(",",$fullNames).')', $patient );
	        }
	        if( $type = $pag->getFilter('type') )
	        {
	            $qb->like( 'patient_related_files.type', $type );
	        }
	        else if( $typeStr = $pag->getFilter('type_str'))
	        {
	        	$qb->like('patient_related_files.type', $typeStr);
	        }

	        if( $created_by = $pag->getFilter('created_by') )
	        {
	            $qb->like( 'user.nick_name', $created_by );
	        }
	        if( $encounter_id = $pag->getFilter('encounter_id') )
	        {
	            $qb->where( 'patient_related_files.encounter_id', $encounter_id );
	        }
			return $qb;
		});
	}

	public function getLastID()
	{
		$this->db->select_max('id')->from('patient_related_files');
		$row = $this->db->get()->row_array();
		if($row)
		{
			return $row['id'] + 1;
		}
		else
		{
			return 1;
		}
	}
}