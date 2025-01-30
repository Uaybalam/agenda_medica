<?php
/**
 * @route:user/examinations
 */
class User_Examinations_Controller extends APP_User_Controller{	
	
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Examinations_Model' => 'Examinations_DB'
		]);
	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{	
		
		$this->template
			->set_title('Examinations')
			->body([
				'ng-app' => 'app_examinations',
				'ng-controller' => 'ctrl_examinations',
				'ng-init' => "initialize()"
			])
			->js('user/user.examinations')
			->render('user/view.panel.user.examinations');
	}

	/**
	 * @route:getInfoExaminations
	 */
	function getInfoExaminations()
	{
		$data = $this->Examinations_DB->get_data($this->current_user->instance_id);

		$this->template->json( [
			'myexaminations' => $data,
			'status' => 1
		] );

	}

	/**
	 * @route:insert
	 */
	function insert()
	{
		if(! ($response['message'] = $this->__validate()) )
		{
			$this->Examinations_DB->title   	= $this->input->post('title');
			$this->Examinations_DB->content 	= $this->input->post('content');
			$this->Examinations_DB->user_id 	= $this->current_user->id;
			$this->Examinations_DB->instance_id = $_SESSION['User_DB']->instance_id;

			$ID = $this->Examinations_DB->save();
			
			$response = [
				'status' => 1,
				'message' => 'Examination was added',
				'item' => $this->Examinations_DB->get($ID)
			];
		}

		$this->template->json( $response );
	}

	/**
	 * @route:update/(:num)
	 */
	function update($ID)
	{
		$examination = $this->Examinations_DB->getRowBy([
			'id' => $ID, 
			'user_id' => $this->current_user->id
		]);

		if(!$examination)
		{
			$response['message'] = 'This isn my examination';
		}
		else if(! ($response['message'] = $this->__validate()) )
		{
			$this->Examinations_DB->title = $this->input->post('title');
			$this->Examinations_DB->content = $this->input->post('content');
			$this->Examinations_DB->save($ID);
			$response = [
				'status' => 1,
				'message' => 'Examination was updated',
				'item' => $this->Examinations_DB->get($ID),
			];
		}

		$this->template->json( $response );
	}

	/**
	 * @route:delete/(:num)
	 */
	function delete($ID)
	{
		$examination = $this->Examinations_DB->getRowBy([
			'id' => $ID, 
			'user_id' => $this->current_user->id
		]);

		if(!$examination)
		{
			$response['message'] = 'This isn my examination';
		}
		else
		{
			$this->Examinations_DB->delete( $ID );
			$response = [
				'status' => 1,
				'message' => 'Examination was deleted'
			];
		}

		$this->template->json( $response );
	}

	private function __validate()
	{
		$this->form_validation
			->set_rules('title', 'Titulo', 'required|trim|xss_clean|max_length[70]')
			->set_rules('content', 'Contenido', 'required|trim|xss_clean')
		;

		return ( $this->form_validation->run() === FALSE ) ? $this->form_validation->error_string() : FALSE;
	}

}