<?php
/**
 * @route:location
 */
class Location_Controller extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model(['Location_Model' => 'Location_DB']);
	}
	
	/**
	 * @route:filter
	 */
	function filter()
	{	
		$type = gettype($this->input->get('zipCode'));
		if(in_array($type, ['string','integer']))
		{
			$filterZipCode = trim($this->input->get('zipCode'));
		}
		else
		{	
			return $this->template->json( [
				'status' => 0,
			]);
		}

		$this->db
			->from('location')
			->where(['zipcode' => $filterZipCode  ])
			->limit( 1 );
		
		$location = $this->db->get()->row();

		if($location)
			return $this->template->json( [
				'status' => 1,
				'location' => $location
			]);

		return $this->template->json( [
			'status' => 0,
			'location' => []
		]);
	}	

	/**
	 * @route:{get}updateLocations
	 */
	function updateLocations()
	{
		$this->db->query('TRUNCATE TABLE location');	
		
		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);

		$this->settings = $this->Migration_HS->getArrayFile('settings',"config.php");
		
		$this->date_save = date('Y-m-d H:i:s');
		
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

			pr($result);
		}
		else
		{
			pr("something is wrong");
		}
	}
	
}