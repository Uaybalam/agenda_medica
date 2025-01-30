<?php
/**
* @route:pending/warnings
*/
class Pending_Warnings_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Patient_Warnings_Model' => 'Patient_Warnings_DB',
		]);
	}
	
	/**
	 * @route:__avoid__
	 */
	function index()
	{
		
		$this->template
			->set_title('List of pending warnings')
			->body([
				'ng-app' => 'app_pending_warnings',
				'ng-controller' => 'ctrl_pending_warnings',
				'ng-init' => "initialize()"
			])
			->js('pending/pending.warnings')
			->render('pending/view.pending.warnings');
	}

	/**
	 * @route:{get}initialize
	 */
	function initialize()
	{
			
		$response = [
			'warnings' => $this->Patient_Warnings_DB->get_pending( $this->current_user ),
			'available_status' => $this->Patient_Warnings_DB->data_status()
		];
			
		$this->template->json( $response );
	}
}