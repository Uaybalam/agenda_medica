<?php 
class Patient_Model extends APP_Model
{
	protected $timestamp = TRUE;

	private $fields = [
			'patient.id',
			"CONCAT(patient.name,' ',patient.middle_name) as names",
			'patient.recorded_history',
			'patient.name',
			'patient.middle_name',
			'patient.last_name',
			'patient.address',
			'patient.address_zipcode',
			'patient.address_city',
			'patient.address_state',
			'patient.gender',
			'patient.phone',
			'patient.ethnicity',
			'patient.blood_type',
			'patient.email',
			'patient.date_of_birth',
			'patient.phone_memo',
			'patient.phone_alt',
			'patient.phone_alt_memo',
			'patient.how_found_us',
			'patient.interpreter_needed',
			'patient.advanced_directive_offered',
			'patient.advanced_directive_taken',
			'patient.language',
			'patient.discount_type',
			'patient.marital_status',
			//insurance
			'patient.insurance_primary_status',
			'patient.insurance_primary_plan_name',
			'patient.insurance_primary_identify',
			'patient.insurance_primary_notes',
			'patient.insurance_secondary_plan_name',
			'patient.insurance_secondary_identify',
			'patient.insurance_secondary_notes',
			'patient.insurance_secondary_status',
			//membership /*
			'patient.membership_name',
			'patient.membership_date',
			'patient.membership_type',
			'patient.membership_notes',
			//responsible
			'patient.responsible_name',
			'patient.responsible_middle_name',
			'patient.responsible_last_name',
			'patient.responsible_gender',
			'patient.responsible_phone',
			'patient.responsible_phone_alt',
			'patient.responsible_address',
			'patient.responsible_address_zipcode',
			'patient.responsible_address_city',
			'patient.responsible_address_state',
			'patient.responsible_relationship',
			'patient.responsible_self',
			//emergency
			'patient.emergency_name',
			'patient.emergency_middle_name',
			'patient.emergency_last_name',
			'patient.emergency_gender',
			'patient.emergency_phone',
			'patient.emergency_phone_alt',
			'patient.emergency_address',
			'patient.emergency_address_zipcode',
			'patient.emergency_address_city',
			'patient.emergency_address_state',
			'patient.emergency_relationship',
			
			'patient.recorded_history',
			'patient.balance_due',
			//preventions
			'patient.prevention_allergies',
			'patient.prevention_alcohol',
			'patient.prevention_drugs',
			'patient.prevention_tobacco',
	];

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
	function getMaritalStatus( $key = -1 )
	{
		if(isset($this->_marital_status[$key]))
			return $this->_marital_status[$key];

		return  $this->_marital_status;
	}

	function getPagination( $itemsPerPage, $page, $sort, $filters )
	{
		$config = [
			'table' => $this->tableName(),
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
			'patient.insurance_primary_plan_name',
			'patient.insurance_secondary_plan_name',
			"DATE(STR_TO_DATE(patient.date_of_birth,'%m/%d/%Y')) as dob_sort",
		];

		$pagination = new \libraries\Pagination( $config, $columns );

		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->where( [ 'patient.instance_id' => $_SESSION['User_DB']->instance_id ] );

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
	        if( $insurance = $pag->getFilter('insurance') )
	        {
	        	 $qb->like( [ 'patient.insurance_primary_plan_name' => $insurance ] );
	        }
			return $qb;
		});
	}
	
	function get_info( $patient_id )
	{
		$this->db
			->select( $this->fields )
			->from('patient')
			->where([ 'patient.id' => $patient_id ] )
		;
		
		if( $patient = $this->db->get()->row() ) 
		{	
			$my_insurances = [];
			
			if($patient->insurance_primary_status)
			{
				$insurancePrimary = $patient->insurance_primary_plan_name  . "|". $patient->insurance_primary_identify;

				$my_insurances[] = $insurancePrimary;
				
			}

			if($patient->insurance_secondary_status)
			{
				$insuranceSecondary = $patient->insurance_secondary_plan_name  . "|". $patient->insurance_secondary_identify;

				$my_insurances[] = $insuranceSecondary;
				
			}

			$patient->insurance_string = implode(",", $my_insurances);
			
			$patient->my_insurances = $my_insurances;
			
			return $patient;
		}
		else
		{
			return null;
		}
	}

	function create_basic_patient( $data_patient )
	{
		
		//create row patient
		$this->name                        = mb_strtoupper($data_patient['name'],'UTF-8');
		$this->middle_name                 = isset($data_patient['middle_name']) ? mb_strtoupper($data_patient['middle_name'],'UTF-8') : ''; 
		$this->last_name                   = mb_strtoupper($data_patient['last_name'],'UTF-8');
		$this->phone                       = isset($data_patient['phone']) ? $data_patient['phone'] : '';
		$this->gender                      = isset($data_patient['gender']) ? $data_patient['gender'] : '';
		$this->email					   = isset($data_patient['email']) ? $data_patient['email'] : '';
		$this->how_found_us                = isset($data_patient['how_found_us']) ? $data_patient['how_found_us'] : '';
		$this->interpreter_needed          = isset($data_patient['interpreter_needed']) ? $data_patient['interpreter_needed'] : '';
		$this->advanced_directive_offered  = isset($data_patient['advanced_directive_offered']) ? $data_patient['advanced_directive_offered'] : '';
		$this->advanced_directive_taken    = isset($data_patient['advanced_directive_taken']) ? $data_patient['advanced_directive_taken'] : '';
		
		$this->insurance_primary_plan_name = isset($data_patient['insurance_primary_plan_name']) ? $data_patient['insurance_primary_plan_name'] : '';
		$this->insurance_primary_identify  = isset($data_patient['insurance_primary_identify']) ? $data_patient['insurance_primary_identify'] : '';

		$this->instance_id   = $_SESSION['User_DB'] ? $_SESSION['User_DB']->instance_id : $data_patient['instance_id'];
		$this->status        = 0; 
		$this->date_of_birth = $data_patient['date_of_birth'];
		//$this->date_of_birth_time = strtotime($data_patient['date_of_birth']);
		$patient_id =  $this->save();
		
		//create folder patient
		$folder_patient = FCPATH . '../private/uploads/patients/patient_' .$patient_id;
		if( !file_exists( $folder_patient ) )
		{	
			mkdir( $folder_patient );
		} 

		//create history active patient
		$this->db->insert('patient_history_active', ['patient_id' => $patient_id, 'surgeries' => '' ] );
		//create tuberculosis
		$this->db->insert('patient_tuberculosis', ['patient_id' => $patient_id ] );
		
		return $patient_id;
	}

	function get_info_history( $patient_id )
	{

		$fields = [
			'patient.id',
			'user.nick_name as user_capture',
			'patient.recorded_history',
			'patient.recorded_history_user_id',
			'patient.recorded_history_comments',
			'patient.recorded_history_surgeries',
			'patient.recorded_history_current_medications',
			"patient.date_of_birth",
			"DATE_FORMAT(patient.recorded_history_at,'%m/%d/%Y %h:%i %p') as date_capture",
			"CONCAT(patient.last_name,' ',patient.name, ' ', patient.middle_name) as full_name "
		];

		$this->db
			->select( $fields  )
			->from('patient')
			->join('user', 'user.id=patient.recorded_history_user_id', 'inner' )
			->where('patient.id', $patient_id )
		;
		if( $patient = $this->db->get()->row() ) 
		{	
			return $patient;
		}
		else
		{
			return null;
		}
	}

	function get_open_balance( $patient_id )
	{
		
		$SQL = " SELECT ( SUM(total) - SUM(paid)  ) AS balance_due ".
			" FROM encounter_invoice WHERE patient_id = {$patient_id} and status=1 ";

		$query = $this->db->query( $SQL );
		if($data = $query->row())
		{
			return (is_null( $data->balance_due) ) ? 0 :  $data->balance_due;
		}
		
		return 0;
	}

    function getAllergies( $patient_id )
    {
    	
    	$this->db->select('prevention_allergies')
    		->from('patient')
    		->where(['id' => $patient_id ]);

    	if($data = $this->db->get()->row() )
    	{
    		return explode(",", $data->prevention_allergies);
    	}
    	else
    	{
    		return [];
    	}
    }

    function requiredValues( $patient_id, $redirect = TRUE )
    {
    	$this->db->select([
    		'gender',
    		'interpreter_needed',
    		//'advanced_directive_offered',
    		//'advanced_directive_taken',
    		'recorded_history',
    		'how_found_us',
    		'date_of_birth',
    		'address',
    		'address_zipcode',
    		'address_city',
    		'address_state',
    		
    	])->from('patient')->where(['id' => $patient_id ]);

    	if( !$patientInfo = $this->db->get()->row() )
    	{
    		show_error('Patient not found');
    	}

    	$requiredValues = [];

    	
    	$messagePrintF = "Campo requiredo: <b>%s</b>";

    	if($patientInfo->date_of_birth === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Fecha de nacimiento');
    	}
    	if($patientInfo->how_found_us === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'¿Como nos encontraste?');
    	}
    	if($patientInfo->gender === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF, 'Genero');
    	}
    	if($patientInfo->interpreter_needed === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'¿Necesita interprete?');
    	}
    	/*if($patientInfo->advanced_directive_offered === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Was advance directive Offered');
    	}
    	if($patientInfo->advanced_directive_taken === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Was advance directive Taken');
    	}*/
    	
    	if($patientInfo->address === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Dirección');
    	}
    	if($patientInfo->address_state === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Estado');
    	}
    	if($patientInfo->address_city === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Ciudad');
    	}
    	if($patientInfo->address_zipcode === '')
    	{
    		$requiredValues[] = sprintf($messagePrintF,'Codigo Postal');
    	}

    	if( $redirect===TRUE && count($requiredValues) )
    	{
    		//$requiredValues[] = "<h4>Please, Capture required fields</h4><br>";
    		redirect('/patient/detail/'. $patient_id.'/?open_modal=basic' );
    	}
    	
    	/*
		else if( $redirect === TRUE && $patientInfo->recorded_history == 0 )
    	{
    		redirect('patient/history/capture/'. $patient_id.'/' );
    	}
    	*/
    	return $requiredValues;
    }
 	
 	/**
 	 * @param Integer $patientID
 	 * 
 	 * Check all available Elements if Patient can be removed
 	 * 
 	 * @return boolean
 	 */
    function isAvailableForRemove( $patientID )
    {
    	$patientID = (int)$patientID;

    	$model = $this->db;

    	if( $model->from('encounter_referrals')->where(['patient_id' => $patientID])->count_all_results() )
        	return false;

    	if( $model->from('appointment')->where(['patient_id' => $patientID])->count_all_results() )
        	return false;
    	
    	if( $model->from('encounter')->where(['patient_id' => $patientID])->count_all_results() )
        	return false;
       	
        if( $model->from('billing')->where(['patient_id' => $patientID])->count_all_results() )
        	return false;

        if( $model->from('patient_related_files')->where(['patient_id' => $patientID])->count_all_results() )
        	return false;
        
        return true;

    }

}
