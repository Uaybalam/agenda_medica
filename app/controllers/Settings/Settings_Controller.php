<?php 
/**
* @route:settings
*/
class Settings_Controller extends APP_User_Controller
{	

	function __construct()
	{
		parent::__construct();

		$this->validate_access('manager','admin','root', '/');

		$this->load->model([
			'Custom_Setting_Model' => 'Custom_Setting_Model',
		]);
	}

	/**
	 * @route:__avoid__
	 */
	function main()
	{

		$this->template
			->set_title('Manage Values')
			->body([
				'ng-app' => 'app_settings',
				'ng-controller' => 'ctrl_settings',
				'ng-init' => 'initialize()'
			])
			->js( 'settings/settings.values' )
			->render( 'settings/view-manage-values' );
	}	

	/**
	 * @route:{get}(:any)/search/(:num)/(:num)
	 */
	function search( $settingName, $maxRecords = 0, $page = 0 )
	{

		if( !$this->Custom_Setting_Model->setType( $settingName ) )
		{
			return $this->template->json([
				'status' => 0,
				'message' => 'Setting Type Undefined'
			]);	
		}

		$limit = ($maxRecords==0 || $maxRecords>100) ? 10 : $maxRecords;
		$start = ( $limit * $page ) - $limit;

		$totalCount = $this->Custom_Setting_Model->total_count();
		$resultData = $this->Custom_Setting_Model->get_data($limit, $start );

		return $this->template->json([
			'total_count' => $totalCount,
			'result_data' => $resultData
		]);	
	}

	/**
	 * @route:{get}init
	 */
	function init()
	{
		
		$this->template->json([
			'settings' =>  $this->Custom_Setting_Model->getSettings()
		]);
	}

	/**
	 * @route:{post}insert/(:any)
	 */
	function insert( $settingName )
	{
		$_POST['type'] = $settingName;

		$this->form_validation
			->set_rules('name', 'Name', 'required|xss_clean|trim|max_length[150]|exist_setting_name['.$settingName.']')
			->set_rules('type', 'Type', 'required|xss_clean|trim|max_length[75]|in_list['.implode(",",$this->Custom_Setting_Model->getTypes()).']')
		;

		if($this->form_validation->run() === FALSE)
		{
			$response = [
				'status' => 0,
				'message' => $this->form_validation->error_string()
			];
		}
		else 
		{		
			$this->Custom_Setting_Model->type = $settingName;
			$this->Custom_Setting_Model->name = $this->input->post('name');
			$ID = $this->Custom_Setting_Model->save();
			
			$response = [
				'status' => 1,
				'message' => 'Setting was added',
			];
		}

		$this->template->json($response);
	}

	/**
	 * @route:{post}update/(:any)/(:num)
	 */
	function update( $settingName , $ID )
	{
		
		$_POST['type'] = $settingName;

		$this->form_validation
			->set_rules('name', 'Name', 'required|xss_clean|trim|max_length[150]|exist_setting_name['.$settingName.'|'.$ID.']')
			->set_rules('type', 'Type', 'required|xss_clean|trim|max_length[75]|in_list['.implode(",",$this->Custom_Setting_Model->getTypes()).']')
		;
		
		if($this->form_validation->run() === FALSE)
		{
			$response = [
				'status' => 0,
				'message' => $this->form_validation->error_string()
			];
		}
		else 
		{
			
			$this->Custom_Setting_Model->type = $settingName;
			$this->Custom_Setting_Model->name = $this->input->post('name');
			$this->Custom_Setting_Model->fullname = $this->input->post('fullname');
			$this->Custom_Setting_Model->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'Setting was updated'
			];
		}

		$this->template->json($response);
	}

	/**
	 * @route:{delete}delete/(:any)/(:num)
	 */
	function delete( $settingName, $id)
	{
		if( !$this->Custom_Setting_Model->setType( $settingName ) )
		{
			return $this->template->json([
				'status' => 0,
				'message' => 'Setting Type Undefined'
			]);	
		}
			
		$this->Custom_Setting_Model->delete( $id );

		return $this->template->json([
			'status' => 1,
			'message' => 'Setting Removed'
		]);
	}

	/**
	 * @route:{get}(:any)/pdf
	 */
	function pdf( $settingName )
	{
		
		if( !$this->Custom_Setting_Model->setType($settingName) )
		{
			return $this->template->json([
				'status' => 1,
				'message' => 'Setting Type Undefined'
			]);
		}
		
		$items = $this->Custom_Setting_Model->getElements( $settingName );
		
		$allTypes = $this->Custom_Setting_Model->getSettings();
		
		$customSettingType = $allTypes[$settingName];
		
		$settingManagment['title']  = $customSettingType['title'];
		$settingManagment['helper'] = $customSettingType['helper'];
		

		$settingManagment['items'] = $items;
		
		$this->load->library('print/PDF_Settings');
		
		$this->pdf_settings->body( $settingManagment );
		
		$this->pdf_settings->output();
	}



}