<?php
/**
 * @route:migration/files
 */
class Migration_Allergies_Controller extends CI_Controller
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

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','allergies.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	echo "<pre>".print_r($data,1)."</pre>";
		    	/*if($i > 0){

		    		if($data[3] != ""){
		    			$dateText = "";
			    		
			    		if (strpos($data[3], '-') !== false) {
						    $dateText = explode("-",$data[3]);
						}
						elseif (strpos($data[3], '.') !== false) {
							$dateText = explode(".",$data[3]);
						}
						else{
							$dateText = explode("/",$data[3]);
						}

						$dateText[0] = (strlen ($dateText[0]) == 1)? "0".$dateText[0] : $dateText[0];
						$dateText[2] = (strlen ($dateText[2]) == 1)? "0".$dateText[2] : $dateText[2];

						$data[3] = $dateText[2]."-".$dateText[0]."-".$dateText[1];
					}
		
			        $columns = [
			        	"id" => $data[6],
			            "encounter_id" => $data[2],
			            "notes" => $data[5],
			            "user_id" => 0, 
			            "is_request" => 0, 
			            'create_at' => $data[3],
			        ];	
					
					if(!$this->db->select('id')->from('encounter_addendum')->where(['id' => $data[6]])->get()->row_array()){
						$this->db->insert('encounter_addendum', $columns );
					}
				}
				
				$i++;*/
		    }
		    fclose($gestor);
		}

	}

}
