<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Encounter_Invoice extends FPDF{
	
	private $_patient;

	private $_encounter;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Patient health history', 1 );
	}

	function Header()
	{	
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Factura", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(32 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$this->_patient->last_name.' '.$this->_patient->name.' '.$this->_patient->middle_name , $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"ID de consulta", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->id, $this->_border ) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(32 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$this->_patient->date_of_birth, $this->_border) ;

	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"Firmado el", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->signed_at, $this->_border) ;
	   	/*
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(20 ,4,"Signed by", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->user_signed, $this->_border);
	    */
	    //Line_3
	    $this->Ln(5);
	    $this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"ID del paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$this->_patient->id, $this->_border) ;
	    
	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);

	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}

	function body( $params  )
	{	
		$this->_patient   = $params['patient'];
		$this->_encounter = $params['encounter'];
		
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		
		$this->SetFont('Arial','',10);

		$this->_cellLeftSide([
			'Visita a consultorio' => $params['invoice']->office_visit,
			'Laboratorio' => $params['invoice']->laboratories,
			utf8_decode('Inyecciónes / Vacunas') => $params['invoice']->injections,
			'Medicamentos' => $params['invoice']->medications,
			'Procedimientos' => $params['invoice']->procedures,
			'INS Physical' => $params['invoice']->physical,
			'ECG' => $params['invoice']->ecg,
			'Ultrasonido' => $params['invoice']->ultrasound,
			'Rayos X' => $params['invoice']->x_ray
		]);

		$this->_cellRightSide([
			'Sub Total' => $params['invoice']->subtotal,
			'Saldo pendiente' => $params['invoice']->open_balance,
			'Tipo de descuento	' => $params['invoice']->discount_type,
			'Descuento' => $params['invoice']->discount,
			'Total' => $params['invoice']->total,
			'Pago' => $params['invoice']->paid,
			'Tipo de pago' => $params['invoice']->payment_type,
			'Total a pagar	' => $params['invoice']->balance_due
		]);
	}

	private function _cellLeftSide( $data )
	{
		foreach ($data as $key => $value) {
		
			$this->Cell(50, 4, $key ,$this->_border,0,'L', 1 );
			$this->setX( $this->getX() + 1);
			$this->Cell(45, 4, $value ,$this->_border,0,'L' );
			$this->ln(5);
		}
	}

	private function _cellRightSide( $data )
	{
		$this->setY(33);
		foreach ($data as $key => $value) {
			$this->setX(110);
			$this->Cell(50, 4, $key ,$this->_border,0,'L', 1 );
			$this->setX( $this->getX() + 1);
			$this->Cell(45, 4, $value ,$this->_border,0,'L' );
			$this->ln(5);
		}
	}
}