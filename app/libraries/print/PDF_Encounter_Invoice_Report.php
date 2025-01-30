<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Encounter_Invoice_Report extends PDF_MC_Table{
	
	private $_getData = [];

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 236, 240, 241);
		$this->SetTitle('Invoices Report ', 1 );
	}

	function Header()
	{	
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(52);
	    $this->MultiCell(0 ,6,"Invoices ", $this->_border,'C',1);
	    $this->ln(1);
	    //Line 1
	    $this->SetFont('Arial','B',8);$this->SetX(52);$this->Cell(20 ,4,"Start date", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);$this->Cell(55 ,4, date('m/d/Y', strtotime($this->_get('filters.start_date'))), $this->_border);
		//Line 2
		$this->Ln(5);$this->SetFont('Arial','B',8);$this->SetX(52);$this->Cell(20 ,4,"End date", $this->_border,0,'L',1) ;
		$this->SetFont('Arial','',8);$this->Cell(55 ,4, date('m/d/Y', strtotime($this->_get('filters.end_date'))), $this->_border);
		
		$this->SetDrawColor(0,0,0);
	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);

	    $this->_cell( 'Totals', [
			'Discount' => $this->_get('groups.discount'),
			'Subtotal' => $this->_get('groups.subtotal'),
			'Total' => $this->_get('groups.total'),
			'Balance Due' => $this->_get('groups.balance_due'),
			'Paid' => $this->_get('groups.paid'),
		]);

		$this->SetFont('Arial','B',10);
		
		$this->cell( 16, 4, "Enc ID" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->cell( 60, 4, "Patient" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->cell( 18, 4, "Paid" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->cell( 18, 4, "Total" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->cell( 18, 4, "Subtotal" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->cell( 61, 4, "Payment Details" , $this->_border, 0, 'L', 1);
		$this->setX( $this->getX() +1 );
		$this->ln(5);
		
	}
	
	function Footer()
	{
	    $this->pdf_footer_print();
	}

	function body( $paramsGet, $result )
	{
		$this->_getData = array_merge( $paramsGet, [
			'groups' => $result['total_count']
		]);
		$this->SetDrawColor(236,240,241);

		$this->AddPage('P','Letter');
		$this->AliasNbPages();

		$y = $this->getY();

		$invoices = [];
		foreach ($result['result_data'] as $value) {
			$signedDate = new \DateTime( $value['signed_at']);
			$invoices[$signedDate->format('m/d/Y')][] = $value;
		}

		$this->SetWidths( array(17,61,19,19,19,28,22 ) );
		$this->SetAligns( array('L','L','R','R','R','L','R') );

		foreach ($invoices as $key => $invoiceList) {
			$this->SetFont('Arial','',10);
			
			$this->SetDrawColor(77, 77, 77);
			//
	    	$this->Line(10,$this->getY(), 206, $this->getY());
	    	$this->ln(1);
			$this->cell( 35, 4, "Encounter Date:" , $this->_border, 0, 'R', 1);
			$this->setX( $this->getX() +1 );
			$this->SetFont('Arial','B',10);
			$this->cell( 35, 4, $key, 0, 0, 'L', 0);
			$this->SetFont('Arial','',10);
			$this->ln(5);
			
			foreach ($invoiceList as $invoice) {

				$detailData = $detailStr = [];

				if($invoice['open_balance']>0)
				{
					$detailStr[]  = "Open Balance:"; 
					$detailData[] = number_format($invoice['open_balance'],2);
				}
				if($invoice['discount']>0)
				{
					$detailStr[]  = "Discount:"; 
					$detailData[] = "- " .number_format($invoice['discount'],2);
				}
				if($invoice['office_visit']>0)
				{
					$detailStr[]  = "Office Visit:"; 
					$detailData[] = number_format($invoice['office_visit'],2);
				}
				if($invoice['laboratories']>0)
				{
					$detailStr[]  = "Laboratories:"; 
					$detailData[] = number_format($invoice['laboratories'],2);
				}
				if($invoice['injections']>0)
				{
					$detailStr[]  = "Injections:";
					$detailData[] = number_format($invoice['injections'],2);
				}
				if($invoice['medications']>0)
				{
					$detailStr[]  = "Medications:";
					$detailData[] = number_format($invoice['medications'],2);
				}
				if($invoice['procedures']>0)
				{
					$detailStr[]  = "Procedures:";
					$detailData[] = number_format($invoice['procedures'],2);
				}
				if($invoice['physical']>0)
				{
					$detailStr[]  = "Physical:";
					$detailData[] = number_format($invoice['physical'],2);
				}
				if($invoice['ecg']>0)
				{
					$detailStr[]  = "ECG:";
					$detailData[] = number_format($invoice['ecg'],2);
				}
				if($invoice['ultrasound']>0)
				{
					$detailStr[]  = "Ultrasound:";
					$detailData[] = number_format($invoice['ultrasound'],2);
				}
				if($invoice['x_ray']>0)
				{
					$detailStr[]  = "Co-Pay:";
					$detailData[] = number_format($invoice['x_ray'],2);
				}
				if($invoice['print_cost']>0)
				{
					$detailStr[]  = "Print:";
					$detailData[] = number_format($invoice['print_cost'],2);
				}

				$this->Row([
					$invoice['encounter_id'],
					$invoice['patient'],
					$invoice['paid'],
					$invoice['total'],
					$invoice['subtotal'],
					implode("\n", $detailStr ),
					implode("\n", $detailData )
				]);
			}
		}
		
	}

	/**
	 * recursive get elements
	 */
	private function _get( $key, $default = false, $getData = null  )
	{
		$nameArrays = explode(".", (String)$key);

		$keyName = $nameArrays[0];
		
		array_shift($nameArrays);

		if(is_null($getData))
		{
			$getData = $this->_getData;
		}

		if( isset($getData[$keyName]) )
		{
			if(count($nameArrays))
			{
				return $this->_get( implode(".", $nameArrays), $default ,$getData[$keyName] );
			}
			else
			{
				return $getData[$keyName];
			}
		}
		else
		{
			return $default;
		}
	}

	private function _cell( $title, $content = [],  $coordinates = array(0,1) )
	{
		
		if($coordinates[0])
		{	
			$this->setXY($coordinates[0],$coordinates[1]);
		}
		$this->SetFont('Arial','B',10);
		$this->cell(98,4,$title, $this->_border, 0, 'C', 1); 
		$this->ln(5);


		foreach ($content as $key => $value) {
			if($coordinates[0])
			{		
				$this->setX($coordinates[0]);
			}

			$this->SetFont('Arial','',10);
			$this->Cell(35, 4, $key , $this->_border , 0, 'L', 1 );
			$this->SetFont('Arial','',10);
			$this->Cell(25, 4, $value , $this->_border, 0, 'R', 0);
			$this->ln(5);
		}
		
	}
}