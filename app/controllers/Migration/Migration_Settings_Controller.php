<?php

/**
 * @route:migration/settings
 */
class Migration_Settings_Controller extends APP_User_Controller
{	
	private $columns_required_csv = [];

	private $columns_patient        = [];
	
	private $columns_tuberculosis   = [];
	
	private $columns_history_active = [];

	private $settings;

	private $_currentData;

	private $columns_name_settings;

	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Patient_Model' => 'Patient_DB'
		]);
		
		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);
		
		$this->settings = $this->Migration_HS->getArrayFile('settings',"config.php");
		
		$this->date_save = date('Y-m-d H:i:s');
	}

	/**
	 * @route:run/(:any)
	 */
	function run( $key )
	{
			
		$this->Migration_HS->key_code_valid( $key );

		$this->clinic = $this->input->get('clinic');

		$this->_insert_insurances();
		$this->Migration_HS->_log[] = 'insurances Added';
		
		$this->_insert_educations();
		$this->Migration_HS->_log[] = 'educations Added';

		$this->_insert_referral_specialty();
		$this->Migration_HS->_log[] = 'Referral Specialties Added';
		
		$this->_insert_referral_services();
		$this->Migration_HS->_log[] = 'Referral Services Added';
		
		$this->_insert_medications();
		$this->Migration_HS->_log[] = 'Medications Added';
		
		$this->_insert_allergies();
		$this->Migration_HS->_log[] = 'Allergies Added';
		
		$this->_insert_how_found_us();
		$this->Migration_HS->_log[] = 'How Did You Found Us Options Added';		
		
		$this->_insert_request();
		$this->Migration_HS->_log[] = 'Requests Added';	

		$this->_insert_languages();
		$this->Migration_HS->_log[] = 'Languages Added';	

		$this->_insert_locations();
		$this->Migration_HS->_log[] = 'Locations Added';

		/**
		 * 
		 */
		$this->Migration_HS->jsonSuccess();
	}

	private function _insert_insurances()
	{
		
		$sqlString = " INSERT INTO custom_setting (name,type) ";
		$sqlString.= " SELECT DISTINCT insurance_primary_plan_name,'setting_insurance' FROM patient WHERE insurance_primary_plan_name!='' ";
		return  $this->db->query( $sqlString );	
	}

	private function _insert_educations()
	{
		$educations = [];
		$sqlString  = " SELECT DISTINCT procedure_patient_education FROM encounter WHERE procedure_patient_education!='' ";
		$query      = $this->db->query( $sqlString );	
		$result     = $query->result_array();
		
		foreach ($result as $key => $R) {
			
			//ctype_upper
			$tmp_educations = explode(",",$R['procedure_patient_education']);

			if( count($tmp_educations) )
			{
				foreach ($tmp_educations as $key => $value) {
					if(ctype_upper($value))
					{
						$educations[$value] = 1;
					}
				}
			}
		}
		
		foreach ( $educations as $key => $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $this->Migration_HS->cleanString($key)."','setting_education')");	
		}
	}

	private function _insert_referral_specialty()
	{
		$referrals = [];
		$sqlString = " SELECT DISTINCT speciality FROM encounter_referrals WHERE speciality!='' ";
		$query     = $this->db->query( $sqlString );	
		$result    = $query->result_array();
		
		foreach ($result as $key => $R) {
			
			if(ctype_upper($R['speciality']))
			{
				$referrals[$R['speciality']] = 1;
			}
		}

		foreach ( $referrals as $key => $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $this->Migration_HS->cleanString($key)."','setting_referral_specialty')");	
		}
	}

	private function _insert_referral_services()
	{
		$referrals = [];
		$sqlString = " SELECT DISTINCT service FROM encounter_referrals WHERE service!='' ";
		$query     = $this->db->query( $sqlString );
		$result    = $query->result_array();
		
		foreach ($result as $key => $R) {

			if(ctype_upper($R['service']))
			{
				$referrals[$R['service']] = 1;
			}
		}

		foreach ( $referrals as $key => $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $this->Migration_HS->cleanString($key)."','setting_referral_service')");	
		}
	}

	private function _insert_medications()
	{

		$sqlString = " INSERT INTO custom_setting (name,type) ";
		$sqlString.= " SELECT DISTINCT title,'setting_medication' FROM encounter_medication WHERE title!='' ";
		return  $this->db->query( $sqlString );	
	}

	private function _insert_allergies()
	{
		
		$allergies = [];
		$sqlString = "SELECT DISTINCT prevention_allergies FROM patient WHERE prevention_allergies!='' ";
		$query     = $this->db->query( $sqlString );	
		$result    = $query->result_array();
		
		foreach ($result as $key => $R) {
			
			//ctype_upper
			$tmp_educations = explode(",",$R['prevention_allergies']);

			if( count($tmp_educations) )
			{
				foreach ($tmp_educations as $key => $value) {
					if(ctype_upper($value))
					{
						$allergies[$value] = 1;
					}
				}
			}
		}
		
		foreach ( $allergies as $key => $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $this->Migration_HS->cleanString($key)."','setting_allergie')");	
		}
	}

	private function _insert_how_found_us()
	{
		$how_found_us = [
			'Internet',
			'Google',
			'Facebook',
			'From a Friend',
			'From a Family',
			'Brochure',
			'Flyer',
			'Radio',
			'Yelp',
			'Outdoor',
			'Other'
		];

		foreach ( $how_found_us as $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $value."','setting_how_found_us')");	
		}
	}

	private function _insert_request()
	{
		$results = [];
		$sqlString = " SELECT DISTINCT title FROM encounter_results WHERE title!='' ";
		$query     = $this->db->query( $sqlString );
		$result    = $query->result_array();
		
		foreach ($result as $key => $R) {
			
			if(ctype_upper($R['title']))
			{
				$results[$R['title']] = 1;
			}
		}

		foreach ( $results as $key => $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $this->Migration_HS->cleanString($key)."','setting_request')");	
		}
	}
	
	private function _insert_languages()
	{
		$languages = [
			'Chinese',
			'Spanish',
			'English',
			'Tagalog',
			'Vietnamese',
			'Korean',
			'Armenian',
			'Russian',
			'Arabic',
			'Hindi'
		];

		foreach ( $languages as $value ) {
			$this->db->query("INSERT INTO custom_setting (name,type) values('". $value."','setting_language')");	
		}
	}

	private function _insert_locations()
	{
		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);
		
		$pathLocations       = FCPATH . '../private/seeds/us_postal_codes.csv';
		if(file_exists($pathLocations))
		{
			$result = $this->Migration_HS->importData( $pathLocations, Array(
				'zipcode',
				'city',
				'state_full',
				'state_short',
				'county'
			) , 'location', " IGNORE 1 LINES " );
		}
	}
	

}

