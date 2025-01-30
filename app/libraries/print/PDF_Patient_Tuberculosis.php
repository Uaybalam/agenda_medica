<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Patient_Tuberculosis extends FPDF{
	
	private $_patient;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Tuberculosis', 1 );
	}

	function Header()
	{	
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,22,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Tuberculosis", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(30 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->last_name.' '.$patient->name.' '.$patient->middle_name , $this->_border) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(30 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	     //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(30 ,4,"ID del paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    $this->Line(10,32, 206, 32);
	    $this->Ln(10);
	}

	
	function Footer()
	{	
	    $this->pdf_footer_print();
	}	

	function body( $patient, $param  = null )
	{
		$this->_patient = $patient;
		
		$this->_print_tb( $param['tb'] );

	}	

	private function _print_tb( $tb = null )
	{	
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','B',10);
		$this->SetXY(10, 33);
		$this->Cell(0, 4, 'Tuberculosis' , $this->_border , 0, 'L', 1 );
		$this->SetFont('Arial','',10);

		$this->_line('Tipo',  $tb->type );
		$this->_line('Resultado',  $tb->result );
		$this->_line(utf8_decode('Tamaño'),  $tb->size );
		$this->_line('Fecha',  $tb->date );
		$this->_line(utf8_decode('Induración'),  $tb->induration);
		$this->_line('Revisado por',  $tb->read_by);
		$this->_line(utf8_decode('Fecha de revisión'),  $tb->date_read);
		$this->_line('Riesgos',  $tb->risk_assessment );	
		$this->_line(utf8_decode('Radiografía de tórax'),  $tb->chest_x_ray );
		$this->_line(utf8_decode('¿Se proporciono tratamiento?'),  $tb->treatment_given );
		$this->_line('Inicio de tratamiento',  $tb->treatment_start_date );
		$this->_line('Fin de tratamiento',  $tb->treatment_end_date );

	}


	private function _line( $name, $value = '')
	{
		$this->ln(5);
		
		$this->Cell(50, 4,  $name , $this->_border , 0, 'L', 1 );
		$this->setX( $this->getX() + 1 );
		$this->Cell(0, 4, $value , $this->_border , 0, 'L', 0 );
	}

}