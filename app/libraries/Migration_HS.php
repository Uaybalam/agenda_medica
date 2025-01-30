<?php

error_reporting(E_ALL);

ini_set('memory_limit', '512M');	//# response memory limit.
ini_set('display_errors', 1);		//# show all errors.
ini_set('max_execution_time', 600); //# 6 minutes max time execution.

class Migration_HS
{	

	public $column_names = null;

	/**
	 * log
	 */
	public $_log = [];

	/**
	 * valid key code
	 */
	public $key_code = '';


	/**
	 * folder files
	 */
	public $folders = [];

	public $expected_count = 1;

	private $CI;	
	
	private $time_start = 0;

	function __construct( $null_data = null )
	{	
		$this->time_start = new DateTime();
		
		$this->folders = [	

			'clean_csv' =>  FCPATH.'../migration/clean_csv/',

			'initial_csv' => FCPATH.'../migration/initial_csv/',
			
			'not_inserted' => FCPATH.'../migration/not_inserted/',
			
			'relation' => FCPATH.'../migration/relations/',

			'settings' =>  FCPATH.'../migration/settings/'
		];
		
		$this->CI =&get_instance();
	}

	function key_code_generate()
	{
		
		$this->key_code  =  uniqid();
		
		$this->CI->session->set_flashdata(  'key_code' , $this->key_code);
		
		return $this->key_code;
	}

	function key_code_valid( $key_code )
	{
		
		$tmp_code = $this->CI->session->flashdata('key_code');
			
		if(is_null($tmp_code) || $tmp_code != $key_code )
		{	
			$this->jsonError( "Code {$key_code} not valid" );
		}
		else
		{	
			return true;
		}
	}
	
	/**
	 * fopen php
	 */
	public function open( $type , $filename = '' , $typeOpen = 'w' )
	{
		if(!isset($this->folders[$type]) )
		{	
			return false;
		}
		
		if( $type === 'clean_csv' || $type === 'not_inserted')
		{
			//open file to write
			if(!file_exists( $this->full_path( $type, $filename ) ) )
			{
				$fileNull = fopen( $this->full_path( $type, $filename ) , "w");
				fwrite($fileNull, "");
				fclose($fileNull);
			}
			else
			{
				$fileNull = fopen($this->full_path( $type, $filename ), "r+");
				// clear content to 0 bits
				ftruncate($fileNull, 0);
				//close file
				fclose($fileNull);
			}
			
			$file = fopen($this->full_path( $type, $filename ), $typeOpen );
		}
		else
		{
			$file = @fopen( $this->full_path( $type, $filename ), $typeOpen );
		}
		
		if($file)
		{		
			@chmod($this->full_path( $type, $filename ) ,0777);
			return $file;
		}
		else
		{		
			$this->jsonError( " File name [".$filename."] can not be opened (Review permissions)");	
		}
	}
	
	/**
	 * fopen php
	 */
	public function getArrayFile( $type , $filename = ''  )
	{
		if(!isset($this->folders[$type]) )
		{	
			$this->jsonError("Type folder open not found");	
		}

		$fileFullPath =  $this->full_path( $type, $filename );
		
		if(!$fileFullPath)
		{		
			$this->jsonError(" File name [".$filename."] can not be opened (Review permissions)");	
		}
		
		if(file_exists($fileFullPath))
		{
			$D = @include( $fileFullPath);
			
			return (is_array($D)) ? $D : [];
		}
		else
		{
			$this->jsonError(" File name [".$filename."] can not be include or is not array \n [$fileFullPath]");	
		}	
	}
	
	/**
	 * get full path
	 */
	public function full_path( $type, $filename)
	{
		if(!isset($this->folders[$type]) )
		{	
			return false;
		}
		return $this->folders[$type].$filename;
	}

	/**
	 * @stop process
	 */
	public function jsonError( $message )
	{
		$this->CI->template->json([
			'status' => 0,
			'message' => $message,
			'log' => implode("\n", $this->_log)
		], 0 );
	}

	/**
	 * @stop success
	 */
	function jsonSuccess()
	{
		$diff 		  = $this->time_start->diff( new DateTime() );
		
		$this->_log[] =  $diff->format('<b>___Minutes [%I] ____Seconds [%S]</b>');
		
		$this->CI->template->json([
			'status' => 1,
			'key_code' => $this->key_code_generate(),
			'log' =>implode("\n", $this->_log)
		], 0 );	
	}
	
	
	function cleanString( $string )
	{	
		
		$string 	= preg_replace('/[\t\n\r\0\x0B]/', '', trim( $string ) );
		
		$string 	= str_replace("\\", '/', $string );
		
		return preg_replace('/([\s])\1+/', ' ', $string);
	}
	
	function cleanDateTime( $dateStr )
	{
		$explodeDate = explode('/', trim($dateStr) );
		if(count($explodeDate) != 3 )
		{	
			$explodeDate = explode("-", $dateStr);
		}
		
		if( count( $explodeDate) != 3)
		{
			return '';
		}
		

		$month = str_pad($explodeDate[0],2, "0", STR_PAD_LEFT); 
		$day   = str_pad($explodeDate[1],2, "0", STR_PAD_LEFT); 
		$year  = str_pad($explodeDate[2],4, "0", STR_PAD_LEFT);

		return "$year-$month-$day";
	}

	function cleanDate( $dateStr )
	{

		$explodeDate = explode('-', trim($dateStr) );
		if(count($explodeDate) != 3 )
		{
			$explodeDate = explode("/", $dateStr);
		}

		if( count( $explodeDate) != 3)
		{
			return '';
		}
		
		$month = str_pad($explodeDate[0],2, "0", STR_PAD_LEFT); 
		$day   = str_pad($explodeDate[1],2, "0", STR_PAD_LEFT); 
		$year  = str_pad($explodeDate[2],4, "0", STR_PAD_LEFT);
		
		if (!@checkdate($month, $day, $year ) )
		{
            return '';
		}
		$parse = @date_parse("$month/$day/$year");
		if($parse['error_count']>0)
		{
			return '';
		}
		
		return "$month/$day/$year";
	}

	function cleanPhone( $phoneString )
	{
		$phone = str_replace(["-","(",")"," "],"", trim($phoneString) );
		return $phone;
	}

	function getData( $bufferString )
	{
		$data 	= str_getcsv( trim($bufferString) );

		if($this->expected_count != count($data))
		{	
			$str  = substr( trim($bufferString),1 , strlen(trim($bufferString)) - 2  );
			$data = explode("\",\"", $str );

			if($this->expected_count != count($data))
			{
				$this->jsonError("Columns expected not become completed.. step 2");	
			}
		}
		
		return array_combine( $this->column_names, $data );
	}
	
	function importData( $path = "", $columns = array(), $table = "" , $extraCommands = "" )
	{
		$escapeColumnName = [];
		foreach ($columns as $value) {
			$escapeColumnName[] = "`".$value."`";
		}

		$sql = "LOAD DATA LOCAL INFILE '".$path."' INTO TABLE ".$table
			." FIELDS TERMINATED BY ',' ENCLOSED BY '\"'"
			." LINES TERMINATED BY '\\n'"
			." ".$extraCommands
			."(".implode(',', $escapeColumnName).");";
			
		try
        {	
			
			$extra_params      = [];
        	if( defined('PDO::MYSQL_ATTR_LOCAL_INFILE') )
			{		
				$extra_params[PDO::MYSQL_ATTR_LOCAL_INFILE] = true;
			}
			
			if( defined('PDO::MYSQL_ATTR_READ_DEFAULT_GROUP') )
			{		
				$extra_params[PDO::MYSQL_ATTR_READ_DEFAULT_GROUP] = 'client';
			}
			
            $connect_local = new PDO("mysql:host={$this->CI->db->hostname};dbname={$this->CI->db->database}", 
            		$this->CI->db->username,
            		$this->CI->db->password,
            		$extra_params
            	);	
            
            $connect_local->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           	
    		$connect_local->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

           	$affected = $connect_local->exec( $sql );

           	return $affected;
        }	
        catch(PDOException $e) 
        {
        	$extra_message = "\n <<Review /etc/mysql/my.cnf [client] local-infile = 1 >>";
            $this->jsonError( $e->getMessage().
            	"<p>***SQL***</p> ".
            	$sql .
            	"<p>***PARAMS***</p><pre> ". 
            	print_r($extra_params , 1)."</pre>",
            	"<p>***Check Modules***</p>". 
            	"mysqlnd and PDO"
            );
        }
	}

	function setColumnNames( $bufferString , $validColumnsRequired = array() )
	{
		$this->column_names   = str_getcsv( trim($bufferString) );
		$this->expected_count = count( $this->column_names );

		$error_columns = [];

		foreach ($validColumnsRequired as $column) {
			if(!in_array($column,$this->column_names))
			{	
				$error_columns[] = $column;
				//$this->jsonError("Columns required not found [$column] ");	
			}
		}

		if(count($error_columns))
		{
			$this->jsonError("Columns required not found <br>".implode("<br>",$error_columns));	
		}

		return true;
	}
	
	function saveRelationFile( $nameFile , $dataRelation, $integerKey = FALSE )
	{
		$content = "<?php \nreturn [\n";
		foreach ($dataRelation as $key => $value ) {	
			if($integerKey)
				$content.="\t".$key." => ".$value.",\n";
			else
				$content.="\t'".$key."' => ".$value.",\n";
		}
		$content.="];";
		
		if( !$fileRelations  = $this->open( 'relation', $nameFile ) )
		{
			$this->jsonError("File [relations] ".$nameFile." can`t be read or open \n");
		}

		fwrite($fileRelations, $content );
		fclose($fileRelations);
	} 

	function valueYesNo( $option_value , $return_value_avoid = TRUE)
	{
		$option_value = (string)trim($option_value);

		if($option_value === '')
		{	
			if($return_value_avoid)
				return '';
			else
				return 'No';
		}
		
		if(in_array($option_value[0], ['N','n','0']))
			return 'No';

		return 'Yes';
	}
	
	function reset_data()
	{
		/**
		 * reset data
		 */
		$truncate_sql = [
			"TRUNCATE TABLE custom_setting",//completed

			"TRUNCATE TABLE appointment",//completed
			"TRUNCATE TABLE billing",//completed
			"TRUNCATE TABLE billing_detail",//completed
			"TRUNCATE TABLE checked_out",//completed
			"TRUNCATE TABLE encounter",//completed
			"TRUNCATE TABLE encounter_activity",//not_required
			"TRUNCATE TABLE encounter_addendum",//completed
			"TRUNCATE TABLE encounter_child", //completed except development***********
			"TRUNCATE TABLE encounter_diagnosis",//completed
			"TRUNCATE TABLE encounter_results",//completed
			"TRUNCATE TABLE encounter_medication",//completed
			"TRUNCATE TABLE encounter_physicalexam",
			"TRUNCATE TABLE encounter_referrals",//completed
			"TRUNCATE TABLE encounter_invoice",//completed
			"TRUNCATE TABLE examinations",//completed
			"TRUNCATE TABLE patient",//completed
			"TRUNCATE TABLE patient_communication",//completed
			"TRUNCATE TABLE patient_contact",//completed
			"TRUNCATE TABLE patient_history",//completed
			"TRUNCATE TABLE patient_history_active",//completed
			"TRUNCATE TABLE patient_related_files",//completed
			"TRUNCATE TABLE patient_tuberculosis",//completed
			"TRUNCATE TABLE patient_vaccines",//completed
			"TRUNCATE TABLE patient_warnings",//completed
			"TRUNCATE TABLE appointment_event",//completed
			"DELETE FROM user WHERE id not in (1, 6, 11, 13, 29, 42, 44, 46 )"
		];
		//
		// id 1 = jonathanq
		//DELET FROM USER MUST BE CHANGE IN NEW INSTALLATION
		
		foreach ($truncate_sql as $Q ) {
			$this->CI->db->query( $Q );
		}
	}

}
