<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Health_History extends FPDF{
	
	private $_patient;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Historial de salud del paciente', 1 );
	}

	function Header()
	{
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Historial de salud del paciente", $this->_border,'C',1);
	    $this->Ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->full_name , $this->_border) ;
	    $this->SetFont('Arial','B',8);$this->SetX(127);
	    $this->Cell(35 ,4,"Usuario que capturo", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(59 ,4,$patient->user_capture, $this->_border);
	    
	    //Line_2 date_capture
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	    $this->SetFont('Arial','B',8);$this->SetX(127);
	    $this->Cell(35 ,4,"Fecha de captura", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(59 ,4,$patient->date_capture, $this->_border);

	    //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(35 ,4,"ID del paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    
	    $this->Line(10,32, 206, 32);

	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}

	function body( $patient , $health_history = null )
	{	
		$this->_patient = $patient;
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','B',10);
		//$this->SetXY(10, 33);

		//$this->setXY();
		$x = 10;
		$y = 33;
		
		foreach ($health_history as $position => $data_position )
		{
			$this->setXY( $x , $y);

			foreach ($data_position as $group => $data_group) 
			{

				$this->setX($x);
				$this->SetFont('Arial','B',10);
				$this->Cell( 64 , 4,utf8_decode($group), $this->_border , 0 , 'C', 1);
				$this->ln(5);
				$this->SetFont('Arial','B',8);
				$this->setX($x);
				$this->Cell( 32 , 4,'Titulo', $this->_border, 0,'L', 1);
				$this->setX($x + 33);
				$this->Cell( 15 , 4,'Paciente', $this->_border, 0,'L', 1);
				$this->setX($x + 49);
				$this->Cell( 15 , 4,'Familiar', $this->_border, 0,'L', 1);
				$this->ln(5);
				$this->SetFont('Arial','',10);

				foreach ($data_group as $key => $data) 
				{
					$y_tmp_1 = $this->getY();
					$this->setX($x);
					$this->SetFont('Arial','',8);
					$this->MultiCell( 32 , 4, utf8_decode($data->title), $this->_border ,'L',1 );
					
					$y_tmp_2 = $this->getY() + 1;
					$this->setXY($x, $y_tmp_1 );
					
					$this->setX($x + 33);
					$this->Cell( 15 , 4,$data->patient == "Yes" ? "Si" : $data->patient, $this->_border);
					$this->setX($x + 49);
					$this->Cell( 15 , 4,$data->family == "Yes" ? "Si" : $data->family, $this->_border);
					//$this->setX($x);$this->Cell( 20 , 4,$data->family, $this->_border);
					$this->ln( $y_tmp_2 - $y_tmp_1);
				}

				$ln =  4;

				if($group == "Básicos"){ $ln = 8; }
				if($group == "Problemas cardíacos"){ $ln = 30; }
				if($group == "Musculoesquelético"){ $ln = 25; }

				$this->ln($ln);
			}
			
			$x+= 66;
			$y = 33;
		}
		
	}
}