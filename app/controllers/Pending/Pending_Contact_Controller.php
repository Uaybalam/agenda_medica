<?php
/**
* @route:pending/contact
*/
class Pending_Contact_Controller extends APP_User_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'Patient_Communication_Model' => 'Patient_Communication_DB',
			'Patient_Contact_Model' => 'Patient_Contact_DB',
			'Appointment_Model' => 'Appointment_DB',
			'Patient_Model' => 'Patient_DB'
		]);
	}

	/**
	 * @route:{get}messagenocontact/(:num)
	 */
	function message_no_contact( $contact_id )
	{
		$this->load->library('print/PDF_Contact');
		
		$communications = $this->Patient_Communication_DB->getResultsBy( ['patient_contact_id' => $contact_id] );

		if(!count($communications))
		{
			show_error('Paciente sin comunicaciones ' , 404 );
		}
		
		$patient = $this->Patient_DB->get_info( $communications[0]->patient_id);

		$this->pdf_contact->body( $patient, $communications  );
		
		$this->pdf_contact->output();
	}
	
	/**
	 * @route:__avoid__
	 */
	function index()
	{
		$typesOfCommunications = $this->Patient_Communication_DB->get_available_types();

		$this->template
			->set_title('List of Pending Communications')
			->body([
				'ng-app' => 'app_pending_contact',
				'ng-controller' => 'ctrl_pending_contact',
				'ng-init' => "initialize(".$this->template->json_entities($typesOfCommunications).")"
			])
			->modal('patient/communicate/modal.create.communication',
				['title' => 'Completar notas' ], 
				['visit_types' => $this->Appointment_DB->get_visit_types(), 'pendingContact' => true ] 
			)
			->modal('patient/communicate/modal.history.communication',['title' => 'Detalle de historial','size' => 'modal-xl' ] )
			->modal('appointment/modal.current.date',['title' => 'Citas fecha actual' ] )
			->js('pending/pending.contact')
			->render('pending/view.pending.contact');
		
	}

	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	public function search(  $maxRecords = 0, $page = 0 )
	{
		
		$result = $this->Patient_Contact_DB->getPagination( 
			$maxRecords, 
			$page, 
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		
		foreach ($result['result_data'] as &$contacts) {
			$contacts['appointment'] = $this->Appointment_DB->near( $contacts['patient_id'] );
		}

		return $this->template->json( $result );
	}
}