<?php 
/**
* @route:administration
*/
class administration_Controller extends APP_User_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Administration_Model'
		]);

		$this->validate_access(['admin','manager','root'], '/');
	}
	
	/**
	 * @route:__avoid__
	 */
	function config()
	{

		if(!$configurationsSettings = $this->Administration_Model->getData())
		{
			libraries\Administration::install();
			$configurationsSettings = $this->Administration_Model->getData(); 
		} 

		$existBackupDocuments = $this->existBackupDocuments();

		$this->template->js('administration/setting');

		$this->template->set_title('Administration');

		$this->template->render('administration/view-administration-general', [
			'administration' => $configurationsSettings,
			'existBackupDocuments' => $existBackupDocuments
		]);	
	}	

	/**
	 * @route:{post}update
	 */
	function update()
	{

		$setting = $this->Administration_Model->getRowBy(['name' => trim($this->input->post('name')), 'instance_id' => $_SESSION['User_DB']->instance_id ]);

		if(!$setting )
		{
			$this->template->json([
				'status' => 0,
				'message' => 'Ajuste no encontrado'
			]);
		}

		/**
		 * Set new Value
		 */
		$this->Administration_Model->value = trim($this->input->post('value'));
		$this->Administration_Model->save( $setting->id );
		
		$this->template->json([
			'status' => 1,
			'message' => 'Ajuste '.$setting->title.' fue cambiado'
		]);
		
	}

	/**
	 * @route:{get}downloadDatabase
	 */
	function downloadDatabase()
	{
		if(in_array($this->current_user->nick_name, ['josue','jonathanq']))
			exportDataBase();
		
		//header("location: ".site_url("administration"));
	}
	
	/**
	 * @route:{get}downloadDocuments
	 */
	function downloadDocuments()
	{
		if(!in_array($this->current_user->nick_name, ['josue','jonathanq']))
		{
			header("location: ".site_url("administration"));
		}

		
	}

	/**
	 * @route:{post}uploadLogo
	 */
	function uploadLogo()
	{ 
		$folder   = FCPATH . "../private/uploads/files/instance_".$_SESSION['User_DB']->instance_id;
		$logo     = "logo.".explode("/",$_FILES['file']["type"])[1];
		$tmp_name = $_FILES["file"]["tmp_name"]; 

		if(explode("/",mime_content_type($_FILES["file"]["tmp_name"]))[0] != "image")
		{
			$this->template->json([
				'status' => 0,
				'message' => 'Formato incorrecto de archivo, debes ingresar una imagen'
			]);
		}

		if(\libraries\Administration::getValue('logo') != "isotipo.png")
		{
			unlink(FCPATH . "../private/uploads/files/instance_".$_SESSION['User_DB']->instance_id."/".\libraries\Administration::getValue('logo'));
		}

		if(file_exists($folder))
		{  
			move_uploaded_file($tmp_name, "$folder/$logo");
		}
		else
		{
			mkdir($folder); 
			move_uploaded_file($tmp_name, "$folder/$logo");
		}

		$setting = $this->Administration_Model->getRowBy(['name' => "logo", 'instance_id' => $_SESSION['User_DB']->instance_id ]);

		if(!$setting )
		{
			$this->template->json([
				'status' => 0,
				'message' => 'Ajuste no encontrado'
			]);
		}
 
		$this->Administration_Model->value = $logo;
		$this->Administration_Model->save( $setting->id );
		
		$this->template->json([
			'status' => 1,
			'message' => 'Ajuste '.$setting->title.' fue cambiado'
		]);
	}

	/**
	 * @route:{get}getLogo
	 */
	function getLogo()
	{
		$this->template->render_file(FCPATH . "../private/uploads/files/instance_".$_SESSION['User_DB']->instance_id."/".$_GET['logo']);
	}

	private function existBackupDocuments()
	{
		$backupDocs = FCPATH . '../private/uploads/backupDocuments.zip';
		if( file_exists($backupDocs) )
		{
			return true;
		}

		return false;
	}
}