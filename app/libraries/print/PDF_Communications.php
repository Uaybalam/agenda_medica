<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Communications extends PDF_MC_Table{
	
	private $_patient;

	private $_filter_from = '';

	private $_filter_to = '';

	private $_border = 0;

	private $_types;

	private $_row_fill = 0;

	private $_Y = 100;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Historial de comunicationes', 1 );
	}

	function Header()
	{
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Historial de comunicationes", $this->_border,'C',1);
	    $this->Ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->last_name.' '.$patient->name.' '.$patient->middle_name , $this->_border) ;
	   	if($this->_filter_from!='')
	   	{
	   		$this->SetFont('Arial','B',8);
		    $this->Cell(35 ,4,"Desde", $this->_border,0,'L',1);
		    $this->SetFont('Arial','',8);
		    $this->Cell(50 ,4,$this->_filter_from, $this->_border ) ;
	   	}
	    
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	    if($this->_filter_to!='')
	    {
	    	$this->SetFont('Arial','B',8);
		   	$this->Cell(20 ,4,"A", $this->_border,0,'L',1);
		    $this->SetFont('Arial','',8);
		    $this->Cell(50 ,4,$this->_filter_to, $this->_border);
	    }
	    

	    //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(35 ,4,"ID del paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    
	    $this->Line(10,32, 206, 32);
	    
	    $this->SetXY(10, 33);
	    $this->SetFont('Arial','B',10);
	    $this->cell(85,6,utf8_decode('Información del sistema'), $this->_border, 0 , 'L', 1 );
	    $this->setX(96);
	    $this->cell(110,6,'Comentarios', $this->_border, 0 , 'L', 1 );
	    $this->ln(8);
	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}	

	function body( $patient, $comunications , $param )
	{
		
		$this->_types       = $param['type_communications'];
		$this->_filter_from = $param['filter_from'];
		$this->_filter_to   = $param['filter_to'];
		$this->_patient     = $patient;

		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		$this->setY(40);
		$this->setWidths(Array(85,110));
		foreach ($comunications as $communication ) {
			$user = utf8_decode( $communication->user_full_name );
			$date = date('m/d/Y H:i A', strtotime($communication->create_at));
			$type = $this->_types[$communication->type]['title'];
			$this->row(Array("$user\n$date\n$type", trim(utf8_decode($communication->notes)) ));
		}
	}
	
}