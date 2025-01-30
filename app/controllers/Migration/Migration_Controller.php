<?php
/**
 * @route:migration
 */

class Migration_Controller extends APP_User_Controller
{

	function __construct()
	{	
		parent::__construct();
		$this->load->library('Migration_HS');
	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{


		$path_initial = $this->migration_hs->folders['initial_csv'];

		$errors = [];
		if(!file_exists($path_initial . 'patients.csv'))
		{
			$errors[] = "File ".$path_initial . "patients.csv not found";
		}
		if(!file_exists($path_initial . 'users.csv'))
		{
			$errors[] = "File ".$path_initial . "users.csv not found";
		}
		if(!file_exists($path_initial . 'encounters.csv'))
		{
			$errors[] = "File ".$path_initial . "encounters.csv not found";
		}

		$folder_clear        = $this->migration_hs->folders['clean_csv'];
		$folder_relation     = $this->migration_hs->folders['relation'];
		$folder_not_inserted = $this->migration_hs->folders['not_inserted'];

		if(!file_exists( $folder_clear ) )
		{
			if( !@mkdir( $folder_clear ) )
			{
				$errors[] = "Folder ".$folder_clear . " cant be created";
			}
			else
			{
				chmod( $folder_clear , 0777 );
			}
		}
		if(!file_exists( $folder_relation ) )
		{
			if( !@mkdir( $folder_relation ) )
			{
				$errors[] = "Folder ".$folder_relation . " cant be created";
			}
			else
			{
				chmod( $folder_relation , 0777 );
			}
		}
		if(!file_exists( $folder_not_inserted ) )
		{
			if( !@mkdir( $folder_not_inserted ) )
			{
				$errors[] = "Folder ".$folder_not_inserted . " cant be created";
			}
			else
			{
				chmod( $folder_not_inserted , 0777 );
			}	
		}
		
		
		$this->template
			->js('migration.index')
			->render('view.migration', [
				'key_code' => $this->migration_hs->key_code_generate() , 
				'errors' => $errors
			]);	
	}

	/**
	 * @route:errors
	 */
	function errors()
	{
		
		$this->template
			->render('view.migration.errors',['errorData' =>  [
				'addendums' => file( $this->migration_hs->full_path( 'not_inserted','addendums.not_inserted.txt') ),
				'appointments' => file( $this->migration_hs->full_path( 'not_inserted','appointments.not_inserted.txt') ),
				'communications' => file( $this->migration_hs->full_path( 'not_inserted','communications.not_inserted.txt') ),
				'diagnosis' => file( $this->migration_hs->full_path( 'not_inserted','diagnosis.not_inserted.txt') ),
				'encounters' => file( $this->migration_hs->full_path( 'not_inserted','encounters.not_inserted.txt') ),
				'labs' => file( $this->migration_hs->full_path( 'not_inserted','labs.not_inserted.txt') ),
				'medications' => file( $this->migration_hs->full_path( 'not_inserted','medications.not_inserted.txt') ),
				'patients' => file( $this->migration_hs->full_path( 'not_inserted','patients.not_inserted.txt') ),
			]]);	
	}

	
	/**
	 * @route:{get}truncate/run/(:any)
	 */
	function truncate( $token )
	{
		$this->migration_hs->key_code_valid( $token );
		
		$this->migration_hs->reset_data();

		$this->migration_hs->_log[] = "Truncate data tables";
		/**
		 * 
		 */
		$this->migration_hs->jsonSuccess();
	}
}
