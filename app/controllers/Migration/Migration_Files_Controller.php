<?php
/**
 * @route:migration/files
 */
class Migration_Files_Controller extends CI_Controller
{	

	private $columns_files = [];

	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Related_Files_Model' => 'Files_DB'
		]);
		
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
		
		if(php_sapi_name()!=='cli')
		{
			echo "Only from terminal";
			exit;
		}

		$start = date('Y-m-d H:i');
		
		$this->_execFromTerminal();
		echo "\n\n\tStart ".$start;
		echo "\n\tEnd ".date('Y-m-d H:i')."\n";
		
	}
	
	private function _execFromTerminal(){

		$pathFiles                 = $this->Migration_HS->full_path( 'initial_csv','files/');

			$currentDate      = new \DateTime();
			$date             = $currentDate->format('Y-m-d H:i');
			$arr_not_inserted = [];
		$file_not_inserted         = $this->Migration_HS->open( 'not_inserted','files.not_inserted.txt');
		
		$patient_relation_key      = $this->Migration_HS->getArrayFile('relation' ,'patients.relations.php');
		$users_rellation_signature = $this->Migration_HS->getArrayFile('relation' ,'users.relation_signature.php');
		
		$arrayFiles = scandir( $pathFiles );
		$totalFiles = count($arrayFiles);
		$user_id_created = isset($users_rellation_signature[$this->settings['name_system_migration']]) ? 
			$users_rellation_signature[$this->settings['name_system_migration']] : 0;

		$documents = $this->db->select_max('id')->from('patient_related_files')->get()->row_array();

		
		foreach ($arrayFiles as $n => $file) {
			
			
			$fileInfo = pathinfo( $pathFiles.$file );
			
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
			$title      = $this->Migration_HS->cleanString(trim($data[3]));
			
			switch ($type) {
				case 'Xray':
					$type="X-Ray";
					break;
				case 'Ecg':
					$type="ECG";
					break;
				case 'Lab':
					$type="Laboratory";
					break;
			}

			$extension  = strtolower($fileInfo['extension']);

			if(!$patient_id)
			{
				$arr_not_inserted[] = "Patient not found [".$data[0]."] in File [".$file."]";
				continue;
			}

			$file_name = "document_".uniqid();
			$newFile   = $this->patient_path( $patient_id ) ."/$file_name.$extension";
			$oldFile   = $fileInfo['dirname'].'/'.$fileInfo['basename'];

			if( !copy($oldFile, $newFile) )
			{
				echo "file canot by copied ({$oldFile}), check folder access";
				exit;
			}

			$columns = [
				'patient_id'      => $patient_id,
				'title'           => $title,
				'type'            => $type,
				'user_id_created' => $user_id_created,
				'file_name'       => "$file_name.$extension",
				'create_at'       => $date,
				//'update_at'       => $date
			];

			if( $n== $totalFiles)
			{
				echo "\n 100.00 %";
			}
			else if( $n%20 == 0  )
			{
				$calc = ($n * 100) / $totalFiles;
				echo "\n ".number_format($calc, 2 )." %";
			}
			//echo (5 *100) / 10;

			//echo "\n".$n." Copied ".$title."";
			$this->db->insert('patient_related_files', $columns );
		}

		fwrite( $file_not_inserted, implode("\n", $arr_not_inserted ) );
		fclose($file_not_inserted);

	}

	private function patient_path( $ID )
	{	
		$path = FCPATH ."../private/uploads/patients/patient_{$ID}";
		if(!file_exists($path))
		{
			mkdir($path,0775);
		}
		return $path;
	}


}
