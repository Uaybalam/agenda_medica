<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Prescription extends PDF_MC_Table{
	
	private $_encounter;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Receta Medica', 1 );

		$this->enableRowFonts = TRUE;
	}

	function Header()
	{	
	    $patient = $this->_encounter->patient;
	    //float w [, float h [, string type [, mixed link]]]]]] 
	    
		$logo = FCPATH . "../private/uploads/files/instance_".$_SESSION['User_DB']->instance_id."/".\libraries\Administration::getValue('logo');
		$this->Image($logo, 28, 11, 19, 19);

		$this->SetFont('Arial','B',10);
		$this->SetX(0);
		$this->SetY(32);
		$this->MultiCell(60,ceil(strlen(\libraries\Administration::getValue('address_clinic'))/40)*2.5,\libraries\Administration::getValue('address_clinic'), $this->_border,'C',0);

		$this->Ln(0);
		$this->SetXY($this->GetX() + 60, 15);
		$this->SetFont('Arial','B',20);
		$this->MultiCell(70,(strlen(\libraries\Administration::getValue('name'))/40)*3,\libraries\Administration::getValue('name'), $this->_border,'C',0);

		$this->Ln(0);
		 
		$this->SetXY($this->GetX() + 130, 25);
		$this->SetFont('Arial','B',10);
		$this->MultiCell(60,(strlen("Razón de consulta: ".$this->_encounter->chief_complaint)/50)*3,utf8_decode("Razón de consulta: ".$this->_encounter->chief_complaint), $this->_border,'C',0);
		
		$this->SetXY($this->GetX() + 130, ((strlen("Razón de consulta: ".$this->_encounter->chief_complaint)/20)*3)+32);
		$this->SetFont('Arial','B',10);
		$this->MultiCell(60,(strlen(\libraries\Administration::getValue('billing_facility_telephone'))/40)*3,"Tel.: ".\libraries\Administration::getValue('billing_facility_telephone'), $this->_border,'C',0);
		
		$heightAddress = (ceil(strlen(\libraries\Administration::getValue('address_clinic'))/40)*2.5)+45;
		$heightCom     = ((strlen("Razón de consulta: ".$this->_encounter->chief_complaint)/20)*3)+40;
		$linePos = $heightAddress > $heightCom ? $heightAddress : $heightCom;

		$this->SetDrawColor(100, 100, 100);
		$this->Line(10,$linePos, 206, $linePos);
	    $this->SetFont('Arial','B',8);$this->SetXY(10,$linePos+0.5);
	    $this->Cell(20,6," Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(45 ,6,utf8_decode($patient->last_name.' '.$patient->name.' '.$patient->middle_name) , $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(13 ,6," Fecha", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(20 ,6,date("d/m/Y"), $this->_border ) ; 
	    $this->SetFont('Arial','B',8); 
	    $this->Cell(32 ,6," Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,6,$patient->date_of_birth, $this->_border) ;

		$this->Line(10,$linePos+7, 206, $linePos+7);
		$this->SetXY(10, $linePos+10);
	   	  
	}

	
	function Footer()
	{	
		
	    $this->pdf_footer_print();
	    
	}	

	function body( $encounter = null )
	{	
		$this->_encounter = $encounter;
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		
		if($encounter->medications)
		{	
			$medications = [];
			foreach ($encounter->medications as $med ) {
				$content = " Cantidad: $med->amount \t\t";
				$title = $med->title;
				//for old reasons
				if($med->dose != '')
					$title.= ' '.$med->dose;
				
				$medications[$title] = $content." Indicaciones: ".$med->directions;
			}
			
			$this->_cell( $medications );
		}

		$this->SetDrawColor(3,3,3);

		$this->Line(73,$this->GetY()+28, 143, $this->GetY()+28);
		$this->SetXY(10, $this->GetY()+30);
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(0 ,4,"Firma", $this->_border,0,'C',0);
	    $this->SetFont('Arial','',8);
		
		$this->InFooter = true;
		$this->Footer();
		$this->InFooter = false;
	}


	private function _cell( $result )
	{

		$this->SetFont('Arial','B',10);
		
		if( is_array($result))
		{	

			$nb=0;
			$i = 0;

		   	foreach($result as $key => $value) 
		    {
		        $nb = max($nb,$this->NbLines(105,$value));
		    }

		    $h=10*$nb;

			$this->CheckPageBreak($h);
			$this->SetFontStyle( array('B','') );

			foreach ($result as $key => $value) 
			{
				$i++;

				$this->setX(30);

				if($value === '' || ( is_numeric($value) && (int)$value===0 ) ) 
					continue ;

				$splitBold = explode("|||", $value);

				if(isset($splitBold[1]))
				{
					$this->SetWidths( array(70,125) );
					$keyName = $splitBold[0];
					$value = $splitBold[1];
				}
				else if(is_numeric($key))
				{		
					$this->SetWidths( array(70,125) );
					$keyName = '';
					$value = '° ' . $value;
				}
				else
				{
					$this->SetWidths( array(70,125) );
					$keyName = $key;
				}


				$this->Row(Array($keyName, utf8_decode($value)),10);
				
				if(count($result) > $i)
				{
					$this->Line(10,$this->GetY(), 206, $this->GetY());
					$this->Ln(1);
				}

			}
		}
		else if( $result!='' )
		{
			$this->Cell(0, 9, $text, $this->_border , 0, 'L', 1 );
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->MultiCell(150, 8, utf8_decode($result) ,$this->_border );
	
		}
		
	}

	function AddPage($orientation='', $size='', $rotation=0)
	{
		// Start a new page
		if($this->state==3)
			$this->Error('The document is closed');
		$family = $this->FontFamily;
		$style = $this->FontStyle.($this->underline ? 'U' : '');
		$fontsize = $this->FontSizePt;
		$lw = $this->LineWidth;
		$dc = $this->DrawColor;
		$fc = $this->FillColor;
		$tc = $this->TextColor;
		$cf = $this->ColorFlag;
		if($this->page>0)
		{
			// Page footer
			//$this->InFooter = true;
			//$this->Footer();
			//$this->InFooter = false;
			// Close page
			$this->_endpage();
		}
		// Start new page
		$this->_beginpage($orientation,$size,$rotation);
		// Set line cap style to square
		$this->_out('2 J');
		// Set line width
		$this->LineWidth = $lw;
		$this->_out(sprintf('%.2F w',$lw*$this->k));
		// Set font
		if($family)
			$this->SetFont($family,$style,$fontsize);
		// Set colors
		$this->DrawColor = $dc;
		if($dc!='0 G')
			$this->_out($dc);
		$this->FillColor = $fc;
		if($fc!='0 g')
			$this->_out($fc);
		$this->TextColor = $tc;
		$this->ColorFlag = $cf;
		// Page header
		$this->InHeader = true;
		$this->Header();
		$this->InHeader = false;

		// Restore line width
		if($this->LineWidth!=$lw)
		{
			$this->LineWidth = $lw;
			$this->_out(sprintf('%.2F w',$lw*$this->k));
		}
		// Restore font
		if($family)
			$this->SetFont($family,$style,$fontsize);
		// Restore colors
		if($this->DrawColor!=$dc)
		{
			$this->DrawColor = $dc;
			$this->_out($dc);
		}
		if($this->FillColor!=$fc)
		{
			$this->FillColor = $fc;
			$this->_out($fc);
		}
		$this->TextColor = $tc;
		$this->ColorFlag = $cf;
	}
}