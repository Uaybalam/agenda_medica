<?php 

include_once __DIR__ . '/pdf/FPDF.php';


class PDF_Active_History extends FPDF{
	
	private $_patient;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Historia clínica activa del paciente', 1 );
	}

	function Header()
	{	
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,utf8_decode("Historia clínica activa del paciente"), $this->_border,'C',1);
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
	    $this->setXY(10,33);
	}

	
	function Footer()
	{
	    $this->pdf_footer_print();
	}	

	function body( $patient, $param  )
	{

		$this->_patient = $patient;
		
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		
		
		//$this->_cell('Surgeries', $param['activeHX']->surgeries );
		$this->_cell(utf8_decode('Antígeno Prostático Específico'), $param['activeHX']->psa );
		$this->_cell(utf8_decode('Última influenza'), $param['activeHX']->last_influenza );
		$this->_cell(utf8_decode('Última clamidia'), $param['activeHX']->last_chlamidia );
		$this->_cell(utf8_decode('Última SHA'), $param['activeHX']->last_sha );
		$this->_cell(utf8_decode('Último colesterol'), $param['activeHX']->last_cholesterol );
		$this->_cell(utf8_decode('Último FOBT'), $param['activeHX']->last_fobt );
		$this->_cell(utf8_decode('Última Colonoscopia'), $param['activeHX']->last_colonoscopy );
		$this->_cell(utf8_decode('Último SIG'), $param['activeHX']->last_sig );
		
		$this->_cell(utf8_decode('Último ECG'), [
			'value' =>  $param['activeHX']->last_ecg,
			'normal' => $param['activeHX']->last_ecg_normal == "Yes" ? "Si" : $param['activeHX']->last_ecg_normal
		] );
		$this->_cell(utf8_decode('Último PPD'), [
			'value' =>  $param['activeHX']->last_ppd,
			'normal' => $param['activeHX']->last_ppd_normal == "Yes" ? "Si" : $param['activeHX']->last_ppd_normal
		] );
		$this->_cell(utf8_decode('Última Vacuna contra el tétanos'), [
			'value' =>  $param['activeHX']->last_tetanous,
			'normal' => $param['activeHX']->last_tetanous_normal == "Yes" ? "Si" : $param['activeHX']->last_tetanous_normal
		] );	
		$this->_cell(utf8_decode('Última vacuna neumocócica'), [
			'value' =>  $param['activeHX']->last_pneumo,
			'normal' => $param['activeHX']->last_pneumo_normal == "Yes" ? "Si" : $param['activeHX']->last_pneumo_normal
		] );

		//$this->ln(4);
		$this->SetFont('Arial','B',10);
		$this->cell(0,4,'Embarazos', $this->_border, 0 , 'C' , 1);
		$this->ln(5);
		$this->_cell('Birth control', $param['activeHX']->pregnancy_birth_control );
		$this->_cell(utf8_decode('Último PAP'), [
			'value' => $param['activeHX']->pregnancy_last_pap,
			'normal' => $param['activeHX']->last_pap_normal == "Yes" ? "Si" : $param['activeHX']->last_pap_normal
		] );
		$this->_cell(utf8_decode('Última mamografía'), [
			'value' => $param['activeHX']->pregnancy_last_mamo,
			'normal' => $param['activeHX']->last_mamo_normal == "Yes" ? "Si" : $param['activeHX']->last_mamo_normal 
		] );
		$this->_cell('Existosos', (int)$param['activeHX']->pregnancy_count_succesfull);
		$this->_cell(utf8_decode('Cesáreas'), (int)$param['activeHX']->pregnancy_count_cesarean );
		$this->_cell('Abortos', (int)$param['activeHX']->pregnancy_count_abortions);
		
		$total =  (int)$param['activeHX']->pregnancy_count_succesfull +
			(int)$param['activeHX']->pregnancy_count_cesarean +
			(int)$param['activeHX']->pregnancy_count_abortions;
		
		$this->_cell('Total', $total);
		$this->SetFont('Arial','B',10);
		$this->cell(0,4,'Prevenciones', $this->_border, 0 , 'C' , 1);
		$this->ln(5);
		$this->_cell('Alergias', str_replace(",",", ",$patient->prevention_allergies) );
		$this->_cell('Alcohol', $patient->prevention_alcohol );
		$this->_cell('Medicamentos', $patient->prevention_drugs );
		$this->_cell('Tabaco', $patient->prevention_tobacco );
		
		$this->AddPage('P','Letter');
		$this->ln(1);
		$this->SetFont('Arial','B',10);
		$this->cell(0,6,'Diagnostico', $this->_border, 0 , 'C' , 1);
		$this->ln(7);
		//$this->SetXY(10, 33);
	    $this->SetFont('Arial','B',10);
	    $this->cell(22,4,'Firmado el', $this->_border, 0 , 'L', 1 );
	    $this->setX(33);
	    $this->cell(20,4,utf8_decode('Crónico'), $this->_border, 0 , 'L', 1 );
	    $this->setX(54);
	    $this->cell(152,4,'Comentarios', $this->_border, 0 , 'L', 1 );
	    $this->ln(5);

	    $this->SetFont('Arial','',10);
	    foreach ($param['diagnosis'] as $diagnosis ) {
			$this->cell(23,4, $diagnosis->signed_at, $this->_border, 0 ,'L' );	
			$this->cell(21,4, $diagnosis->chronic, $this->_border, 0 ,'L' );	
			$this->MultiCell(152,4, $diagnosis->comment, $this->_border,'L',0 );	
		}
		
		$this->AddPage('P','Letter');
		$this->ln(1);
		$this->SetFont('Arial','B',10);
		$this->cell(0,6,'Tratamientos medicados', $this->_border, 0 , 'C' , 1);
		$this->ln(7);
		//$this->SetXY(10, 33);
	    $this->SetFont('Arial','B',10);
	    $this->cell(22,4,'Firmado el', $this->_border, 0 , 'L', 1 );
	    $this->setX( $this->getX() + 1 );
	    $this->cell(42,4,'Titulo', $this->_border, 0 , 'L', 1 );
	    $this->setX( $this->getX() + 1 );
	    $this->cell(20,4,'Dosis', $this->_border, 0 , 'L', 1 );
	    $this->setX( $this->getX() + 1);
	    $this->cell(20,4,'Cantidad', $this->_border, 0 , 'L', 1 );
	    $this->setX( $this->getX() + 1);
	    $this->cell(88,4,'Indicaciones', $this->_border, 0 , 'L', 1 );

	    $this->ln(5);

	   	$this->SetFont('Arial','',10);
	    foreach ($param['medications'] as $medications ) {
			$this->cell(23,4, date('m/d/Y',strtotime($medications->date)), $this->_border, 0 ,'L' );	
			$this->cell(43,4, $medications->title, $this->_border, 0 ,'L' );	
			$this->cell(21,4, $medications->dose, $this->_border, 0 ,'L' );	
			$this->cell(21,4, $medications->amount, $this->_border, 0 ,'L' );	
			$this->MultiCell(88,4, $medications->directions, $this->_border,'L',0 );	
		}

	}



	private function _cell( $title, $result  )
	{
		$this->SetFont('Arial','',10);
		$this->Cell(55, 4 , $title , $this->_border, 0 , 'L', 1 );
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
			if( $result['value'] )
			{
				$this->Cell(30, 4 , 'Normal', $this->_border , 0 , 'L', 1);
				$this->SetFont('Arial','',10);
				$this->Cell(30, 4 , $result['normal'], $this->_border );
			}
			
			$this->ln(5);
		}
		
	}	

}