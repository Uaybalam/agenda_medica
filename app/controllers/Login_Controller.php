<?php
use Vectorface\GoogleAuthenticator;
/**
* @route:login
*/
class Login_Controller extends CI_Controller
{		
	private $_max_attempts = 10;

	private $_wait_minutes = 5;

	protected $install = [
		'nick_name' => 'admin',
		'email'     => 'eduardo@greenshieldtech.com',
		'password'  => 'admin',
		'names' 	=> 'admin',
		'last_name'	=> 'admin' 
	];

	function __construct()
	{
		
		parent::__construct();
		
		$moduleErrors = [];

		if(!extension_loaded('mysqlnd'))
		{
			$moduleErrors[] = "Module [mysqlnd] is required php7.0-mysqlnd";
		}
		/*
		if(!extension_loaded('memcached'))
		{
			//$moduleErrors[] = "Module [memcached] is required php7.0-memcached";
		}
		*/
		/*
		if(!extension_loaded("xml"))
		{
			$moduleErrors[] = "Module [xml] is required php7.0-xml";
		}*/	
		/*if(!extension_loaded("imagick"))
		{
			$moduleErrors[] = "Module [imagick] is required php7.0-imagick";
		}*/
		
		if(count($moduleErrors) > 0 )
		{	
			show_error(implode("<br>",$moduleErrors) , 500 , "Modules not found");
		}

		if(count( $this->db->list_tables() ) <= 1)
		{
			//go to install tables
			redirect('/app-admin/schema/index');
		}


	}

	/**
	 * @route:home
	 */
	function home()
	{
		if( !$this->User_DB->getRowBy(['nick_name' => $this->install['nick_name'] ])  )
		{
			redirect('/login/install');
		}

		if(@$this->session->userdata['User_DB'])
		{	
			redirect('/appointment/book');
		}
		$this->template
			->layout('public','**HealthService**')
			//]->js('jcryption.3.1.0','/assets/vendor/jquery/')
			//->js('login/home')
			->render('login/view.home', [
					'nick_name' => $this->input->get('nick_name')
				] );
	}

	/**
	 * @route:intent
	 */
	function intent()
	{

		if(@$this->session->userdata['User_DB'])
		{	
			redirect('/appointment/book');
		}
		
		
		$this->validate_attempts_connection( $this->input->post('nick_name') );

		$user = $this->User_DB->getRowBy([
			'nick_name' => $this->input->post('nick_name')
		]);

		if( !$user )
		{	
			$this->notify->error( 'Usario o contraseña invalido' );
		}
		else if( (int)$user->status === 0 )
		{	
			$this->notify->error( 'Usuario desactivado, por favor contacta al administrador.' );
		}
		else if(!password_verify( $this->input->post('password') ,  $user->password   ) )
		{
			$this->notify->error( 'Usario o contraseña invalido' );
		}
		else
		{
			$this->session->set_userdata(['User_DB' => $user ]);
			
			\libraries\Administration::install();
			
			$this->removeSessionFiles();
			
			//##Appointment
			$this->db->select('id')
				->from('appointment')
				->where([
					'status' => 1,
					"DATE_FORMAT(date_appointment,'%Y%m%d') < " => date('Ymd')]
				);
			
			if ($appointments = $this->db->get()->result() )
			{
				foreach ($appointments as $appt) {
					
					$this->db->where('id',$appt->id )->update('appointment', ['status' => 9 ]);
					
					$this->db->insert('appointment_event', [
						'appointment_id' => $appt->id,
						'event'          => 'not_show',
						'notes'          => 'Automáticamente después de 1 día',
						'date'           => date('Y-m-d H:i'),
						'user'           => 'by_app'
					]);
				}
			}
			//##Appointment

			$this->db
				->where([
					'status' => 1,
					"DATE_FORMAT(date_appointment,'%Y%m%d') < " => date('Ymd')
				])
				->update('appointment', [ 'status' => 9 ]);
			
			$timezone = trim($this->input->post('input_timezone'));
			
			if( !in_array($timezone, timezone_identifiers_list() ) )
			{
				$timezone = "America/Los_Angeles";
			}

			$this->session->set_userdata(['Guest_TimeZone' => $timezone ]);
			//$includePIN = '';
			
			if($user->secret2fa && $user->active2fa)
			{
				redirect('/login/verifyCodeAuth');	
			}

			if( (int)$user->status === 2)
			{	
				redirect('/user/profile?new=1');
			}
			
			redirect('/appointment/book');	
		}
		
		$Data = [
			'nick_name' => $this->input->post('nick_name')
		];
		
		redirect('/login/home/?'. http_build_query( $Data));
	}

	/**
	 * @route:close
	 */
	function close()
	{
		$this->session->unset_userdata('User_DB');
		$this->session->unset_userdata('verfied');
		$this->notify->success('End session');
        redirect('/');
	}


	
	/**
	 * @route:install
	 */
	function install()
	{
		
		//create user
		if( !$this->User_DB->getRowBy(['nick_name' => $this->install['nick_name'] ]) )
		{
			$this->User_DB->nick_name     = $this->install['nick_name'];
			$this->User_DB->email         = $this->install['email'];
			$this->User_DB->password      = password_hash(  $this->install['password'] , PASSWORD_BCRYPT );
			$this->User_DB->names 		  = $this->install['names'];
			$this->User_DB->last_name 	  = $this->install['last_name'];
			$this->User_DB->access_type   = 'root'; 
			
			/**
			 * this user is active
			 */
			$this->User_DB->status        = 1;

			$this->User_DB->save();
			
			$folderUploads = FCPATH . '../private/uploads';

			if(!file_exists($folderUploads))
			{
				mkdir( $folderUploads );
				mkdir( $folderUploads.'/patients' );
			}
			
			@chmod( $folderUploads , 0777 );
			@chmod( $folderUploads.'/patients' , 0777 );
			
			//$this->notify->success('Admin ['.$this->install["nick_name"].'] was installed ZipCodes (' . $result . ')');
		}
		else
		{
			show_error('Pagina no encontrada**', 500 );
		}

		redirect('/');	
	}

	/**
	 * @route:verifyCodeAuth
	 */
	function verifyCodeAuth()
	{  
		if(!$this->session->userdata['User_DB'])
		{
			redirect('/');	
		}

		if($this->session->verfied)
		{
			redirect('/');	
		}

		$user = $this->db->select([
				'secret2fa',
				'active2fa', 
			])
			->where([ 'id' => $this->session->userdata['User_DB']->id ])
			->get('user')
		    ->row();
		    
		if(!$user->active2fa)
		{
			redirect('/');	
		}

		$this->template
			->layout('public','**HealthService**') 
			->js('login/verify')
			->render('login/view.verify');
	}

	/**
	 * @route:verify2fa
	 */
	function verify2fa()
	{
		require_once dirname(__FILE__).'/../libraries/google2fa/vendor/autoload.php';

		$ga          = new GoogleAuthenticator();
		$checkResult = $ga->verifyCode($this->session->userdata['User_DB']->secret2fa, $this->input->post('code'));  

		if($checkResult)
		{
			$_SESSION['verfied'] = 1;
		}
		else
		{
			$_SESSION['verfied'] = 0;
		}

		echo json_encode(["status" => $checkResult]);
	}

	/**
	 * @route:verifyLogin
	 */
	function verifyLogin()
	{   
		echo json_encode(["status" => !isset($this->session->userdata['User_DB'])]);
	}
	
	public function removeSessionFiles()
	{
		
		$sessionPath = BASEPATH.'../private/storage/sessions/';
		$lastWeek    = time() - (7 * 24 * 60 * 60);

		// Load all *_*.yml files in the migrations path
		foreach (scandir( $sessionPath ) as $file)
		{	
			if(!is_file($sessionPath . $file))
			{
				continue;
			}
			
			$fileLastModify = filemtime( $sessionPath . $file); 
			
			if($lastWeek >= $fileLastModify)
			{
				@unlink($sessionPath . $file);
			}
		}	
	}


	private function validate_attempts_connection( $user )
    {
        $connection = $this->db->from("login_attempts")->where([
            'ip' => $this->input->ip_address(),
            'user' => $user 
        ])->get()->row_array();
        $currentDate  = new \DateTime();
        if(!$connection)
        {
            $this->db->insert('login_attempts', [
                'ip' => $this->input->ip_address(),
                'user' => $user,
                'updated_at' => $currentDate->format('Y-m-d H:i:s'),
                'attempts' => 1
            ] );

            return true;
        }
        
		$attemptsDate = new \DateTime($connection['updated_at']);
		
		$attemptsDate->add(new \DateInterval('PT' . $this->_wait_minutes . 'M'));
		if( $currentDate <= $attemptsDate )
		{
			$connection['attempts']++;

			if($connection['attempts'] >= $this->_max_attempts)
			{
				$message = sprintf('Por favor, espera un máximo de %s minutos para intentar de nuevo', $this->_wait_minutes);

                $this->notify->error( $message );

                $interval = $attemptsDate->diff( $currentDate );

                $Data = [
					'nick_name' => $user,
					'minutes' => $interval->i,
					'seconds' => $interval->s,
				];

				redirect('/login/home/?'. http_build_query( $Data));

                exit;
			}
			else
			{
				$this->db->where( ['id' => $connection['id'] ] )->update('login_attempts', [
		            'attempts' => $connection['attempts'],
		            'updated_at' => $currentDate->format('Y-m-d H:i:s'),
		        ]);
			}
		}
		else
		{
			$this->remove_attempts_connection( $user );
		}

        return true;
    }
    
    private function remove_attempts_connection( $user )
    {
        $this->db->delete('login_attempts', [
            'ip' => $this->input->ip_address(),
            'user' => $user
        ]);
    }


}
