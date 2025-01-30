<?php
class Encounter_Invoice_Model extends APP_Model {

	function default_payment_type()
	{	
		return 'Cash';
	}
	
	function get_payment_types()
	{
		return [
			'Efectivo',
			'Credito/Debito',
			'Cheque',
			'Tarjeta de credico/Efectivo'
		];
	}
	
	function get_info( $encounter_id )
	{
		$invoice = $this->getRowBy([
			'encounter_id' => $encounter_id 
		]);
		
		
		if( $invoice && $invoice->status == 0 )
		{
			//PR($invoice);
			
			$patient = $this->db->select('open_balance')
				->where(['id' => $invoice->patient_id ])
				->get('patient')
				->row();
			
			$invoice->open_balance = $patient->open_balance;

			$subtotal = $invoice->office_visit
				+ $invoice->laboratories
				+ $invoice->injections
				+ $invoice->medications
				+ $invoice->procedures
				+ $invoice->physical
				+ $invoice->ecg
				+ $invoice->ultrasound
				+ $invoice->x_ray
				+ $invoice->print_cost
			;
			
			$invoice->total = $subtotal
				+ $invoice->open_balance
				- $invoice->discount
			;
			
			$invoice->balance_due = floatval(  number_format( $invoice->total - $invoice ->paid, 2 , '.', '' )  );
		}	
		return $invoice;
	}

	function getPagination( $itemsPerPage, $page , $sort = null, $filters = null )
	{

		$config = [
			'table' => $this->tableName(),
			'orderAvailable' => [
	    		'encounter_id' => 'encounter.id',
				'date'         => 'DATE_FORMAT(encounter.signed_at,"%Y%m%d")',
				'patient'      => 'CONCAT(patient.name," ",patient.last_name)',
				//'encounter_date' => ''
	    	],
			'itemsPerPage' => $itemsPerPage,
			'page' => $page,
			'sort' => $sort,
			'filters' => $filters
		];

		$columns = [
			'encounter.id',
			'encounter.chief_complaint',
			'encounter.insurance_title',
			'encounter.signed_at',
			'encounter.patient_id',
			'encounter.create_at as encounter_date',
			'patient.date_of_birth as patient_dob',
			'CONCAT(patient.name," ",patient.last_name) as patient',
			'appointment.visit_type as appt_visit_type',
			'encounter_invoice.print_cost',
			'encounter_invoice.office_visit',
			'encounter_invoice.laboratories',
			'encounter_invoice.injections',
			'encounter_invoice.medications',
			'encounter_invoice.procedures',
			'encounter_invoice.physical',
			'encounter_invoice.ecg',
			'encounter_invoice.ultrasound',
			'encounter_invoice.x_ray',
			'encounter_invoice.open_balance',
			'encounter_invoice.discount_type',
			'encounter_invoice.discount',//
			'encounter_invoice.paid',
			'encounter_invoice.payment_type',
			'encounter_invoice.balance_due',
			'encounter_invoice.subtotal',//
			'encounter_invoice.total',//
			'encounter_invoice.status',
			'encounter_invoice.encounter_id',
		];

		$pagination = new \libraries\Pagination( $config, $columns );
		
		$pagination->setSelectCount( function( $qb  ){

			return $qb->select([
				'SUM(encounter_invoice.print_cost) as print_cost',
				'SUM(encounter_invoice.office_visit) as office_visit',
				'SUM(encounter_invoice.laboratories) as laboratories',
				'SUM(encounter_invoice.injections) as injections',
				'SUM(encounter_invoice.medications) as medications',
				'SUM(encounter_invoice.procedures) as procedures',
				'SUM(encounter_invoice.physical) as physical',
				'SUM(encounter_invoice.ecg) as ecg',
				'SUM(encounter_invoice.ultrasound) as ultrasound',
				'SUM(encounter_invoice.x_ray) as x_ray',
				'SUM(encounter_invoice.open_balance) as open_balance',
				'SUM(encounter_invoice.discount) as discount',//
				'SUM(encounter_invoice.paid) as paid',
				'SUM(encounter_invoice.balance_due) as balance_due',
				'SUM(encounter_invoice.subtotal) as subtotal',//
				'SUM(encounter_invoice.total) as total',//			
				'COUNT(1) as counter'
        	], FALSE );

		});
		
		return $pagination->retrieve( function( $qb, $pag, $type ) {
			
			$qb->join('encounter','encounter.id=encounter_invoice.encounter_id','inner');
			$qb->join('patient','patient.id=encounter.patient_id','inner');
			$qb->join('appointment','appointment.encounter_id=encounter_invoice.encounter_id','left');
			
			$qb->where([ 
				'encounter.status' => 2 , 
				'encounter_invoice.status' => 1,
				'encounter_invoice.enabled' => 1,
				'instance_id' => $_SESSION['User_DB']->instance_id
			]);

			if($patient_id = $pag->getFilter('patient_id') )
			{
				$qb->where('encounter.patient_id', $patient_id );
			}
			if($visitType = $pag->getFilter('appt_visit_type') )
			{
				$qb->like('appointment.visit_type', $visitType );
			}
			if( $patient = $pag->getFilter('patient') )
	        {
	           	$qb->like('concat(patient.name," ",patient.last_name)', $patient );
	        }
			if( $dob = $pag->getFilter('patient_dob') )
	        {
	           	$qb->like('patient.date_of_birth', $dob );
	        }
	        if( $encounter_id = $pag->getFilter('encounter_id') )
	        {
	           	$qb->like('encounter.id', $encounter_id );
	        }
	        if( $startDate = $pag->getFilter('start_date') )
	        {
	          	$qb->where( ['DATE_FORMAT(encounter.signed_at,"%Y%m%d") >= ' => $startDate  ]);
	        }
	        if( $endDate = $pag->getFilter('end_date') )
	        {
	           	$qb->where( ['DATE_FORMAT(encounter.signed_at,"%Y%m%d") <= ' => $endDate  ]);
	        }

	        return $qb;
		});
	}

	
	function createDefault( $encounter, $patient )
	{
		$this->status        = 0;
		$this->enabled       = ( $encounter->has_insurance ) ? 0 : 1;
		$this->patient_id    = $encounter->patient_id;
		$this->encounter_id  = $encounter->id;
		$this->discount_type = $patient->discount_type;
		$this->payment_type  = ( $encounter->has_insurance ) ? "Insurance" : "Cash";
		
		return $this->save();
	}

}