<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Medications extends PDF_MC_Table{
	
	private $params;

	private $_border = 0;
 
	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 ); 
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('History comunications', 1 );
	}

	function Header()
	{
	    $patient = $this->params['patient'];
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Medicamentos", $this->_border,'C',1);
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
	    $this->cell(30,6,'Titulo', $this->_border, 0 , 'L', 1 );
	    $this->setX(62);
	    $this->cell(20,6,'Dosis', $this->_border, 0 , 'L', 1 );
	    $this->setX(83);
	    $this->cell(20,6,'Cantidad', $this->_border, 0 , 'L', 1 );
	    $this->setX(104);
	    $this->cell(15,6,utf8_decode('Crónico'), $this->_border, 0 , 'L', 1 );
	    $this->setX(120);
	    $this->cell(86,6,'Indicaciones', $this->_border, 0 , 'L', 1 );
	    
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
		$this->setWidths(Array(20,31,21,21,16,86));
		foreach ($this->params['medications'] as $medication ) {
			$this->row(Array(
				$medication->date, 
				trim(utf8_decode($medication->title)),
				$medication->dose,
				$medication->amount,
				$medication->chronic,
				$medication->directions
			));
		}
	}
	
}