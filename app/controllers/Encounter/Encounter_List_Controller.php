<?php
/**
 * @route:encounter-list
 */
class Encounter_List_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
		]);
		
		$this->validate_access(['admin','manager','billing','root'], '/');

	}

	/**
	 * @route:{get}__avoid__
	 */
	function index()
	{	

		$this->template
			->body([
				'ng-app' => 'app_encounters_list',
				'ng-controller' => 'ctrl_encounters_list',
			])
			->set_title("Encounters")
			->js('encounter/encounter.list')
			->render('encounter/view.panel.encounter.list');
	}
	
	/**
	 * @route:{get}data/(:num)/(:num)
	 */
	function records_data( $maxRecords = 0, $page = 0)
	{	
		$limit = ($maxRecords==0 || $maxRecords>100) ? 10 : $maxRecords;
		$start = ( $limit * $page ) - $limit;

		$encounters = $this->Encounter_DB->get_data($limit, $start, $this->input->get() );
		
		foreach ($encounters as &$enc) {
			$enc->diagnosis = $this->Encounter_Diagnosis_DB->diagnosisByEncounter( $enc->id );
		}

		$this->template->json( [
			'total_count' => $this->Encounter_DB->total_count( $this->input->get('filters')  ),
			'result_data' => $encounters
		]);
	}

}