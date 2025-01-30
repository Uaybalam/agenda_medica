<?php
use Vectorface\GoogleAuthenticator;
/**
 * @route:user/manager
 */
class User_Manager_Controller extends APP_User_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->validate_access(['root','admin']);
	}

	/**
	 * @route:{get}init
	 */
	function init()
	{
		$response = [
			'data_users'  =>  $this->User_DB->get_list(),
			'access_type_avalible' => $this->User_DB->access_type_avalible ,
			'catalog_status' => [
				0 => 'Deshabilitado', 
				1 => 'Activo', 
				2 => 'Pendiente de activación' 
			]
		];
		
		$this->template->json( $response );
	}

	/**
	 * @route:{get}index
	 */
	public function index()
	{
		$this->template
			->set_title('User manager')
			->body([
				'ng-app' => 'app_manager',
				'ng-controller' => 'ctrl_manager',
				'ng-init' => 'initialize()'
			])
			->modal('user/manager/modal.user.create', ['title' => 'Completar formulario'])
			->js('user/manager.index')
			->render('user/manager/view.panel.index');
	}

	/**
	 * @route:{get}(:num)
	 */
	public function detail( $ID )
	{

		$user = $this->db->select([
				'id',
			])
			->from('user')
			->where(['id' => $ID ])
			->get()->row();

		if(!$user)
		{
			show_error('Usuario no encontrado');
		}

		$this->template
			->set_title('User detail')
			->body([
				'ng-app' => 'app_manager_detail',
				'ng-controller' => 'ctrl_manager_detail',
				'ng-init' => "initialize({$ID})"
			])
			->modal('user/manager/modal.edit.address', ['title' => 'Editar dirección del usuario'] )
			->modal('user/manager/modal.edit.primarycontact', ['title' => 'Editar contacto primario'])
			->modal('user/manager/modal.edit.secondarycontact', ['title' => 'Editar contacto secundario'])
			->modal('user/manager/modal.edit.doctorcontact', ['title' => 'Edit address user'])
			->modal('user/manager/modal.edit.basic', ['title' => 'Editar información basica'])
			->js('user/manager.detail')
			->render('user/manager/view.content.detail');
	}
	
	/**
	 * @route:{get}(:num)/init
	 */
	public function detail_init( $ID )
	{
		if( !$user = $this->User_DB->get( $ID ) )
		{	
			show_error('Usuario no encontrado');
		}

		$user->qrCodeUrl = "";

		if($user->secret2fa && $user->active2fa)
		{
			require_once dirname(__FILE__).'/../../libraries/google2fa/vendor/autoload.php';

			$ga              = new GoogleAuthenticator();
			$user->qrCodeUrl = $ga->getQRCodeUrl($user->email, $user->secret2fa);
		}
		
		$this->template->json( [
			'user' => $user,
			'access_type_avalible' => $this->User_DB->access_type_avalible ,
		]);
	}

	/**
	 * @route:{post}create
	 */
	public function create()
	{
		$response['status'] = 0;
		
		$this->form_validation
			->set_rules('nick_name', 'Usuario', 'trim|required|xss_clean|alpha_dash|is_unique[user.nick_name]')
			->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]')
			->set_rules('password', 'Contraseña', 'trim|required|min_length[4]')
		;

		$this->_basic_validation();
		
		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string(); 
		}
		else if( $this->input->post('nick_name') === 'by_app' )
		{
			$response['message'] = 'by_app is a reserve word';
		}
		else
		{		
				
			$this->User_DB->nick_name = $this->input->post('nick_name');
			$this->User_DB->password  = password_hash( $this->input->post('password') , PASSWORD_BCRYPT );
			$this->User_DB->status    = 2;
			

			if($this->input->post('access_type') == "root")
			{
				$this->User_DB->instance_id = $this->_getInstance();
			}
			else
			{
				if($this->input->post('new_instance') && $this->input->post('access_type') == "admin")
				{ 
					$this->User_DB->instance_id = $this->_getInstance();
				}
				else
				{
					$this->User_DB->instance_id = $_SESSION['User_DB']->instance_id;
				}
			}

			$response = [
				'status' => 1,
				'user_id' => $this->_basic_save(0)
			];

			$this->notify->success('Usuario agregado');
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{post}update/(:num)/(basic|address|primarycontact|secondarycontact|doctorcontact)
	 */
	public function update( $ID, $type )
	{
		if( !$user = $this->User_DB->get_basic($ID) )
		{
			$response['message'] = 'Usuario no encontrado';
		}
		else
		{
			/**
			 * @validation
			 */
			switch ($type) {
				case 'basic':
					
					$this->form_validation->set_rules('email','Email','required|trim|valid_email');

					if($this->input->post('edit_password') == 1 )
					{	
						$this->form_validation
							->set_rules('password', 'Contraseña', 'trim|required|min_length[4]');
					}
					if($user->status!=2)
					{
						$this->form_validation->set_rules('status', 'Estatus', 'required|in_list[0,1]');
					}
					
					$this->_basic_validation();

					$email = $this->input->post('email');
					$userTmp = $this->User_DB->getRowBy(['email' => $email]);
					
					if($userTmp && $userTmp->id != $ID  )
					{
						$this->template->json( [
							'status' => 0,
							'message' => 'El correo electrónico ingresado ya está registrado para el usuario '.$userTmp->nick_name
						] );
					}
					break;
				case 'address':
					$this->form_validation
						->set_rules('address','Dirección', 'trim|xss_clean|max_length[120]')
						->set_rules('address_zipcode','Codigo postal', 'trim|xss_clean|max_length[20]')
						->set_rules('address_city','Ciudad', 'trim|xss_clean|max_length[150]')
						->set_rules('address_state','Estado', 'trim|xss_clean|max_length[75]')
					;
					break;
				case 'primarycontact':
					$this->form_validation
						->set_rules('emergency_contact_relation','Relación', 'trim|xss_clean|max_length[120]')
						->set_rules('emergency_contact_phone','Teléfono', 'trim|xss_clean|max_length[12]')
						->set_rules('emergency_contact_name','Nombre Completo', 'trim|xss_clean|max_length[120]')
						->set_rules('emergency_contact_full_address','Dirección completa', 'trim|xss_clean|max_length[120]')
					;
					break;
				case 'secondarycontact':
					$this->form_validation
						->set_rules('emergency_contact_other_relation','Relación', 'trim|xss_clean|max_length[120]')
						->set_rules('emergency_contact_other_phone','Teléfono', 'trim|xss_clean|max_length[12]')
						->set_rules('emergency_contact_other_name','Nombre Completo', 'trim|xss_clean|max_length[120]')
						->set_rules('emergency_contact_other_full_address','Dirección completa', 'trim|xss_clean|max_length[120]')
					;
					break;
				case 'doctorcontact':
					$this->form_validation
						->set_rules('emergency_contact_doctor_name','Relación', 'trim|xss_clean|max_length[120]')
						->set_rules('emergency_contact_doctor_phone','Teléfono', 'trim|xss_clean|max_length[12]')
						->set_rules('emergency_contact_doctor_address','Dirección completa', 'trim|xss_clean|max_length[120]')
					;
					break;
			}

			if( $this->form_validation->run() === FALSE )
			{
				$response = [
					'message' => $this->form_validation->error_string(),
					'post' => $this->input->post('edit_password')
				];
			}
			else
			{		
				$message  = '';
				/**
				 * @updated
				 */
				switch ($type) {
					case 'basic':
						if($this->input->post('edit_password') == 1 )
						{	
							$this->User_DB->password  = password_hash( $this->input->post('password') , PASSWORD_BCRYPT );
						}
						if($user->status!=2)
						{
							$this->User_DB->status = $this->input->post('status');
						}
						$this->_basic_save( $ID );

						$message = 'Información básica actualizada';
						break;
					case 'address':
						$this->User_DB->address         = $this->input->post('address');
						$this->User_DB->address_zipcode = $this->input->post('address_zipcode');
						$this->User_DB->address_city    = $this->input->post('address_city');
						$this->User_DB->address_state   = $this->input->post('address_state');
						$this->User_DB->save($ID);
						$message  = 'Dirección Actualizada';
						break;
					case 'primarycontact':
						$this->User_DB->emergency_contact_relation     = $this->input->post('emergency_contact_relation');
						$this->User_DB->emergency_contact_phone        = $this->input->post('emergency_contact_phone');
						$this->User_DB->emergency_contact_name         = $this->input->post('emergency_contact_name');
						$this->User_DB->emergency_contact_full_address = $this->input->post('emergency_contact_full_address');
						$this->User_DB->save( $ID );
						$message = 'Contacto primario actualizada';
						break;
					case 'secondarycontact':
						$this->User_DB->emergency_contact_other_relation     = $this->input->post('emergency_contact_other_relation');
						$this->User_DB->emergency_contact_other_phone        = $this->input->post('emergency_contact_other_phone');
						$this->User_DB->emergency_contact_other_name         = $this->input->post('emergency_contact_other_name');
						$this->User_DB->emergency_contact_other_full_address = $this->input->post('emergency_contact_other_full_address');
						$this->User_DB->save( $ID );
						$message = 'Contacto secundario actualizada';
						break;
					case 'doctorcontact':
						$this->User_DB->emergency_contact_doctor_name    = $this->input->post('emergency_contact_doctor_name');
						$this->User_DB->emergency_contact_doctor_phone   = $this->input->post('emergency_contact_doctor_phone');
						$this->User_DB->emergency_contact_doctor_address = $this->input->post('emergency_contact_doctor_address');
						$this->User_DB->save( $ID );
						$message = 'Contacto del médico actualizado';
						break;
				}

				$response = [
					'status' => 1,
					'message' => $message
				];
			}
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{get}(:num)/remove
	 */
	public function remove( $ID )
	{
		if( !$user = $this->User_DB->get_basic( $ID ) )
		{
			show_error('Usuario no encontrado');
		}

		if( (int)$user->status === 2 )
		{	

			$this->notify->success('Usuario fue eliminado');

			$this->User_DB->delete( $ID );
		}

		redirect('/user/manager/index');
	}

	private function _basic_validation()
	{
		$result_access_type   = $this->User_DB->get_access_types();
		$result_access_type[] = "root";

		$this->form_validation
			->set_rules('names', 'Nombres', 'trim|required|xss_clean')
			->set_rules('last_name', 'Apellidos', 'trim|required|xss_clean')
			->set_rules('access_type', 'Tipo de acceso', 'trim|required|xss_clean|in_list['.implode(',',$result_access_type).']')
			->set_rules('gender','Genero','required|in_list[Male,Female]')
			->set_rules('marital_status','Estado civil','xss_clean|trim|required')
			->set_rules('phone','Teléfono','xss_clean|trim|required|max_length[12]|min_length[12]')
			->set_rules('medical_information','Información medica','xss_clean|trim')
			->set_rules('date_of_birth','Fecha de nacimiento', 'xss_clean|trim|required|exist_date|date_max_today')
		;

		if( $this->input->post('access_type')==='medic' )
		{	
			$this->form_validation
				->set_rules('medic_type','Tipo de medico','trim|xss_clean|required|in_list[MD,PA,NP]')
				//->set_rules('medic_npi','Medic NPI','trim|xss_clean|required|max_length[75]')
				->set_rules('digital_signature','Firma de medico','required|xss_clean|trim')	
			;
		}

		return true;
	}

	private function _basic_save( $ID = 0)
	{
		$medic_type        = is_null($this->input->post('medic_type')) ? '' : $this->input->post('medic_type');
		$medic_npi         = is_null($this->input->post('medic_npi')) ? '' : $this->input->post('medic_npi');
		$digital_signature = is_null($this->input->post('digital_signature')) ? '' : $this->input->post('digital_signature');
		
		$this->User_DB->medic_type        = $medic_type;
		$this->User_DB->medic_npi         = $medic_npi;
		$this->User_DB->digital_signature = $digital_signature;
		
		$this->User_DB->names               = $this->input->post('names');
		$this->User_DB->email 				= $this->input->post('email');
		$this->User_DB->last_name           = $this->input->post('last_name');
		$this->User_DB->access_type         = $this->input->post('access_type');
		$this->User_DB->gender              = $this->input->post('gender');
		$this->User_DB->marital_status      = $this->input->post('marital_status');
		$this->User_DB->phone               = $this->input->post('phone');
		$this->User_DB->medical_information = $this->input->post('medical_information');
		$this->User_DB->date_of_birth       = $this->input->post('date_of_birth');

		return $this->User_DB->save( $ID );
	}

	private function _getInstance()
	{
		$instance_id = implode("",str_split(substr(sha1(microtime()), rand(0, 5), 25),5));
		$user        = $this->db->select('id')
			->from('user')
			->where(["instance_id" => $instance_id]);
		
		while (!$user) 
		{
			$instance_id = $instance_id;
			$user 		 = $this->db->select('id')
				->from('user')
				->where(["instance_id" => $instance_id]);	
		}

		return $instance_id;
	}

}	

