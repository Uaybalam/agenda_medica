<?php

class APP_User_Controller extends CI_Controller
{
	public $current_user;
	
	function __construct( $config = null ){
		parent::__construct();
		
		/**
		 * Remember remove this secition in production
		 */
		if( ! ($this->current_user = @$this->session->userdata['User_DB'] ) )
		{	
			$this->notify->error('Intanta iniciar sesión');
			redirect('/');
		}

		if($this->current_user->status == 2 && $this->router->class!='User_Controller')
		{
			redirect('/user/profile/?new=1');
		}

		if($_SERVER["REDIRECT_URL"] != "/user/update2fa" && !isset($_REQUEST['active2fa']))
		{
			$user = $this->db->select([
				'secret2fa',
				'active2fa', 
			])
			->where([ 'id' => $this->current_user->id ])
			->get('user')
		    ->row();
		   
			if($user->secret2fa && $user->active2fa)
			{ 
				if(!$this->session->verfied)
				{ 
					redirect('/login/verifyCodeAuth');
				} 
			}
		}

		$this->template->layout('user');
		
		$this->load->model(['Menu_Model' => 'Menu_DB']);
		$this->Menu_DB->current_user = $this->current_user;
	}
	
	
	
	/**
	 * @access_type array(secretary, nurse, medic)
	 */
	protected function validate_access( $access_type , $redirect_url = '', $stop = true )
	{	
		$arr_access_type = (is_array($access_type)  ) ? $access_type : [$access_type];
		if  ( 	$this->current_user->access_type === 'admin' || $this->current_user->access_type === 'root' || 
				in_array($this->current_user->access_type, $arr_access_type ) 
			)
		{
			return true;
		}

		if(!$stop)
			return false;
		
		if( $redirect_url !='' )
		{
			redirect($redirect_url);
		}
		else
		{

			$str_access_types = [];
			foreach ($arr_access_type as $value) 
			{
				$str_access_types[] = $this->User_DB->access_type_avalible[$value];
			}

			$this->template->json([
				'status' => 0,
				'message'=> 'Only there users can edit <b>'.implode(', ', $str_access_types).'</b>'
			]);
		}
	}

	
	protected function add_appt_event( $appointment_id , $event , $notes = '' )
	{
		$this->db->insert('appointment_event', [
			'appointment_id' => $appointment_id,
			'event' => $event,
			'notes' => $notes,
			'date' => date('Y-m-d H:i'),
			'user' => $this->current_user->nick_name
		]);
	}

	protected function patient_path( $ID )
	{	
		$path = FCPATH ."../private/uploads/patients/patient_{$ID}";
		if(!file_exists($path))
		{
			mkdir($path,0775);
		}
		return $path;
	}

	protected function deleted_path( $patientID , $filename )
	{	
		$date     = date('YmdHis');
		$userID   = $this->current_user->id;
		$fullName = "P{$patientID}_D{$date}_U{$userID}_$filename";
		return FCPATH ."../private/uploads/deleted/{$fullName}";
	}
}