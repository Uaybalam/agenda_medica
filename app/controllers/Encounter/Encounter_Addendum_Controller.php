<?php
/**
* @route:encounter/addendum
*/
class Encounter_Addendum_Controller extends APP_User_Controller
{
	
	function __construct()
	{	
		parent::__construct();

		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Addendum_Model' => 'Encounter_Addendum_DB'
		]);

		$this->Encounter_DB->set_user( $this->current_user );
	}
	
	/**
	 * @route:{post}create/(:num)
	 */
	function create( $ID )
	{
		$response['status'] = 0;

		if( ! ( $encounter = $this->Encounter_DB->get($ID) ) )
		{
			show_error('Encounter not found' , 404);
		}else if( $encounter->status === 1 )
		{	
			show_error('Encounter not found status process' , 404);
		}

		$this->form_validation
			->set_rules('notes','Comment diagnosis','required|xss_clean|trim')
			->set_rules('password','User PIN','required|pin_verify')
		;

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else
		{		
			$this->Encounter_Addendum_DB->encounter_id = $encounter->id;
			$this->Encounter_Addendum_DB->patient_id   = $encounter->patient_id;
			$this->Encounter_Addendum_DB->user_id      = $this->current_user->id;
			$this->Encounter_Addendum_DB->create_at    = date('Y-m-d H:i:s');
			$this->Encounter_Addendum_DB->notes        = $this->input->post('notes');
			$addendum_id = $this->Encounter_Addendum_DB->save();
			
			$response = [
				'status' => 1,
				'message' => 'Addendum was added',
				'addendum' => $this->Encounter_Addendum_DB->get_detail( $addendum_id ),
			];
			
			$this->Encounter_DB->set_activity( $ID , 'encounter_addendum_create' );
		}


		$this->template->json( $response );
	}



	function _validate_user( $str )
	{	
		if(!password_verify( $str ,  $this->current_user->password )  ) 
		{
			$this->form_validation->set_message('_validate_user',   'Password does not match the user');
            return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}