<?php

class Encounter_Results_Model extends APP_Model
{

	private $_status = [
	    '0' => 'Inicial',
	    '1' => 'Nuevo',
	    '2' => 'No afiliado',
	    '3' => 'Enviar',
	    '4' => 'Resultados recibidos',
	    '5' => 'Realizado',
	    '6' => 'Rechazado',
	    '7' => 'Pendiente',
	    '8' => 'Doc. en archivo',
	];
	
	private $_fields = [
		'encounter_results.id',
		'encounter_results.patient_id',
		'encounter_results.encounter_id',
		'encounter_results.type_result',
		"DATE_FORMAT(encounter.create_at, '%m/%d/%Y') as created_at",
		'encounter_results.title',
		'encounter_results.title_document',
		'encounter_results.status',
		'encounter_results.file_name',
		'encounter_results.comments',
		"CONCAT(patient.name,' ',patient.last_name) as patient",
		"patient.date_of_birth",
		"encounter_results.recive_date",
		'encounter_results.recive_nickname',
		'encounter_results.done_date',
		'encounter_results.done_nickname',
		'encounter_results.refused_date',
		'encounter_results.refused_nickname',
		'encounter_results.refused_reason',
		'encounter_results.doc_on_file_date',
		'encounter_results.doc_on_file_nickname',
		'encounter_results.doc_on_file_reason',
	];
	
	function get_results_availible()
	{	
		return [
			'Laboratorio',
			'Procedimiento',
			'X-Ray',
			'Electrocardiograma',
			'Ultrasonido'
		];
	}

	public function get_status()
	{	
		return $this->_status;
	}

	public function get_info( $ID )
	{
		$this->db
			->select($this->_fields)
			->from( 'encounter_results' )
			->join('patient', 'patient.id=encounter_results.patient_id', 'inner')
			->join('encounter', 'encounter.id=encounter_results.encounter_id', 'inner')
			->where( ['encounter_results.id'=> $ID, 'instance_id' => $_SESSION['User_DB']->instance_id ] )
		;
		
		return $this->db->get()->row();
	}

	public function get_pending( $type = '')
	{
			
		$filter_status = ($type === 'check') ?  [4] : [2, 3 , 4];
		
		$this->db
			->select($this->_fields)
			->from( 'encounter_results' )
			->join('patient', 'patient.id=encounter_results.patient_id', 'inner')
			->join('encounter', 'encounter.id=encounter_results.encounter_id', 'inner')
			->where_in( 'encounter_results.status', $filter_status )
			->where(['instance_id' => $_SESSION['User_DB']->instance_id])
			->order_by('encounter_results.id DESC ')
		;
			
		return $this->db->get()->result();
	}

	public function exist_new( $encounter_id )
	{
		$this->db
			->select([
				'encounter_results.title',
			])
			->from( 'encounter_results' )
			->where( [
				'encounter_results.encounter_id' => $encounter_id,
				'encounter_results.status' => 1,
				'instance_id' => $_SESSION['User_DB']->instance_id
			])
		;
		
		return $this->db->get()->row();
	}

	function getPagination( $itemsPerPage, $page, $sort, $filters )
	{
		$config = [
			'table' => $this->tableName(),
			'orderAvailable' => [
	    		'created_at' => 'DATE_FORMAT(encounter.create_at,"%Y%m%d%H%i")',
	    	],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];

		$pagination = new \libraries\Pagination( $config, $this->_fields );
		//$pagination->addJoin()

		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->join('patient', 'patient.id=encounter_results.patient_id', 'inner');
			$qb->join('encounter', 'encounter.id=encounter_results.encounter_id', 'inner');
			$qb->where(['instance_id' => $_SESSION['User_DB']->instance_id]);
			$qb->where_not_in('encounter_results.status', [7] );
			
			if( $patient = $pag->getFilter('patient') )
	        {
	         	$qb->like('concat(patient.name," ",patient.last_name)', $patient );
	        }



	        if( $title = $pag->getFilter('title') )
	        {
	         	$qb->like('encounter_results.title', $title );
	        }
	        
	        if( $type = $pag->getFilter('type') )
	        {
	         	$qb->where('encounter_results.type_result', $type );
	        } else if( $typeStr = $pag->getFilter('type_str'))
	        {
	        	$qb->like('encounter_results.type_result', $typeStr);
	        }

	        $status = $pag->getFilter('status');
	        if( is_array($status) && count($status) )
	        {
	         	$qb->where_in('encounter_results.status', $status );
	        }
	        
	      	if( $created_at = $pag->getFilter('created_at') )
	        {
				$qb->like('date_format(encounter.create_at,"%m/%d/%Y")', $created_at );
	        }

			return $qb;
		});
	}
	
}