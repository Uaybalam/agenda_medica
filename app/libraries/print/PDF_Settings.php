<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Settings extends FPDF{
	
	private $_border = 0;

	private $settingManagment;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 236, 240, 241);
		$this->SetTitle('History comunications', 1 );
	}

	function Header()
	{
	    
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(52);
	    $this->MultiCell(0 ,6,"List of ".$this->settingManagment['title'], $this->_border,'C',1);
	    $this->Ln(1);
	  	$this->Line(10,32, 206, 32);
	  	$this->ln(5);
	  	$this->setY(33);
	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}	

	function body( $settingManagment )
	{
		$this->settingManagment= $settingManagment;
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		

		foreach ($settingManagment['items'] as $pos =>  $item ) {
			$this->cell(12,4, $pos + 1   , $this->_border, 0 ,'L', 1 );	
			$this->MultiCell(0,4, $item['name'] , $this->_border,'L', 0 );
			$this->ln(1);
		}
	}
}