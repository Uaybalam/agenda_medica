<?php
/**
 * @route:migration/fixed-files
 */
class Migration_File_Fixed_Names_Controller extends APP_User_Controller
{
	private $columns_files = [];

	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Related_Files_Model' => 'Files_DB'
		]);
	}

	/**
	 * @route:run
	 */
	function run()
	{
		$query = $this->db;
		$query->select('id, title')->from('patient_related_files');
		$query->where(["DATE_FORMAT(create_at,'%m/%d/%Y')" => "08/31/2018"]);
		$query->or_where(["DATE_FORMAT(create_at,'%m/%d/%Y')" => "08/01/2018"]);
		
		$files       = $query->get()->result_array();
		$trueDate    = $falseDate = 0 ;
		$currentDate = date('Y-m-d H:i:43');

		foreach ($files as $file ) {
			$parseDate = $this->_parseDate($file['title']);
			if($parseDate)
			{	
				if($this->input->get('update') == 'TRUE' )
				{
					$this->db->where(['id' => $file['id']]);
					$this->db->update('patient_related_files', [
						'create_at' => $parseDate,
						'update_at' => $currentDate
					]);	
				}
				$trueDate++; 
			}
			else
			{
				$falseDate++;
			}
		}
		if($this->input->get('update') == 'TRUE' )
		{
			echo "<h3>Updated</h3>";
		}
		echo "<p>Current Date {$currentDate}</p>";
		echo "<p>Parse Date {$trueDate}</p>";
		echo "<p>No-Parse Date {$falseDate}</p>";
		echo "<p>Totals ".count($files)."</p>";
	}



	private function _parseDate( $title = '' )
	{
		//get date
		$explodeTitle = explode(" ",$title);
		$setDate      = null;
		foreach ($explodeTitle as $value ) 
		{
			preg_match("/[0-9]{1,2}-[0-9]{1,2}-[0-9]{2,4}/",
				$value,
				$match
			);
						
			if(!count($match))
				continue;

			$explodeDate = explode("-", $match[0]);
			if(count($explodeDate)!==3)
				continue;

			$month   = $explodeDate[0];
			$day     = $explodeDate[1];
			$year    = $explodeDate[2];

			if(strlen($year)==4)
			{
				$setDate = new \DateTime("$year-$month-$day 08:43:18");
			}
			else if(strlen($year)==3)
			{
				$year = "20".$year[0].$year[1];
				$setDate = new \DateTime("$year-$month-$day 08:43:18");	
			}
			else
			{
				$year    = "20{$year}";
				$setDate = new \DateTime("$year-$month-$day 08:43:18");	
			}
		}

		if(!is_null($setDate))
		{
			return $setDate->format('Y-m-d H:i:s');
		}
		else
		{
			return false;
		}
	}
}