<?php
/**
 * @route:migration/files
 */
class Migration_Patient_Controller extends CI_Controller
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

		$pathFiles	 = $this->Migration_HS->full_path( 'initial_csv','patients.csv');
		$currentDate = new \DateTime();
		$date        = $currentDate->format('Y-m-d H:i');
		$i           = 0;

		ini_set('auto_detect_line_endings',TRUE);

		if (($gestor = fopen($pathFiles, "r")) !== FALSE) {
		    while (($data = fgetcsv($gestor, 5000, ",", '"', "\n")) !== FALSE) {
		    	if($i > 0){
		    		if($data[33] != ""){
		    			$dateText = "";
			    		
			    		if (strpos($data[33], '-') !== false) {
						    $dateText = explode("-",$data[33]);
						}
						elseif (strpos($data[33], '.') !== false) {
							$dateText = explode(".",$data[33]);
						}
						else{
							$dateText = explode("/",$data[33]);
						}

						$dateText[0] = (strlen ($dateText[0]) == 1)? "0".$dateText[0] : $dateText[0];
						$dateText[1] = (strlen ($dateText[1]) == 1)? "0".$dateText[1] : $dateText[1];

						$data[33] = $dateText[2]."-".$dateText[0]."-".$dateText[1];
					}

					$gender = "";

					if($data[25] != "" || $data[26] != "")
					{
						$gender = ($data[25] == "") ? "Female" : "Male";
					}


					$rGender = "";

					if($data[141] != "" || $data[142] != "")
					{
						$rGender = ($data[141] == "") ? "Female" : "Male";
					}

					$marital_status = 0;

					if(strtolower($data[21]) == "yes" ){ $marital_status = 1; }
					if(strtolower($data[22]) == "yes" ){ $marital_status = 2; }
					if(strtolower($data[23]) == "yes" ){ $marital_status = 3; }
			         
			        $columns = [
						"id" => $data[54],
						"name" => $data[27],                              
						"middle_name" => $data[20],   
						"last_name" => $data[24], 
						"date_of_birth" => $data[33],  
						"phone" => $data[18],
						"phone_memo" => "",    
						"phone_alt" => $data[19],  
						"phone_alt_memo" => "",
						"email" => $data[32], 
						"ethnicity" => $data[28],
						"blood_type" => "",
						"gender" => $gender,
						"address" => $data[16],
						"address_zipcode" => $data[15],
						"address_city" => $data[34],                     
						"address_state" => $data[17],
						"imagen" => '',                            
						"how_found_us" => $data[14], 
						"interpreter_needed" => $data[52], 
						"advanced_directive_offered" => $data[53],          
						"advanced_directive_taken" => isset($data[156])? $data[156] : "",             
						"language" => "",                   
						#"appointments_count"                    
						#"recorded_history"                     
						#"recorded_history_user_id"             
						#"recorded_history_at"                  
						"recorded_history_surgeries" => $data[68],          
						"status" => "",                               
						"marital_status" => $marital_status,                      
						"completed" => "",                          
						"balance_due" => "",                          
						"create_at" => $date,                             
						"update_at" => $date,                            
						#"recorded_history_current_medications" 
						#"recorded_history_comments"            
						"insurance_primary_status" => "",              
						"insurance_secondary_status" => "",            
						"insurance_secondary_plan_name" => $data[55],         
						"insurance_secondary_identify" => $data[56],          
						"insurance_secondary_notes" => "",            
						"insurance_primary_plan_name" => $data[58],          
						"insurance_primary_identify" => $data[59],           
						"insurance_primary_notes" => "",              
						"membership_name" => $data[12],                        
						"membership_date" => $data[11],                        
						"membership_type" => $data[9],                        
						"membership_notes" => $data[10],                       
						"responsible_name" => $data[143],                      
						"responsible_middle_name" => $data[139],               
						"responsible_last_name" => $data[140],                 
						"responsible_gender" => $rGender,              
						"responsible_phone" => $data[137],                     
						"responsible_phone_alt" => $data[138],                 
						"responsible_relationship" => "",              
						"responsible_address" => $data[145],                   
						"responsible_address_city" => $data[144],              
						"responsible_address_state" => $data[136],             
						"responsible_address_zipcode" => $data[135],           
						"responsible_self" => "",                   
						"emergency_name" => $data[153],                       
						"emergency_middle_name" => $data[151],                   
						"emergency_last_name" => $data[152],                     
						"emergency_gender" => "",                    
						"emergency_phone" => $data[149],                        
						"emergency_phone_alt" => $data[150],                   
						"emergency_address" => $data[155],                       
						"emergency_address_city" => $data[154],                  
						"emergency_address_state" => $data[147],                 
						"emergency_address_zipcode" => $data[146],               
						"emergency_relationship" => $data[148],                
						"advanced_directive_offerded" => $data[53],           
						"open_balance" => "",                         
						"discount_type" => "",                       
						"prevention_allergies" => "None",               
						"prevention_alcohol" => 0,                   
						"prevention_drugs" => 0,                     
						"prevention_tobacco" => 0,
					];	
						
					if(!$this->db->select('id')->from('patient')->where(['id' => $data[54]])->get()->row_array()){
						
						$this->db->insert('patient', $columns );
					}
				}
				
				$i++;
		    }
		    fclose($gestor);
		}

	}

}
