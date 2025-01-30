<?php 
include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Patient_List extends PDF_MC_Table{
	
	private $_total_count = 0;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 236, 240, 241);
		$this->SetTitle('Demographics', 1 );
	}

	function Header()
	{	
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(52);
	    $this->MultiCell(0 ,6,"Patient list", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(52);
	    $this->Cell(20 ,4,"Total", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4, $this->_total_count , $this->_border) ;
	    //Line_2
	 	
	    if( ( isset($_GET['filters']['names']) && $_GET['filters']['names']!='') 
	    	|| (isset($_GET['filters']['last_name']) && $_GET['filters']['last_name']!='' ))
	    {
	    	$this->Ln(5);
		    $this->SetFont('Arial','B',8);$this->SetX(52);
		    $this->Cell(32 ,4,"Filter by patient name", $this->_border,0,'L',1) ;
		    $this->SetFont('Arial','',8);
		    $this->Cell(55 ,4,trim( $_GET['filters']['names'].' '.$_GET['filters']['last_name']), $this->_border) ;
	    }
	    if(isset($_GET['filters']['date_of_birth']) && ($_GET['filters']['date_of_birth']!='') )
	    {
	    	$this->Ln(5);
		    $this->SetFont('Arial','B',8);$this->SetX(52);
		    $this->Cell(32 ,4,"Filter by DOB", $this->_border,0,'L',1) ;
		    $this->SetFont('Arial','',8);
		    $this->Cell(55 ,4, $_GET['filters']['date_of_birth'], $this->_border) ;
	    }
	    if(isset($_GET['filters']['insurance']) && ($_GET['filters']['insurance']!='') )
	    {
	    	$this->Ln(5);
		    $this->SetFont('Arial','B',8);$this->SetX(52);
		    $this->Cell(32 ,4,"Filter by Insurance", $this->_border,0,'L',1) ;
		    $this->SetFont('Arial','',8);
		    $this->Cell(55 ,4, $_GET['filters']['insurance'], $this->_border) ;
	    }

	    $y = $this->getY()+ 6;
	   	 
	    $this->SetY( ($y<28 ? 28 : $y ) );
	    $this->SetFont('Arial','B',10);
	    $this->Cell(20 ,4,"Number", 0 ,0,'L',1) ;
	    $this->SetX( $this->getX()+ 1 );$this->Cell(73 ,4,"Patient", 0 ,0,'L',1) ;
	    $this->SetX( $this->getX()+ 1 );$this->Cell(18 ,4,"DOB", 0 ,0,'L',1) ;
	    $this->SetX( $this->getX()+ 1 );$this->Cell(40 ,4,"Insurance", 0 ,0,'L',1) ;
	    //$this->SetX(52);$this->Cell(20 ,4,"Patient", 0 ,0,'L',1) ;
	    
	    $this->setY($this->getY() + 4);
	}

	function Footer()
	{	
	    $this->pdf_footer_print();
	}	

	function body( $result )
	{		
		$this->_total_count = $result['total_count'];

		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		
		$this->SetWidths( array( 20, 74, 20, 40 ) );
		$this->SetAligns( array('L','L','L', 'L' ) );
		foreach ($result['result_data'] as $patient) {
			
			$this->Row([
				$patient['id'],
				$patient['names'].' '.$patient['last_name'],
				$patient['date_of_birth'],
				$patient['insurance_primary_plan_name'],
			]);
			
		}
		
	}

}