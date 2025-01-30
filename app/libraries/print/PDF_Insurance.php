<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Insurance extends FPDF{

	private $_border = 1;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 236, 240, 241);
		$this->SetTitle('Information insurance', 1 );
	}

	function Header()
	{	
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(52);
	    $this->MultiCell(0 ,6,"Information insurance", $this->_border,'C',1);
	  	
	    $this->Line(10,32, 206, 32);

	    $this->Ln(10);
	    $this->SetXY(10, 33);
	    $this->SetFont('Arial','',10);
	    $this->Cell(12, 4, 'Num.' , $this->_border , 0, 'L', 1 );
	    $this->SetX(23);
		$this->Cell(60, 4, 'Insurance name' , $this->_border , 0, 'L', 1 );
		$this->SetX(84);
		$this->Cell(35, 4, 'Total in patients' , $this->_border , 0, 'L', 1 );
		$this->SetXY(10, 38);
	}

	
	function Footer()
	{	
	    $this->pdf_footer_print();
	}	

	function body( $data = null)
	{		
		
		
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		foreach ($data as $key => $value) {
			$this->Cell(12, 4, $key + 1 , $this->_border );
	    	$this->SetX(23);
			$this->Cell(60, 4, $value->title, $this->_border);
			$this->SetX(84);
			$this->Cell(35, 4, $value->total_used , $this->_border );
			$this->ln(4);
		}
		//$this->MultiCell(62, 4, 'Yes' , $this->_border  );
	}

	
}
