<?php
use Vectorface\GoogleAuthenticator;
/**
 * @validate::UserInternal
 *
 * @route:user
 */
class User_Controller extends APP_User_Controller{	

	/**
	 * @route:profile
	 */
	function profile()
	{
		$this->current_user->qrCodeUrl = "";

		$user = $this->db->select([
			'secret2fa',
			'active2fa', 
		])
		->where([ 'id' => $this->current_user->id ])
		->get('user')
	    ->row();
 
		$this->current_user->secret2fa = $user->secret2fa;
		$this->current_user->active2fa = $user->active2fa;

		if($this->current_user->secret2fa && $this->current_user->active2fa)
		{
			require_once dirname(__FILE__).'/../../libraries/google2fa/vendor/autoload.php';

			$ga                            = new GoogleAuthenticator();
			$this->current_user->qrCodeUrl = $ga->getQRCodeUrl($this->current_user->email, $this->current_user->secret2fa);
		}

		$this->template
			->modal('user/modal.password', ['title' => 'Editar contraseña'])
			->js('user/profile')
			->render('user/view.profile' ,  [
				'user' => $this->current_user,
				'access_type' => $this->User_DB->access_type_avalible
			]);
	}

	
	
	/**
	 * @route:update
	 */
	function update()
	{
		
		$this->form_validation
			->set_rules('names', 'Names', 'trim|required|xss_clean')
			->set_rules('last_name', 'Last name', 'trim|required|xss_clean')
			->set_rules('pin', 'Pin', 'trim|xss_clean|required|min_length[4]|max_length[6]')
		;
		
		if ($this->form_validation->run() === FALSE)
        {
        	$this->notify->error( $this->form_validation->error_string() );
        	
        	redirect('/user/profile?'.http_build_query( $this->input->post() ));	
        }	
        else
        {	
        	
			$this->User_DB->names     = $this->input->post('names');
			$this->User_DB->last_name = $this->input->post('last_name');
			$this->User_DB->pin       = $this->input->post('pin');
			$this->User_DB->status    = 1;
			$this->User_DB->save( $this->current_user->id );
			
			//update session data
			$this->session->set_userdata([
				'User_DB' => $this->User_DB->get( $this->current_user->id) 
			]);

        	$this->notify->success( 'Usuario fue actualizado ');
        	
        	redirect('/user/profile');	
        }
       	
	}

	/**
	 * @route:edit/password
	 */
	function edit_password()
	{
		
		$this->form_validation
			->set_rules( 'password_old', 'Antigua contraseña', 'trim|required')	
			->set_rules( 'password_new', 'Nueva contraseña', 'trim|required|min_length[4]|max_length[50]')
			->set_rules( 'password_confirm', 'Confirmar contraseña', 'trim|required|matches[password_new]');
				
		if( $this->form_validation->run() === FALSE)
		{
			$this->notify->error( $this->form_validation->error_string() );	
		}
		else if( !password_verify( $this->input->post('password_old') ,  $this->current_user->password )  )
		{
			$this->notify->error('Contraseña invalida');
		}
		else 
		{
			$this->current_user->password = $this->User_DB->password = password_hash( $this->input->post('password_new') , PASSWORD_BCRYPT );
			$this->User_DB->save( $this->current_user->id );
			$this->notify->success( 'Contraseña actualizada');
		}	
		
		redirect('/user/profile');
	}

	/**
	 * @route:update2fa
	 */
	function update2fa()
	{  
		require_once dirname(__FILE__).'/../../libraries/google2fa/vendor/autoload.php';

		$this->current_user->active2fa = $this->input->post('active2fa');
		$this->User_DB->active2fa      = $this->input->post('active2fa');
		$ga                            = new GoogleAuthenticator();

		if(!$this->current_user->secret2fa)
		{
			$secret = $ga->createSecret();

			$this->current_user->secret2fa = $secret;
			$this->User_DB->secret2fa      = $secret;
		}

		$this->User_DB->save( $this->current_user->id );

		if($this->input->post('active2fa'))
		{
			$qrCodeUrl = $ga->getQRCodeUrl($this->current_user->email, $this->current_user->secret2fa);
			echo json_encode(["status" => 1, "qr" => $qrCodeUrl]);
		}
		else
		{
			echo json_encode(["status" => 0]);
		}
	}

	/**
	 * @route:update2faUser
	 */
	function update2faUser()
	{  
		$user = $this->db->select()
			->from('user')
			->where(['id'=> $this->input->post('user_id')])->get()->row();

		if( !$user )
		{
			echo json_encode(["status" => 0]);
		}

		require_once dirname(__FILE__).'/../../libraries/google2fa/vendor/autoload.php';

		$this->User_DB->active2fa  = $this->input->post('active2fa');
		$ga                        = new GoogleAuthenticator();

		if(!$user->secret2fa)
		{
			$secret                   = $ga->createSecret();
 			$user->secret2fa          = $secret;
			$this->User_DB->secret2fa = $secret;
		}

		$this->User_DB->save($this->input->post('user_id'));

		if($this->input->post('active2fa'))
		{
			$qrCodeUrl = $ga->getQRCodeUrl($user->email, $user->secret2fa);
			echo json_encode(["status" => 1, "qr" => $qrCodeUrl]);
		}
		else
		{
			echo json_encode(["status" => 0]);
		}
	}
}	