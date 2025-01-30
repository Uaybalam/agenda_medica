<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Diagnosis extends PDF_MC_Table{
	
	private $params;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Diagnostico', 1 );
	}

	function Header()
	{
	    $patient = $this->params['patient'];
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Diagnostico", $this->_border,'C',1);
	    $this->Ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->last_name.' '.$patient->name.' '.$patient->middle_name , $this->_border) ;
	   	
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	   	
	    //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(35 ,4,"ID del paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    
	    $this->Line(10,32, 206, 32);
	    
	    $this->SetXY(10, 33);
	    $this->SetFont('Arial','B',10);
	    $this->cell(20,6,'Fecha', $this->_border, 0 , 'L', 1 );
	    $this->setX(31);
	    $this->cell(20,6,utf8_decode('Crónico'), $this->_border, 0 , 'L', 1 );
	    $this->setX(52);
	    $this->cell(154,6,'Diagnostico', $this->_border, 0 , 'L', 1 );
	    
	    //Dose	Amount	Chronic	Directions
	    $this->ln(8);
	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}	

	function body( $params )
	{
		$this->params = $params;
		$this->AddPage('P','Letter');
		$this->SetFont('Arial','',10);
		
		$this->AliasNbPages();
		$this->setWidths(Array(21,21,153));
		foreach ($this->params['diagnosis'] as $diagnosis ) {
			
			$this->row(Array(
				$diagnosis->signed_at,
				$diagnosis->chronic,
				trim(utf8_decode($diagnosis->comment))
			));
		}
	}
	
}