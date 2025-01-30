<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @route:app-admin/schema
 */
class Schema_Controller extends CI_Controller
{		
	function __construct()
	{		

		parent::__construct();
			
		$this->load->helper(['url', 'form']);	
		$this->load->library('session');
		$this->load->database();
		$this->load->library( 'core/LibSchema' );
			
	}

	/**
	 * @route:index
	 */
	public function index(){
				
		if( $var['user'] = $this->libschema->isLogged()){
			$this->dashboard();
		}else{
			$this->load->view('schema/view-login-schema');
		}
		
	}	

	/**
	 * @route:login
	 */
	public function login(){
		
		$can_login = $this->libschema->login( 
						$this->input->post('user'),  
						$this->input->post('password')
					);

		if ( $can_login  ){
			redirect('/app-admin/schema/dashboard/');
		}else{
			$var['content'] = 'User or password not valid';	
			$this->load->view('schema/view-login-schema' , $var);
		}
	}	

	/**
	 * @route:dashboard
	 */
	public function dashboard(){	
			
		if( $var['user'] = $this->libschema->isLogged()){	
			$var['list_schemas']          = $this->libschema->getSchemasPending(); 
			$var['list_schemas_migrated'] = $this->libschema->getSchemasMigrated(); 
			$var['last_modify']           = $this->libschema->getSchemasLastModify();
			$var['database']              = $this->db->database;
			$var['username']              = $this->db->username;
			//die('<pre>'.print_r($var,1).'</pre>');
			$this->load->view('schema/view-dashboard-schema', $var);		
		}else{
			redirect('/app-admin/schema/');
		}
	}

	/**
	 * @route:runmigration
	 */
	public function runmigration(){
		
		if($this->libschema->isLogged()){
			if ( $response = $this->libschema->runMigration( $this->input->get('name')  )){
				echo json_encode( [	'status' => 1, 
									'schema_log' => $response,
									'message_success' => $this->libschema->getSuccess() ]);	
			}else{	
				echo json_encode( ['status' => 0, 
						'msg' => $this->libschema->getError(),
						'message_success' =>  $this->libschema->getSuccess()
					]);		
			}
			
		}
	}	

	/**
	 * @route:logout
	 */
	public function logout(){	
		$this->libschema->logout();
		redirect('/app-admin/schema/');
	}

}

/* End of file 'Schema' */
/* Location: ./application/controllers/Schema.php */