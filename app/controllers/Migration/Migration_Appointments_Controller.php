<?php
/**
 * @route:migration/files
 */
class Migration_Appointments_Controller extends CI_Controller
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

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','appointments.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	
		    	if($i > 0){
		    	
		    		if (strpos($data[17], "Ev") === false) {
			    		if($data[15] != ""){
			    			$dateText = "";
				    		
				    		if (strpos($data[15], '-') !== false) {
							    $dateText = explode("-",$data[15]);
							}
							elseif (strpos($data[15], '.') !== false) {
								$dateText = explode(".",$data[15]);
							}
							else{
								$dateText = explode("/",$data[15]);
							}

							$dateText[0] = (strlen ($dateText[0]) == 1)? "0".$dateText[0] : $dateText[0];
							$dateText[1] = (strlen ($dateText[1]) == 1)? "0".$dateText[1] : $dateText[1];

							$data[15] = $dateText[2]."-".$dateText[0]."-".$dateText[1];
						}
			
				        $columns = [
				        	"id" => $data[17],
							"create_user_by" => 0,
							"patient_id" => $data[10],
							"encounter_id" => 0,
							"type_appointment" => 0,
							"date_appointment" => $data[15],
							"code" => $data[4],
							"notes" => $data[13],
							"visit_type" => $data[3],
							"status" => 7,
							"room" => 0,
							"create_at" => $data[15],
							"update_at" => $data[15],
							"has_insurance" => 0,
							"insurance_type" => "",
							"user_arrival_id" => 0,
							"time_arrival" => $data[6],
							"user_chartup_id" => 0,
							"time_chartup" => 0,
							"user_nurse_id" => 0,
							"time_nurse" => 0,
							"time_room" => 0,
							"user_signed_id" => 0,
							"time_signed" => $data[6],
							"time_done" => 0,
							"checked_out_id" => 0,
							"time_open" => $data[8],
				        ];	
						
						if(!$this->db->select('id')->from('appointment')->where(['id' => $data[17]])->get()->row_array()){
							$this->db->insert('appointment', $columns );
						}
					}
				}
				
				$i++;
		    }
		    fclose($gestor);
		}

	}

}
