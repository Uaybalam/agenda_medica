<?php
/**
 * @route:migration/files
 */
class Migration_Medications_Controller extends CI_Controller
{	

	private $columns_files = [];

	function __construct()
	{
		parent::__construct();

		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);

		$this->settings  = $this->Migration_HS->getArrayFile('settings',"config.php");
	}

	/**
	 * @route:run/(:any)
	 */
	function run( $key )
	{
		
		/*if(php_sapi_name()!=='cli')
		{
			echo "Only from terminal";
			exit;
		}*/

		$start = date('Y-m-d H:i');
		
		$this->_execFromTerminal();
		echo "\n\n\tStart ".$start;
		echo "\n\tEnd ".date('Y-m-d H:i')."\n";
		
	}
	
	private function _execFromTerminal(){

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','medications.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	echo "<pre>".print_r($data,1)."</pre>";
		    	/*if($i > 0){
		    
			        $columns = [
			        	"id" => $data[1],
						"encounter_id" => $data[0],
						"comment" => $data[2],
						"chronic" => $data[3],
			        ];	
					
					if(!$this->db->select('id')->from('encounter_diagnosis')->where(['id' => $data[1]])->get()->row_array()){
						$this->db->insert('encounter_diagnosis', $columns );
					}
					
				}
				
				$i++;*/
		    }
		    fclose($gestor);
		}

	}

}
