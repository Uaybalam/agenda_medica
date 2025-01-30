<?php
/**
 * @route:migration/files
 */
class Migration_Encounter_Controller extends CI_Controller
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

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','encounters.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	echo "<pre>".print_r($data,1)."</pre>";
		    	if($i > 10){
		    		exit;
		    		
		    		if($data[129] != ""){
		    			$dateText = "";
			    		
			    		if (strpos($data[129], '-') !== false) {
						    $dateText = explode("-",$data[129]);
						}
						elseif (strpos($data[129], '.') !== false) {
							$dateText = explode(".",$data[129]);
						}
						else{
							$dateText = explode("/",$data[129]);
						}

						$dateText[0] = (strlen ($dateText[0]) == 1)? "0".$dateText[0] : $dateText[0];
						$dateText[1] = (strlen ($dateText[1]) == 1)? "0".$dateText[1] : $dateText[1];

						$data[129] = $dateText[2]."-".$dateText[0]."-".$dateText[1];
					}

					$eye_withglasses_both     = "";
			        $eye_withglasses_left     = "";
			        $eye_withglasses_right    = "";
			        $eye_withoutglasses_both  = "";
			        $eye_withoutglasses_left  = "";
			        $eye_withoutglasses_right = "";

			        if(strtolower($data[14]) == "yes")
			        {
			        	$eye_withglasses_both  = $data[18];
			        	$eye_withglasses_left  = $data[16];
			        	$eye_withglasses_right = $data[15];
			        }
			        else
			        {
			        	$eye_withoutglasses_both  = $data[18];
			        	$eye_withoutglasses_left  = $data[16];
			        	$eye_withoutglasses_right = $data[15];
			        }
			        /*
			        $columns = [
			            "appointment_id"  => 0,
			            "audio_left_1000"
			            "audio_left_2000"
			            "audio_left_3000"
			            "audio_left_4000"
			            "audio_right_1000"
			            "audio_right_2000"
			            "audio_right_3000"
			            "audio_right_4000"
			            "blood_pressure_dia"
			            "blood_pressure_sys"
			            "checked_out_id "
			            "chief_complaint"
			            "condition_autoaccident"
			            "condition_employment"
			            "condition_other_accident"
			            "condition_state"
			            "create_at"
			            "create_by"
			            "current_medications"
			            "eye_prescription_glasses"
			            "eye_questions"
			            "eye_withglasses_both" => $eye_withglasses_both,
			            "eye_withglasses_left" => $eye_withglasses_left,
			            "eye_withglasses_right" => $eye_withglasses_right,
			            "eye_withoutglasses_both" => $eye_withoutglasses_both,
			            "eye_withoutglasses_left" => $eye_withoutglasses_left,
			            "eye_withoutglasses_right" => $eye_withoutglasses_right,
			            "eye_worn_during_exam" => $data[14], 
			            "first_open"
			            "has_insurance  "
			            "heart_head_circ"
			            "heart_hematocrit" => $data[139], 
			            "heart_hemoglobin" => $data[138], 
			            "heart_last_menstrual_period" => $data[83],
			            "heart_pulse" => $data[138], 
			            "heart_respiratory" => $data[3],
			            "heart_temperature" => $data[1],
			            "id" => $data[250], 
			            "insurance_number" => "", 
			            "insurance_title" => $data[428], 
			            "next_appointment" => $data[129],
			            "pain"
			            "patient_id" => $data[291], 
			            "physical_birth_weight" => $data[212], 
			            "physical_height" => $data[5],
			            "physical_weight" => $data[0],
			            "present_illness_history"
			            "procedure_patient_education"
			            "procedure_text"
			            "signed_at"
			            "signed_time_at"
			            "status"
			            "update_at" => $date,
			            "urinalysis_bilirubim" => $data[25],   
			            "urinalysis_blood" => $data[32], 
			            "urinalysis_color" => $data[24], 
			            "urinalysis_glucose" => $data[23], 
			            "urinalysis_human_chorionic_gonadotropin"
			            "urinalysis_ketones" => $data[31], 
			            "urinalysis_leuktocytes" => $data[30], 
			            "urinalysis_nitrite" => $data[29], 
			            "urinalysis_ph" => $data[28], 
			            "urinalysis_protein" => $data[27], 
			            "urinalysis_specific_gravity" => $data[26], 
			            "user_id" => 0, 
			            "user_signature" => 0, 

			          ];	
					
					if(!$this->db->select('id')->from('patient')->where(['id' => $data[54]])->get()->row_array()){
						echo $data[54]."-";
						$this->db->insert('patient', $columns );
					}*/
				}
				
				$i++;
		    }
		    fclose($gestor);
		}

	}

}
