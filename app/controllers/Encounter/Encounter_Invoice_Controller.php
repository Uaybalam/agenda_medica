<?php
/**
* @route:encounter/invoice
*/
class Encounter_Invoice_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Model' => 'Patient_DB',
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Invoice_Model' => 'Encounter_Invoice_DB'
		]);

	}
	
	/**
	 * @route:{get}(:num)/pdf
	 */
	public function pdf( $encounter_id )
	{
		if( !$encounter =  $this->Encounter_DB->get_info_request( $encounter_id) )
		{
			show_error("Encounter not found");
		}
		
		$params = [
			'invoice' => $this->Encounter_Invoice_DB->get_info($encounter_id),
			'encounter' => $encounter,
			'patient' => $this->Patient_DB->get_info( $encounter->patient_id )
		];

		$this->load->library('print/PDF_Encounter_Invoice');
			
		$this->pdf_encounter_invoice->body( $params  );
		$this->pdf_encounter_invoice->output();
		
	}

	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	function search($maxRecords = 0, $page = 0 )
	{
		$this->validate_access(['admin','root']);

		if( $this->input->get('format') === 'pdf' )
		{
			$page = -1;
		}

		$result = $this->Encounter_Invoice_DB->getPagination( 
			$maxRecords,
			$page,
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		 
		foreach ($result['total_count'] as $key => &$sums ) 
		{
			if($sums != NULL){
				$sums = ($key==='counter') ? $sums : number_format($sums,2);
			}

		}

		if( $this->input->get('format') === 'pdf' )
		{
			$this->load->library('print/PDF_Encounter_Invoice_Report');
			
			$this->pdf_encounter_invoice_report->body( $this->input->get(), $result );

			$this->pdf_encounter_invoice_report->output();
		}
		else
		{
			return $this->template->json( $result, FALSE );
		}
		
	}
	
	/**
	 * @route:{get}report
	 */
	function report()
	{
		$this->validate_access(['admin','root']);
		
		$this->template->css('daterangepicker','/assets/vendor/datepickerrange/');	
		$this->template->js('daterangepicker.min','/assets/vendor/datepickerrange/');

		$this->template
			->body([
				'ng-app' => 'app_invoice_search',
				'ng-controller' => 'ctrl_invoice_search',
			])
			->set_title("Invoices * Cash")
			->js('invoice/invoice.search')
			->render('encounter/invoice/view.panel.invoice.search');
	}

	/**
	 * @route:{post}toggleActive/(:num)
	 */
	function toggleActive( $encounter_id )
	{
		$encounter = $this->Encounter_DB->get( $encounter_id );
		if(!$encounter)
		{
			return $this->template->json( ['message' => 'Encounter not found'] );
		}
		
		$invoice = $this->Encounter_Invoice_DB->getRowBy(['encounter_id' => $encounter_id ]);
		if(!$invoice)
		{
			return $this->template->json( ['message' => 'Invoice not found'] );
		}
		else if(!$encounter->has_insurance)
		{
			return $this->template->json( ['message' => 'Encounter not has insurance, must be add invoice bill'] );
		}
		
		$this->Encounter_Invoice_DB->enabled =  ( intval($invoice->enabled) ===  1 ) ?  0 : 1;
		$this->Encounter_Invoice_DB->update( ['id' => $invoice->id ]);

		return $this->template->json( ['status' => 1, 'message' => 'Invoice updated' ] );
	}
}