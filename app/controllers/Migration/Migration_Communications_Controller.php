<?php
/**
 * @route:migration/files
 */
class Migration_Communications_Controller extends CI_Controller
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

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','communications.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	//echo "<pre>".print_r($data,1)."</pre>";
		    	if($i > 0){
		    	
		    		if($data[4] != ""){
		    			$dateText 	  = "";
			    		$dateTimeText = explode(" ",$data[4]);
			    		if (strpos($dateTimeText[0], '-') !== false) {
						    $dateText = explode("-",$dateTimeText[0]);
						}
						elseif (strpos($dateTimeText[0], '.') !== false) {
							$dateText = explode(".",$dateTimeText[0]);
						}
						else{
							$dateText = explode("/",$dateTimeText[0]);
						}

						$dateText[0] = (strlen ($dateText[0]) == 1)? "0".$dateText[0] : $dateText[0];
						$dateText[1] = (strlen ($dateText[1]) == 1)? "0".$dateText[1] : $dateText[1];

						if(strtolower($dateTimeText[2]) == "pm"){
							$temp = explode(":",$dateTimeText[1]);
							$dateTimeText[1] = (intval($temp[0])+12).":".$temp[1];

							if(count($temp) == 3){
								$dateTimeText[1].=":".$temp[2];
							}
							else
							{
								$dateTimeText[1].=":00";
							}

						}

						$data[4] = $dateText[2]."-".$dateText[0]."-".$dateText[1]." ".$dateTimeText[1];
					}
					
		
			        $columns = [
			        	"id" => $data[2],
						"patient_id" => $data[3],
						"appointment_id" => 0,
						"patient_contact_id" => 0,
						"created_by_user" => 0,
						"type" => 0,
						"notes" => $data[5],
						"create_at" => $data[4], 
			        ];	
					
					if(!$this->db->select('id')->from('patient_communication')->where(['id' => $data[2]])->get()->row_array()){
						echo $data[2]."-";
						$this->db->insert('patient_communication', $columns );
					}
					
				}
				
				$i++;
		    }
		    fclose($gestor);
		}

	}

}
