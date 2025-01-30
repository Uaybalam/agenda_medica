<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';


class PDF_Referrals extends PDF_MC_Table{
	
	private $_data;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Appointment PDF', 1 );
	}

	function Footer()
	{	
	    $this->pdf_footer_print();
	}

	function Header()
	{
		$patient     = $this->_data['patient'];
		
		
	    $this->SetFont('Arial','B',12);
		$this->MultiCell(0 ,10,\libraries\Administration::getValue('name'), $this->_border,'C',1);
		$this->Cell(28 ,11,"", $this->_border,0,'L',1);
		$this->pdf_header_logo();
		$this->SetDrawColor(0,0,0);
	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);
	    
	    $this->SetFont('Arial','B',12);$this->SetXY(10,33);
	    $this->MultiCell(0 ,8,utf8_decode("Información del paciente"), $this->_border,'C',1);
	    $this->ln(1);
	    $y1 = $this->getY();
	    $this->_headTitle("Apellido", $patient->last_name);
	    $this->_headTitle("Nombre", $patient->name);
	    $this->_headTitle("Segundo nombre", $patient->middle_name);
	    $this->_headTitle(utf8_decode("Dirección"), $patient->address);
	    $this->_headTitle("Ciudad", $patient->address_city);
	    $this->_headTitle("Estado", $patient->address_state);
	    $this->_headTitle(utf8_decode("Código"), $patient->address_zipcode);
	    $yLong = $this->getY();
	    $this->setY($y1);
		$this->_headTitle("Id de paciente", $patient->id, 109 );
		$this->_headTitle("Fecha de nacimiento", $patient->date_of_birth, 109 );
		$this->_headTitle("Genero", $patient->gender, 109 );
		$this->_headTitle(utf8_decode("Teléfono"), $patient->phone, 109 );
		$this->_headTitle("Seguro", $patient->insurance_primary_plan_name, 109 );
		$this->_headTitle("Numero de seguro", $patient->insurance_primary_identify, 109 );
		$this->_headTitle(utf8_decode("Código ICD-10"), $this->_data['post']['icdCode'],109);
		$this->SetFont('Arial','B',12);

		if($yLong >$this->getY() )
		{
			$this->setY($yLong);
		}
		$this->MultiCell(0 ,8,utf8_decode("Proveedor de derivación"), $this->_border,'C',1);
		$this->ln(1);
		$y = $this->getY();
	   	$this->_headTitle("Nombre de doctor", $this->_data['provider']['name'], 0 );
	   	$this->_headTitle(utf8_decode("Teléfono"), $this->_data['provider']['phone'], 0 );
	   	$this->_headTitle("Fax", $this->_data['provider']['fax'], 0 );
	   	$this->_headTitle(utf8_decode("Dirección"), $this->_data['provider']['address'], 0 );
	   	$yLong = $this->getY();
	    $this->setY($y);
	    //$this->_headTitle("NPI#", $this->_data['provider']['npi'], 109 );
	    $this->_headTitle("Contacto de oficina", $this->_data['provider']['office_contact'], 109 );
	   	$this->_headTitle("Firma", $this->_data['provider']['signature'] , 109 );
	    
	    if($yLong > $this->getY())
	    {
	    	$this->setY($yLong);
	    }
	   	$this->SetFont('Arial','B',12);
		$this->MultiCell(0 ,8,utf8_decode("Derivación"), $this->_border,'C',1);
		$this->SetFont('Arial','',10);
		$this->ln(1);
		$y = $this->getY();
		$this->_headTitle('Especialidad', $this->_data['referr']->speciality );
		$this->_headTitle('Servicio', $this->_data['referr']->service );
		$yLong = $this->getY();
		$this->setY($y);
		$this->_headTitle(utf8_decode('Razón'), $this->_data['referr']->reason, 109 );
		$this->_headTitle('Gravedad', $this->_data['referr']->acuity,109 );
		
		if($yLong > $this->getY())
		{
			$this->setY($yLong);
		}
		
		$extraDiagnosis = $this->_data['post']['extraDiagnosis'];
		$diagnosis    	= is_array($this->_data['post']['referrDiagnosis']) ? $this->_data['post']['referrDiagnosis'] : [] ;

		if($extraDiagnosis!='' || count($diagnosis)>0 )
		{
			$this->SetFont('Arial','B',12);
			$this->MultiCell(0 ,8,"Diagnostico", $this->_border,'C',1);
			$this->SetFont('Arial','',10);
		}
		
		if(count($diagnosis) > 0 )
		{
			foreach ($this->_data['post']['referrDiagnosis'] as $item ) {
				//$txtAux = @iconv("UTF-8", "ISO-8859-1", $txt );
				$txt = @iconv('UTF-8', 'cp1252', "• {$item}");
				$this->MultiCell(0 ,6, $txt, $this->_border,'L',0);
			}
		}
		
		if($extraDiagnosisText = $this->_data['post']['extraDiagnosis'])
		{
			$txt = @iconv('UTF-8', 'cp1252', "• {$extraDiagnosisText}");
			$this->MultiCell(0 ,6, $txt, $this->_border,'L',0);
		}
		
		$this->SetFont('Arial','B',12);
		$this->MultiCell(0 ,8,"Servicios solicitados", $this->_border,'C',1);
		$this->ln(1);
	}

	function body( $data = null )
	{
		$this->_data      = $data;

		$this->AddPage('P','Letter');
		$this->AliasNbPages();

		$this->SetFont('Arial','',10);
		$this->MultiCell(0 ,6,$this->_data['post']['servicesRequested'], $this->_border,'L');
	}


	private function _headTitle( $title, $value = '', $x = null )
	{
		if($x)
		{
			$this->setX($x);
		}

		$this->SetFont('Arial','',10);
	    $this->Cell(40 ,5,$title, $this->_border,0,'L',1);
	    $this->SetFont('Arial','',10);
	    $this->MultiCell(71 ,4, $value , $this->_border);
	    $this->ln(1.5);
	} 

}