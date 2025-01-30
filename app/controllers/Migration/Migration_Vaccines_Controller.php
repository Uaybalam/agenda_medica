<?php
/**
 * @route:migration/vaccines
 */
class Migration_Vaccines_Controller extends APP_User_Controller
{	

	
	function __construct()
	{
		parent::__construct();

		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);
		//$this->date_save = date('Y-m-d H:i:s');
	}

	/**
	 * @route:run/(:any)
	 */
	function run( $key )
	{
			
		//$this->Migration_HS->key_code_valid( $key );
	
		$this->_create_clean_csv();
		$this->Migration_HS->_log[] = "Files clean csv was created.";
		
		//$rows_affected = $this->_insert_data_addendums();
		//$this->Migration_HS->_log[] = "Addendums inserted {$rows_affected}.";

		//$this->_relation_patient();
		//$this->Migration_HS->_log[] = "Addendums asigned patients";

		$this->Migration_HS->jsonSuccess();
		
	}
	
	private function _create_clean_csv()
	{
			
		$file_vaccines             = $this->Migration_HS->open('initial_csv','vaccines.csv', 'r');
		$file_clean_vaccines       = $this->Migration_HS->open('clean_csv', 'vaccines.clean.csv');
		$file_not_inserted         = $this->Migration_HS->open( 'not_inserted','vaccines.not_inserted.txt');
		
		$encounter_relation_key    = $this->Migration_HS->getArrayFile('relation' ,'encounters.relations.php');
		$patient_relation_key      = $this->Migration_HS->getArrayFile('relation' ,'patients.relations.php');
		$users_rellation_key       = $this->Migration_HS->getArrayFile('relation' ,'users.relations.php');
		$users_rellation_signature = $this->Migration_HS->getArrayFile('relation' ,'users.relation_signature.php');
		
		$row = $vaccine_id = 0;
		
		$arr_not_inserted = [];

		while (($buffer = fgets($file_vaccines)) !== false) 
		{
			
			$row++;

			/**
			 * ignore first line
			 */
			if($row <= 1 )
			{		
				$this->Migration_HS->setColumnNames( $buffer, [
					'UserPIN',
					'Notes',
					'MV_ID',
					'Date',
					'CreatedBy',
					'c_NotePreview',
					'Addendum ID'
				]);

				continue;
			}
			
			$data = $this->Migration_HS->getData( $buffer );

			$addID = $data['Addendum ID'];

			/**
			 *  validations
			*/
			$notes        = $this->Migration_HS->cleanString( $data['Notes'] );
			
			$encounter_id = isset($relation_encounter[$data['MV_ID']] ) ? $relation_encounter[$data['MV_ID']] : 0;
			$user_id      = isset($rellation_user[$data['CreatedBy']] ) ? $rellation_user[$data['CreatedBy']] : 0;
			$create_at    = $this->Migration_HS->cleanDateTime( $data['Date'] );
			
			if( $encounter_id === 0)
			{
				$arr_not_inserted[] = "Row[{$row}]::Relation encounter id not found [".$data['MV_ID']."], AddID[{$addID}]";
				continue;
			}
			if(!$notes)
			{
				$arr_not_inserted[] = "Row[{$row}]::Notes Field is null, AddID[{$addID}]";
				continue;
			}
			if(!$CreatedBy = trim($data['CreatedBy']) )
			{
				$arr_not_inserted[] = "Row[{$row}]::CreateBy Field is null, AddID[{$addID}]";
				continue;
			}

			if($user_id === 0 )
			{
				$user_id = $rellation_user[$CreatedBy] = $this->_create_user($CreatedBy);
			}
			
			/**
			 * Data save
			 */
			$addendum_id++;


			$this->columns_addendums = [
				'id'=>$addendum_id,
				'encounter_id'=> $encounter_id,
				'user_id'=> $user_id ,
				'notes'=> $notes,
				'create_at'=> $create_at
			];
			
			fputcsv($file_clean_addendums, $this->columns_addendums );
		}

		fwrite( $file_not_inserted, implode("\n", $arr_not_inserted ) );


		/**
		 * close files temporal
		 */
		fclose($file_clean_addendums);
		fclose($file_addendums);
		fclose($file_not_inserted);
	}

	private function _insert_data_addendums()
	{
		$path = $this->Migration_HS->full_path( 'clean_csv','addendums.clean.csv');
		
		if(!$path)
		{
			$this->Migration_HS->jsonError('File addendums.clean.csv not found');
		}

		return $this->Migration_HS->importData( $path, array_keys( $this->columns_addendums ) , 'encounter_addendum' );
	}

	


}