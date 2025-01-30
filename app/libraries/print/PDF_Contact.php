<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Contact extends FPDF{
	
	private $_patient;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 236, 240, 241);
		$this->SetTitle('Patient contact', 1 );
	}

	function Header()
	{	
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(52);
	    $this->MultiCell(0 ,6,"Patient contact", $this->_border,'C',1);
	    $this->Ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(52);
	    $this->Cell(20 ,4,"Patient", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->last_name.' '.$patient->name.' '.$patient->middle_name , $this->_border) ;
	    $this->SetFont('Arial','B',8);$this->SetX(52);
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(52);
	    $this->Cell(20 ,4,"DOB", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	    //Line_3
	    $this->Ln(5);$this->SetX(52);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(20 ,4,"Patient id", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    $this->Line(10,32, 206, 32);
	    $this->setXY(10,35);
	    
	}
	
	function Footer()
	{	
	    $this->pdf_footer_print();
	}	
	
	function body( $patient, $communications  )
	{	

		$communication = end($communications);
		$last_contact  = date('m/d/Y H:i A', strtotime($communication->create_at));
		
		$this->_patient = $patient;

		$this->AddPage('P','Letter');
		$this->AliasNbPages();

		$this->SetFont('Arial','',12);
		
		$this->MultiCell(0, 8 , "To patient address: " . $patient->address_zipcode.
			", ".
			$patient->address." ".
			$patient->address_city.", ".
			$patient->address_state , 0 , 'L' );
		
		$this->SetFont('Arial','',14);
		
		$message = \libraries\Administration::getValue('communication_whitout_answer');
		
		$content = str_replace(['{last_date}', '{name}','{last_name}'], [$last_contact,$patient->name, $patient->last_name], $message);
		
		$this->ln(5);
		
		$this->MultiCell(0, 8 , $content , 0 , 'C' );

	}

	private function _cell( $title, $result  )
	{
		$this->SetFont('Arial','',10);
		$this->Cell(49, 4 , $title , $this->_border, 0 , 'L', 1 );
		$this->setX( $this->getX() + 1);
		
		if(!is_array($result))
		{		
			$this->MultiCell(146, 4 , $result, $this->_border );
			$this->ln(1);
		}
		else
		{	

			$result['normal'] = ($result['value']!='') ? $result['normal'] : '';
			$this->Cell(86, 4 , $result['value'], $this->_border );
			$this->Cell(30, 4 , 'Normal', $this->_border , 0 , 'L', 1);
			$this->SetFont('Arial','',10);
			$this->Cell(30, 4 , $result['normal'], $this->_border );
			$this->ln(5);
		}
		
	}	
}