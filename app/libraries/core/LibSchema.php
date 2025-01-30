<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
			
class LibSchema
{

	protected $CI;
		
	private $_error          = array();
	
	private $_access         = FALSE;
	
	private $_success        = array();
	
	private $_index_keys     = array();
	
	protected $config_schema = null; 

	public function __construct( $config = array() ){

		$this->CI =& get_instance();

		$this->config_schema = $config;

		$this->CI->load->dbforge();		
		
		
		$this->CI->db->db_debug = FALSE;
						
		$schema_table = $this->_item('schema_table');

		if($schema_table == ''){
			$this->_error[] = 'Schema table not asigned';
		}else if ( ! $this->CI->db->table_exists($schema_table)){	
			$this->CI->dbforge->add_field(array(				
				'name'    => array('type' => 'varchar', 'constraint' => 100),
				'user'    => array('type' => 'varchar', 'constraint' => 100),
				'last_modify' => array('type' => 'int', 'constraint' => 11),
				'date'    => array('type' => 'datetime', 'notnull' => FALSE, 'default' => '0000-00-00'),
			));	
			$this->CI->dbforge->create_table( $schema_table );
		}
	}		

	/**
	 * Run version
	 * 
	 * @return complete 
	 */
	public function runMigration( $name  ){
		
		$schemas = $this->getSchemas();
		if(!isset($schemas[$name])){
			$this->_error[] = 'File not found, check folder schemas';
			return false;
		} else{		
			return $this->_run_file( $schemas[$name], $name);
		}

	}

	/**
	 * Path schema
	 * 
	 * @return complete 
	 */		
	public function getPathSchema(){
		return $this->_item('schema_path');
	}	


	/**
     * Login
     *
     * I can acces to config schema
     * 
     * @return boolean
     */
	public function login( $usu, $pass){
		
		$sessionVar = $this->_item('schema_session_var') ? $this->_item('schema_session_var') : 'session_schema';
		$dataUsers  = $this->_item('schema_session_users');
		//die( PR( $this->config_schema) );
		if( isset( $dataUsers[$usu] ) ) {
		
			if($dataUsers[$usu] === $pass) {	
			
				$data[$sessionVar]	    = $usu;
 				$this->CI->session->set_userdata($data);
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
     * Logout
     *
     * Close session var
     * 
     * @return void
     */	
	public function logout(){
		$sessionVar   	   = $this->_item('schema_session_var') ? $this->_item('schema_session_var') : 'session_schema';
		
		if($sessionVar!=''){
			$data[$sessionVar] = '';
			$this->CI->session->set_userdata($data);	
		}
	}

	/**
     * Exist login
     *
     * Get user if exist login
     * 
     * @return string (user)
     */
	public function isLogged(){
		$sessionVar = $this->_item('schema_session_var') ? $this->_item('schema_session_var')  : 'session_schema';
		$dataUsers  = $this->_item('schema_session_users');

		if(isset($this->CI->session->userdata[$sessionVar])){

			$usu = $this->CI->session->userdata[$sessionVar];
			
			if( isset( $dataUsers[$usu] ) ){
				return $usu;
			}
		}
		return FALSE;
	}

	/**
	 * Get schemas db
	 *
	 *  Mi list tables migrated
	 *
	 * @return string
	 */	
	public function getSchemasMigrated(){
		$this->CI->db->order_by('date','desc');
				
		return $this->CI->db
			->get( $this->_item('schema_table') )
			->result();
	}	

	/**
     * get Pending schemass
     *
     * List pending schemas use in the view schema
     * 
     * @return array [version][namefile]
     */	
	public function getSchemasPending(){
		$list_schemas 	 		= $this->getSchemas();
		$list_scehmas_migrated 	= $this->getSchemasMigrated();
		$pending_schemas = array();

		foreach ($list_schemas as $name => $file) {
			if(!$this->_get_schema_exec($name)){
				$pending_schemas[$name] = $file;
			}
		}	
		return $pending_schemas;
	}

	/**
     * Find schema database
     *
     * Get all list off schema
     * 
     * @return array [version][namefile]
     */	
	public function getSchemas(){
		$schemas = array();

		// Load all *_*.yml files in the migrations path
		foreach (glob($this->_item('schema_path').'*.json') as $file)
		{
			$name = basename($file, '.json');
			$schemas[$name] = $file; 	
		}		

		ksort($schemas);
		return $schemas;
	}

	/**
     * Find schema database
     *
     * Get all list off schema
     * 
     * @return array [version][namefile]
     */	
	public function getSchemasLastModify(){
		$schemas = array();
		
		// Load all *_*.yml files in the migrations path
		foreach (glob($this->_item('schema_path').'*.json') as $file)
		{	
			$name           = basename($file, '.json');
			$schemas[$name] = filemtime($file); 	
		}		

		ksort($schemas);
		return $schemas;
	}


	/**
	 * Get message erros
	 *
	 *  exist message errors?
	 *
	 * @return string
	 */
	public function getError(){

		return $this->_error;
	}

	/**
	 * Get message success
	 *
	 * All message correct
	 *
	 * @return string
	 */
	public function getSuccess(){
		$html = '';
		foreach ($this->_success as $value) {
			$html.= "<p>".$value."</p>";
		}
		return $html;
	}
	
	public function getTemplate()
	{	
		return $this->_item('schema_template');
	}

	public function getPath()
	{		
		return $this->_item('schema_path');
	} 
		
	private function _run_file( $file , $nameSchema ){
			
		$content    = file_get_contents( $file );
		$json_lines = str_replace(array("\t", "\r","\n"),"", $content);
		$tables     = @json_decode( $json_lines , true );
		
		switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            $this->_error[] = ' - No errors';
	        break;
	        case JSON_ERROR_DEPTH:
	            $this->_error[] = ' - Maximum stack depth exceeded';
	            return false;
	        break;
	        case JSON_ERROR_STATE_MISMATCH:
	            $this->_error[] = ' - Underflow or the modes mismatch';
	            return false;
	        break;
	        case JSON_ERROR_CTRL_CHAR:
	            $this->_error[] = ' - Unexpected control character found';
	        	return false;
	        break;
	        case JSON_ERROR_SYNTAX:
	            $this->_error[] = ' - Syntax error, malformed JSON';
	        	return false;
	        break;
	        case JSON_ERROR_UTF8:
	            $this->_error[] = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
	        	return false;
	        break;
	        default:
	            $this->_error[] = ' - Unknown error';
	        	return false;
	        break;
	    }

		$list_tables_bd = $this->CI->db->list_tables();
			
		foreach ($tables as $table =>  $fields) {
			
			if(!$table || trim($table)=='' || $table===0)
			{	
				$this->_error[] = "Schema JSON DB <b>it contains the table name</b>";
				return false;
			}

			if(!is_array($fields))
			{	
				$this->_error[] = "Schema JSON DB it contains any fields/columns from table <b>".$table."</b>";
				return false;
			}
			
			if(!in_array( $table, $list_tables_bd )){
				$new_table 	  = true;
				$list_fields  = Array();
			}else{		
				$new_table 	  = false;
				$list_fields  = $this->CI->db->list_fields( $table ); 
			}
			/**
			 * set null vars attrs
			 */
			$modify_column  = $add_column = $add_field =  $primary_key = $index_keys = null;

			foreach ($fields as $attr) 
			{		
				//$this->_error[] = "<pre>".print_r($attr, 1)."</pre>";
				//return false;
				if(!isset($attr['name']))
				{		
					$this->_error[] = 'Required name field in attr';
					return false;
				}

				$name_field   = $attr['name'];
				$primary      = isset( $attr['primary'] ) ? $attr['primary'] : false;
				$_createindex = isset( $attr['_createindex'] ) ? $attr['_createindex'] : false;
				
				unset($attr['name']);
				unset($attr['_createindex']);

				if( !isset($attr['null']) )
				{
					$attr['null'] = false;
				}

				if($new_table){
					$add_field[$name_field] = $attr;
				}else if(in_array($name_field, $list_fields)){
					$modify_column[$name_field] = $attr;	
				} else{			
					$add_column[$name_field] 	= $attr;
				}	
				
				if( $primary){			
					if(!$new_table){
						$primary_key[] = $name_field;
					}else{	
						$this->CI->dbforge->add_key( $name_field, TRUE );
					}
				}			
				if( $_createindex ){		
					$index_keys[] 	=  $name_field;
					$add_key_forge 	=  $this->CI->dbforge->add_key( $name_field, FALSE );
				}	

			}

			if($new_table)
			{	
				if(!count($this->CI->dbforge->primary_keys)){		
					$this->_error[] = 'Must primary key is required while create table';
					return false;		
				}


				$this->CI->dbforge->add_field($add_field);

				$this->CI->dbforge->create_table( $table );

				if(  $err = $this->_error_db()){
					$this->_error[] = $err;
					return false;	
				}else{
					$this->_success[] = 'dbforge create_table '.$table.' correct';
				}
			} 
				else
			{	
				if(is_array($primary_key) && count($primary_key)){
					if(!$this->_add_primary_key($table, $primary_key)){
						return false;	
					}else{
						$this->_success[] = 'transact-sql primary key was added '.$table;
					}
				}
				if(is_array($add_column) && count($add_column)){
					$this->CI->dbforge->add_column( $table, $add_column  );
					if($err = $this->_error_db()){
						$this->_error[] = $err;
						return false;	
					}else{
						$this->_success[] = 'dbforge add_column '.$table.' correct';
					}
				}	
				if(is_array($index_keys) && count($index_keys)){			
					if(!$this->_add_index_keys( $table, $index_keys )){
						return false;
					}	
				}	
				if(is_array($modify_column) && count($modify_column)){
					$this->CI->dbforge->modify_column( $table, $modify_column );
					if( $err = $this->_error_db()){
						$this->_error[] = $err;
						return false;	
					}else{
						$this->_success[] = 'dbforge modify_column '.$table.' correct';
					}
				}
				
			}
		}

		$schema_log['user']        = $this->isLogged();
		$schema_log['date']        = date('Y-m-d H:i:s');
		$schema_log['name']        = $nameSchema; 
		$schema_log['last_modify'] = filemtime($file); 

		if($this->_get_schema_exec($nameSchema)){	
			$this->CI->db->update($this->_item('schema_table'), $schema_log, array('name' => $nameSchema) );
		}else{
			$this->CI->db->insert($this->_item('schema_table'),$schema_log);
		}	
			
		return $schema_log;	
	}
	
	private function _get_schema_exec( $name ){
		return $this->CI->db
					->get_where( $this->_item('schema_table') , 
							[ 'name' => trim($name) ] )->row();
	}	

	private function _error_db(){
		$msg_error = $this->CI->db->error();
		if($msg_error['message']!=''){
			return $msg_error['message'];
		}	
		return false;
	}

	private function _add_primary_key( $table, $keys){
		$sql 	= " ALTER TABLE ".$table." DROP PRIMARY KEY, ADD PRIMARY KEY(".implode(",",$keys).") ";
		$result = $this->CI->db->query($sql);	
		if( $err = $this->_error_db()){
			$this->_error[] = $err;
			return false;
		}	
		return true;
	}

	private function _add_index_keys($table, $keys )
	{			
		$current_index_keys = $this->_get_index_keys( $table );
			
		$sqls = [];
		foreach ($keys as $value) {	

			$name = $this->CI->db->escape_identifiers('i_'.$table.'_'.$value);
				
			if(in_array(str_replace("`","", $name), $current_index_keys ))
			{		
				continue;
			}
			$sql  = 'CREATE INDEX '.$name
				.' ON '.$this->CI->db->escape_identifiers($table)
				.' ('.$value.');';

			$result = $this->CI->db->query($sql);			
			if( $err = $this->_error_db()){
				$this->_error[] = $err;
				return false;
			}	
			else{
				$this->_success[] = 'Index key was added: '.str_replace("`","", $name);
			}
		}
		return true;
		
	} 

	private function _get_index_keys( $table )
	{	
		$current_index_keys = [];
		$sql   = "SHOW INDEX FROM ".$table;
		$query = $this->CI->db->query($sql);
		foreach ($query->result() as $row)
		{
		  	$current_index_keys[] = $row->Key_name;
		}	
		return $current_index_keys;
	}

	private function _item( $name )
	{
		if( isset($this->config_schema[$name]))
		{
			return $this->config_schema[$name];
		}
		else
		{
			return '';
		}
	}

}	