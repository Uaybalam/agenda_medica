<?php
namespace models;

/**
 * summary
 */
class Patient extends \libraries\Model
{
	private $_marital_status = [
		0 => 'Unspecified',
		1 => 'Single',
		2 => 'Married',
		3 => 'Other'
	];

	/**
	 * Get array about marital status
	 * 
	 * @return Array|String
	 */
	public static function getMaritalStatus( $key = -1 )
	{
		self::init();

		if(isset(self::$_instance->_marital_status[$key]))
			return self::$_instance->_marital_status[$key];

		return  self::$_instance->_marital_status;
	}

	/**
	 * @param Integer $itemsPerPage
	 * @param Integer $page
	 * @param Array $sort
	 * @param Array $filters
	 * 
	 * @return Array(['total_count', 'data'])
	 */
	public static function getPagination( $itemsPerPage, $page, $sort = Array(), $filters = Array() )
	{
		
		$config = [
			'table' => 'patient',
			'orderAvailable' => [
	    		'id' => 'patient.id',
	    		'names' => "names",
	    		'last_name' => 'patient.last_name',
	    		'date_of_birth' => "dob_sort",
	    		'gender' => 'gender'
	    	],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];

		$columns = [
			'patient.id',
			"CONCAT(patient.name,' ',patient.middle_name) as names",
			'patient.last_name',
			'patient.recorded_history',
			'patient.gender',
			'patient.phone',
			'patient.ethnicity',
			'patient.blood_type',
			'patient.email',
			'patient.date_of_birth',
			'patient.discount_type',
			"DATE(STR_TO_DATE(patient.date_of_birth,'%m/%d/%Y')) as dob_sort",
		];

		$pagination = new \libraries\Pagination( $config, $columns );

		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			if( $id = $pag->getFilter('id') )
	        {
	            $qb->where( [ 'patient.id' => $id ] );
	        }
	        if( $names = $pag->getFilter('names') )
	        {
	          	$qb->like('concat(patient.name," ",patient.middle_name)', $names );
	        }
	        if( $lastName = $pag->getFilter('last_name') )
	        {
	            $qb->like( [ 'patient.last_name' => $lastName ] );
	        }
	        if( $dob = $pag->getFilter('date_of_birth') )
	        {
	            $qb->like( [ 'patient.date_of_birth' => $dob ] );
	        }
	        if( $gender = $pag->getFilter('gender') )
	        {
	            $qb->where( [ 'patient.gender' => $gender ] );
	        }
	        if( $phone = $pag->getFilter('phone') )
	        {
	        	$phone = (int) filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
	            $qb->like( [ 'patient.phone' => $phone ] );
	        }
	        
			return $qb;
		});
	}

	/**
	 * @param Integer $patientID
	 * 
	 * @return Array
	 */
	public static function getHistory( $patientID )
	{
		//self::init();

		return self::retrieve( function( &$qb  ) use ($patientID){
			
			$columns = [
				'patient.id',
				'user.nick_name as user_capture',
				'patient.recorded_history',
				'patient.recorded_history_user_id',
				'patient.recorded_history_comments',
				'patient.recorded_history_current_medications',
				"patient.date_of_birth",
				"DATE_FORMAT(patient.recorded_history_at,'%m/%d/%Y %h:%i %p') as date_capture",
				"CONCAT(patient.last_name,' ',patient.name, ' ', patient.middle_name) as full_name "
			];
			
			$qb->select( $columns );
			$qb->join('user', 'user.id=patient.recorded_history_user_id', 'inner' );
			$qb->where([ 'patient.id' => $patientID ]);
			
		}, 'ROW' )->result();
	}

	/**
	 * @param Integer $patientID
	 * 
	 * @return Array
	 */
    public static function getAllergies( $patientID )
    {
    	
    	$result = self::retrieve( function( &$qb  ) use ($patientID){
			$qb->select( 'prevention_allergies' );
			$qb->where([ 'patient.id' => $patientID ]);
		}, 'ROW' )->result();

		if( count($result) )
		{
			return explode(",", $result );
		}

		return [];
    }

    /**
     * @return Array
     */
    public static function requiredValuesForChart()
    {
    	if(!$result = self::result())
    	{
    		return [];
    	}

    	$columns = [
    		'gender' => $result['gender'],
    		'interpreter_needed' => $result['interpreter_needed'],
    		'advanced_directive_offered' => $result['advanced_directive_offered'],
    		'advanced_directive_taken' => $result['advanced_directive_taken'],
    		'recorded_history' => $result['recorded_history'],
    		'how_found_us' => $result['how_found_us'],
    		'date_of_birth' => $result['date_of_birth'],
    		'phone' => $result['phone']
    	];

    	$messagePrintF = "Field: <b>%s</b>";
    	
    	foreach ($columns as $column => $value) {
    		if(!$value)
    		{
    			$requiredValues[] = sprintf("Field: <b>%s</b>", humanString($column));
    		}
    	}

    	return $requiredValues;
    }

    /**
 	 * @param Integer $patientID
 	 * 
 	 * Check all available Elements if Patient can be removed
 	 * 
 	 * @return boolean
 	 */
    public function isAvailableForRemove( $patientID )
    {
    	$patientID = (int)$patientID;

    	$model = $this->db;

    	if( $model->from('appointment')->where('patient_id',$patientID)->count_all_results() )
        	return false;

    	if( $model->from('encounter')->where('patient_id',$patientID)->count_all_results() )
        	return false;

        if( $model->from('billing')->where('patient_id',$patientID)->count_all_results() )
        	return false;

       	if( $model->from('patient_related_files')->where('patient_id',$patientID)->count_all_results() )
        	return false;
        
    	if( $model->from('encounter_referrals')->where('patient_id',$patientID)->count_all_results() )
        	return false;

        return true;

    }
}