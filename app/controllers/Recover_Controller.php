<?php
/**
* @route:recover
*/
class Recover_Controller extends CI_Controller
{		
	
	function __construct()
	{
		parent::__construct();

	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{
		
		
		$email = $this->input->get('email');

		$this->template
			->layout('public','**Recover Password**')
			->render('recover/view-recover-password', [
				'email' => $email
			]);
	}

	/**
	 * @route:{post}send
	 */
	function send()
	{
		$email = $this->input->post('email');
		
		$user = $this->User_DB->getRowBy([
			'email' => $this->input->post('email'),
		]);

		if($email==='')
		{
			$this->notify->error( 'Email field is required' );
		}
		else if(!$user)
		{
			$this->notify->error( 'Invalid Email' );
		}
		else
		{
			$token   = md5($email).'_'.uniqid();
			$data 	 = [
				'email' => $email,
				'token' => $token
			];

			$content = "Open the following link in a new browser tab: <br><br>";
			$content.= site_url('/recover/reset/?'.http_build_query($data));

			include_once APPPATH . '/libraries/Mail/Mail.php';
			$mail = new Lib\Mail();
			$mail->setSubject('Recover Password');
			$mail->addAddress($email);

			if (!$mail->send( $content) ) 
			{
				$this->notify->error($mail->errorMessage);
			}
			else
			{
				$this->User_DB->recover_token = $token;
				$this->User_DB->recover_date  = date('Y-m-d H:i:s');
				$this->User_DB->save( $user->id );

				$this->notify->success('Check your email inbox to recover your password');
			}

			
		}

		redirect('/recover');
	}

	/**
	 * @route:{get}reset
	 */
	function reset()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$this->template
			->layout('public','**Reset Password**')
			->render('recover/view-reset-password', [
				'email' => $email,
				'token' => $token
			]);
	}

	/**
	 * @route:{post}change
	 */
	function change()
	{
		$token    = $this->input->post('token');
		$email    = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->User_DB->getRowBy([
			'recover_token' => $token, 
			'email' => $email 
		]);

		if(!$user)
		{
			$this->notify->error("the Restoration of the password has expired");
			return  redirect('/');
		}
		if( !in_array($user->status, [1,2]) )
		{
			$this->notify->error("The user is deactivated, please contact the administrator");
			return  redirect('/');
		}
		
		$restoreDate = new DateTime($user->recover_date);
		$currentDate = new DateTime();
		$interval = $currentDate->diff($restoreDate);
		if( $interval->format('%d') >= 1 )
		{
			$this->notify->error("the Restoration of the password has expired, Request new token ".$interval->format('%d'));
			return  redirect('/');
		}

		$backUrl = site_url('/recover/reset/?'.http_build_query([
			'email' => $email,
			'token' => $token
		]));

		if(!$password)
		{
			$this->notify->error("Password field is required");
			return  redirect($backUrl);
		}
		if(strlen($password)<6)
		{
			$this->notify->error("The password field must have at least 6 characters");
			return  redirect($backUrl);
		}
		if($password!== $this->input->post('password_confirm'))
		{
			$this->notify->error("The Password field does not match the Confirm Password field");
			return  redirect($backUrl);
		}

		$this->User_DB->password      =  password_hash( $password , PASSWORD_BCRYPT );
		$this->User_DB->recover_token = '';
		$this->User_DB->recover_date  = '';
		$this->User_DB->save( $user->id );
		$this->notify->success('Changes have been applied with your new password');

		return redirect('/?'.http_build_query([
			'nick_name' => $user->nick_name]));
	}


}
