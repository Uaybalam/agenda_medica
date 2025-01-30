<?php
@ini_set('memory_limit', '512M');
/**
* @route:migration/user
*/
class Migration_User_Controller extends APP_User_Controller
{
	
	function __construct()
	{	
		parent::__construct();

		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);
	}

	/**
	 * @route:{get}run/(:any)
	 */
	function run( $token )
	{
		
		$this->Migration_HS->key_code_valid( $token );
		

		/**
		 * @create users.clean
		 */
		$this->_loop_data();
		
		/**
		 * 
		 */
		$this->Migration_HS->jsonSuccess();
	}
	
	private function _loop_data()
	{	
		
		$file_users             = $this->Migration_HS->open( 'initial_csv','users.csv', 'r');
		$file_users_notinserted = $this->Migration_HS->open( 'not_inserted','users.not_inserted.txt');
		
		$row = 0;
		
		$list_KEY_users_username  = [];
		$list_KEY_users_signature = [];
		$list_NOT_inserted        = [];
		
		while (($buffer = fgets($file_users)) !== false) 
		{
			$row++;

			if($row === 1 )
			{
				$this->Migration_HS->setColumnNames( $buffer ,[
						'Password',
						'Login_Name',
						'Gender',
						'Name',
						'Phone',
						'DOB',
						'Employment Status',
						'Employment Date',
						'EmergencyMedcialInfo',
						'ReceiptConfidentiality Statement',
						'Zip',
						'City',
						'Address',
						'EmergencyContact_Relation',
						'EmergencyContact_Phone',
						'EmergencyContact_Name',
						'EmergencyContact_Address',
						'EmergencyContactOther_Relation',
						'EmergencyContactOther_Phone',
						'EmergencyContactOther_Name',
						'EmergencyContactOther_Doctor',
						'EmergencyContactOther_DoctorPhone',
						'EmergencyContactOther_DoctorAddress',
						'DigitalSignature',
					]);

				continue;
			}

			$data = $this->Migration_HS->getData( $buffer );

			/**
			 *  Validations
			 */
			$loginName = preg_replace("/[^a-zA-Z0-9]+/", "", strtolower( trim($data['Login_Name']) ) );
			
			if($loginName === '')
			{
				$list_NOT_inserted[] = "ROW [$row] not have loginName";
				continue;
			}
			
			$gender       = ($data['Gender']!='' && ( $data['Gender'][0]=='F' || $data['Gender'][0]=='f') ) ? 'Female' : 'Male';
			$fullName     = strtolower(trim($data['Name']) );
			$explode_name = explode(" ", $fullName);

			$names        = ucfirst($explode_name[0]);
			unset($explode_name[0]);
			$last_name = (count($explode_name)) ?  ucfirst(implode(" ", $explode_name)) : '';

			$names = ($names === '') ? ucfirst($loginName) : $names;
			
			if(isset($list_KEY_users_username[$loginName]))
			{
				$list_NOT_inserted[] = "ROW [$row] loginName is repeated [{$loginName}]";
				continue;
			}

			$this->db->select('*')->from('user');
			$this->db->where(['nick_name' => $loginName ]);
			$userExist = $this->db->get()->row_array();
			
			if( $userExist && $userExist['id'])
			{
				$list_NOT_inserted[] = "ROW [$row] user in DataBase [{$loginName}]";
				$list_KEY_users_username[$loginName] = $userExist['id'];
				$list_KEY_users_signature[$userExist['digital_signature']] = $userExist['id'];
				continue;
			}

			$password = ($data['Password']) ? $data['Password'] : uniqid();
			
			/**
			 * Data basic
			 */
			$this->User_DB->nick_name           = $loginName;
			$this->User_DB->password            = password_hash( $password , PASSWORD_BCRYPT );
			$this->User_DB->status              = 0; //# ($data[33]) ? 1 : 0;
			$this->User_DB->names               = $names;
			$this->User_DB->last_name           = $last_name;
			$this->User_DB->marital_status      = 'Single';
			$this->User_DB->gender              = $gender;
			$this->User_DB->phone               = $this->Migration_HS->cleanPhone($data['Phone']);
			$this->User_DB->date_of_birth       = $this->Migration_HS->cleanDate($data['DOB']);
			$this->User_DB->employment_status   = trim($data['Employment Status']);
			$this->User_DB->employment_date     = $this->Migration_HS->cleanDate($data['Employment Date']);
			$this->User_DB->medical_information = $this->Migration_HS->cleanString($data['EmergencyMedcialInfo']);
			$this->User_DB->digital_signature   = trim($data['DigitalSignature']);
			$this->User_DB->receipt_confidentiality_statement = (trim($data['ReceiptConfidentiality Statement']) ) ? 'Yes' : 'No';
			
			/**
			 * Data address
			 */
			$this->User_DB->address_zipcode = $data['Zip'];
			$this->User_DB->address_city    = $data['City'];
			$this->User_DB->address         = $this->Migration_HS->cleanString($data['Address']);
			
			/**
			 * Data contact primary
			 */
			$this->User_DB->emergency_contact_relation     = trim($data['EmergencyContact_Relation']);
			$this->User_DB->emergency_contact_phone        = $this->Migration_HS->cleanPhone($data['EmergencyContact_Phone']);
			$this->User_DB->emergency_contact_name         = trim($data['EmergencyContact_Name']);
			$this->User_DB->emergency_contact_full_address = $this->Migration_HS->cleanString($data['EmergencyContact_Address']);

			/**
			 * Data contact secondary
			 */
			$this->User_DB->emergency_contact_other_relation     = trim($data['EmergencyContactOther_Relation']);
			$this->User_DB->emergency_contact_other_phone        = $this->Migration_HS->cleanPhone($data['EmergencyContactOther_Phone']);
			$this->User_DB->emergency_contact_other_name         = trim($data['EmergencyContactOther_Name']);
			$this->User_DB->emergency_contact_other_full_address = '';

			/**
			 * Data doctor
			 */
			$this->User_DB->emergency_contact_doctor_name    = trim($data['EmergencyContactOther_Doctor']);
			$this->User_DB->emergency_contact_doctor_phone   = $this->Migration_HS->cleanPhone($data['EmergencyContactOther_DoctorPhone']);
			$this->User_DB->emergency_contact_doctor_address = $this->Migration_HS->cleanString($data['EmergencyContactOther_DoctorAddress']);
			
			$user_id = $this->User_DB->save();
			
			$list_KEY_users_username[$loginName] 	   = $user_id;
			$list_KEY_users_signature[trim($data['DigitalSignature'])] = $user_id;
		}
		
		$this->Migration_HS->saveRelationFile('users.relations.php', $list_KEY_users_username);
		$this->Migration_HS->saveRelationFile('users.relation_signature.php', $list_KEY_users_signature);

		fwrite($file_users_notinserted, implode("\n", $list_NOT_inserted));
		$this->Migration_HS->_log[] = "Users not inserted created in txt";

		fclose($file_users);
		fclose($file_users_notinserted);
		
		return true;
	}
}

