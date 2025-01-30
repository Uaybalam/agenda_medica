<?php
/**
 * @route:migration/update/files
 */
class Migration_Update_Controller extends APP_User_Controller
{	
	private $columns_files = [];
	private $currentDate = null;

	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Related_Files_Model' => 'Files_DB'
		]);
		
		$this->load->library([ 
			'Migration_HS' => 'Migration_HS'
		]);

		$this->currentDate = new \DateTime();
	}

	/**
	 * @route:run/(:any)
	 */
	function run( $key )
	{
			
		//$this->Migration_HS->key_code_valid( $key );
		
		$this->_create_clean_csv();
		$this->Migration_HS->_log[] = "Files clean csv was created.";
		
		$total = $this->_insert_data_files();
		$this->Migration_HS->_log[] = "Inserted data files {$total}";
		
		$this->Migration_HS->jsonSuccess();
	}

	/**
	 * @route:currentValues
	 */
	function currentValues()
	{
		
		$pathFiles            = $this->Migration_HS->full_path( 'initial_csv','files/');
		$file_not_inserted    = $this->Migration_HS->open( 'not_inserted','files.not_inserted.txt');
		$file_clean_data      = $this->Migration_HS->open( 'clean_csv','files.clean.csv');
		$patient_relation_key = $this->Migration_HS->getArrayFile('relation' ,'patients.relations.php');
		$migrated_file_names  = $this->Migration_HS->getArrayFile('relation' ,'migrated.files.php');
		
		
		$date             = date('Y-m-d H:i');
		$arrayFiles       = scandir( $pathFiles );
		$arr_not_inserted = [];
		$primaryID        = $this->db->select_max('id')->from('patient_related_files')->get()->row_array()['id'];
		echo "<h2>MAX ID $primaryID</h2>";
		echo "<h3>Total de nuevos archivos (<small>".count($arrayFiles)."</small>)</h3>";
		echo "<h3>Total de archivos actuales (<small>".count($migrated_file_names)."</small>)</h3>";
		
	}
	
	private function _create_clean_csv()
	{	
		$pathFiles            = $this->Migration_HS->full_path( 'initial_csv','files/');
		$file_not_inserted    = $this->Migration_HS->open( 'not_inserted','files.not_inserted.txt');
		$file_clean_data      = $this->Migration_HS->open( 'clean_csv','files.clean.csv');
		$patient_relation_key = $this->Migration_HS->getArrayFile('relation' ,'patients.relations.php');
		$migrated_file_names  = $this->Migration_HS->getArrayFile('relation' ,'migrated.files.php');
		
		$arrayFiles       = scandir( $pathFiles );
		$arr_not_inserted = [];
		$relatedFiles     = $this->db->select_max('id')->from('patient_related_files')->get()->row_array();
		$primaryID  	  = ($relatedFiles['id']) ? (int)$relatedFiles['id'] : 0;
		
		foreach ($arrayFiles as $file) {

			$fileInfo    = pathinfo( $pathFiles.$file );
			
			if(!isset($fileInfo['extension']) || $fileInfo['extension']=='')
			{
				continue;
			}

			$data = explode("$$", $fileInfo['filename']);
			if(count($data)!=4)
			{
				continue;
			}

			$patient_id = (isset($patient_relation_key[$data[0]])) ? $patient_relation_key[$data[0]] : 0;
			$type       = ucfirst(strtolower(trim($data[1])));
			if($type==='Xray')
			{
				$type = 'X-Ray';
			}
			
			$title      = $this->Migration_HS->cleanString(trim($data[3]));
			
			$extension  = strtolower($fileInfo['extension']);

			if(!$patient_id)
			{
				$arr_not_inserted[] = "Patient not found [".$data[0]."] in File [".$file."]";
				continue;
			}

			$primaryID++;

			$file_name = "patient_related_file_{$primaryID}";
			$newFile   = $this->patient_path( $patient_id ) ."/$file_name.$extension";
			$oldFile   = $fileInfo['dirname'].'/'.$fileInfo['basename'];
			
			$fileInfo['filename'] = str_replace(["'",'"'], "",$fileInfo['filename']);
			
			if(isset($migrated_file_names[$fileInfo['filename']] ))
			{
				$arr_not_inserted[] = "file canot by copied ({$oldFile}), check folder access";
				$primaryID--;
				continue;
			}
			
			$migrated_file_names[$fileInfo['filename']] = 1;

			if( !copy($oldFile, $newFile) )
			{
				$arr_not_inserted[] = "file canot by copied ({$oldFile}), check folder access";
				$primaryID--;
				continue;
			}

			$this->columns_files = [
				'id'         => $primaryID,
				'patient_id' => $patient_id,
				'title'      => $title,
				'type'       => $type,
				'file_name'  => "$file_name.$extension",
				'create_at'  => $this->currentDate->format('Y-m-d H:i:s'),
			]; 

			fputcsv($file_clean_data, $this->columns_files );
		}
		
		$this->Migration_HS->saveRelationFile('migrated.files.php', $migrated_file_names );
		
		fwrite( $file_not_inserted, implode("\n", $arr_not_inserted ) );
		
		fclose($file_not_inserted);
		fclose($file_clean_data);
		
	}
	
	private function _insert_data_files()
	{
		if(!count( $this->columns_files)  )
			return 0;
		
		$path = $this->Migration_HS->full_path( 'clean_csv','files.clean.csv');
		if(!$path)
		{
			$this->Migration_HS->jsonError('File files.clean.csv not found');
		}

		return $this->Migration_HS->importData( $path, array_keys( $this->columns_files ) , 'patient_related_files' );
	}

		
}

