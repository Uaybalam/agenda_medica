<?php 

include_once __DIR__ . '/pdf/FPDF.php';

class PDF_Patient extends FPDF{
	
	private $_patient;

	private $_border = 0;

	private $maritalstatus = [
		'Unspecified' => 'No especificado',
		'Single' => 'Soltero',
		'Married' => 'Casado',
		'Other' => 'Otra',
	];

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Demograficos', 1 );
	}

	function Header()
	{	
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Demograficos", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,utf8_decode($patient->last_name.' '.$patient->name.' '.$patient->middle_name), $this->_border) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	     //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(35 ,4,"ID de paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    $this->Line(10,33, 206, 33);
	    $this->Ln(10);
	}

	
	function Footer()
	{	
	    $this->pdf_footer_print();
	}	

	function body( $patient, $param  = null )
	{		 
		$this->_patient = $patient;
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		$this->SetXY(10, 34);
		
		$this->_cell('About patient', [
			'Apellido' => utf8_decode($patient->last_name),
			'Nombre' => utf8_decode($patient->name),
			'Segundo nombre' => utf8_decode($patient->middle_name),
			'Genero' => $patient->gender == "Male" ? "Masculino" : "Femenino", 
			'Etnicidad' => utf8_decode($patient->ethnicity),
			'Tipo sanguineo' => $patient->blood_type,
			'Idioma' => utf8_decode($patient->language),
			'Requiere interprete' =>  $patient->interpreter_needed,
			//'Was advance directive offered?' => $patient->advanced_directive_offered,
			//'Was advance directive taken?' => $patient->advanced_directive_taken
		]);	

		$this->setX( 10 );
		
		$maritalStatus = isset($param['marital_status'][$patient->marital_status]) ? $this->maritalstatus[$param['marital_status'][$patient->marital_status]] : '';

		//$this->cell(80,4,'Was advance directive offered?', $this->_border, 0, 'L', 1); 
		//$this->cell(17,4, $patient->advanced_directive_offered , $this->_border, 0, 'L', 0); 
		//$this->ln(5);
		//$this->setX( 10 );
		//$this->cell(80,4,'Was advance directive taken?', $this->_border, 0, 'L', 1); 
		//$this->cell(17,4, $patient->advanced_directive_taken , $this->_border, 0, 'L', 0); 
		//$this->ln(5);

		$pos_y_left = $this->getY();

		$this->_cell('Contacto', [
			'Ciudad'=> utf8_decode($patient->address_city),
			'Estado' => utf8_decode($patient->address_state),
			utf8_decode('Dirección') => utf8_decode($patient->address),
			'Codigo postal' => $patient->address_zipcode,
			utf8_decode('Teléfono') => $patient->phone,
			utf8_decode('Descripción de tel.') => $patient->phone_memo,
			utf8_decode('Teléfono alternativo') => $patient->phone_alt,
			utf8_decode('Descripción de tel. alt.') => $patient->phone_alt_memo,
			'Email' => $patient->email,
			'Estado Civil' => $maritalStatus
		], [110 , 34] );
		
		$pos_y_right = $this->getY();
		$pos_y = ($pos_y_left > $pos_y_right)? $pos_y_left : $pos_y_right;

		$this->setXY( 10 ,  $pos_y );

		$this->_cell('Seguro primario', [
			'Nombre' => $patient->insurance_primary_plan_name,
			'Numero de seguro' => $patient->insurance_primary_identify,
			//'Type' => $param['catalog_type_insurance'][$patient->insurance_primary_type],
			'Notas' => $patient->insurance_primary_notes,
		]);
		
		$this->_cell('Seguro secundario', [
			'Nombre' => $patient->insurance_secondary_plan_name,
			'Numero de seguro' => $patient->insurance_secondary_identify,
			//'Type' => $param['catalog_type_insurance'][$patient->insurance_secondary_type],
			'Notas' => $patient->insurance_secondary_notes,
		], [ 109,  $pos_y ]);

		$pos_y = $this->getY();

		$this->_cell('Entidad Responsable', [
			'Apellido'=> utf8_decode($patient->responsible_last_name),
			'Nombre' => utf8_decode($patient->responsible_name),
			'Segundo nombre' => utf8_decode($patient->responsible_middle_name),
			utf8_decode('Tipo de relación') => utf8_decode($patient->responsible_relationship),
			'Genero' => utf8_decode($patient->responsible_gender != "" ? ($patient->responsible_gender == "Male" ? "Masculino" : "Femenino") : ""), 
			utf8_decode('Teléfono') => $patient->responsible_phone,
			utf8_decode('Teléfono alternativo') => $patient->responsible_phone_alt,
			'Ciudad' => $patient->responsible_address_city,
			'Estado' => $patient->responsible_address_state,
			utf8_decode('Dirección') => $patient->responsible_address,
			'Codigo postal' => $patient->responsible_address_zipcode,
		]);
		
		$this->_cell('Contacto de emergencia', [
			'Apellido'=> utf8_decode($patient->emergency_last_name),
			'Nombre' => utf8_decode($patient->emergency_name),
			'Segundo nomrbe' => utf8_decode($patient->emergency_middle_name),
			utf8_decode('Tipo de relación') => utf8_decode($patient->emergency_relationship),
			'Genero' => utf8_decode($patient->emergency_gender != "" ? ($patient->emergency_gender == "Male" ? "Masculino" : "Femenino") : ""), 
			utf8_decode('Teléfono') => $patient->emergency_phone,
			utf8_decode('Teléfono alternativo') => $patient->emergency_phone_alt,
			'Ciudad' => $patient->emergency_address_city,
			'Estado' => $patient->emergency_address_state,
			utf8_decode('Dirección') => $patient->emergency_address,
			'Codigo postal' => $patient->emergency_address_zipcode,
		], [109, $pos_y ]);

		$pos_y = $this->getY();

		$this->setXY(10,$pos_y);/*
		$this->_cell('Preventions', [
			'Allergies'=> $patient->prevention_allergies,
			'Alcohol' => $patient->prevention_alcohol,
			'Drugs' => $patient->prevention_drugs,
			'Tobacco' => $patient->prevention_tobacco
		]);*/

		$this->_cell('Memebresia', [
			'Nombre' => $patient->membership_name,
			'Fecha' => $patient->membership_date,
			'Tipo' => $patient->membership_type,
			'Notas' => $patient->membership_notes
		], [10, $pos_y]);
		
	}

	private function _cell($title, $data , $coordinates = array(0,1) )
	{
		if($coordinates[0])
		{	
			$this->setXY($coordinates[0],$coordinates[1]);
		}

		$this->SetFont('Arial','B',10);
		$this->cell(98,4,$title, $this->_border, 0, 'C', 1); 
		$this->ln(5);

		foreach ($data as $key => $value) 
		{
			if($coordinates[0])
			{		
				$this->setX($coordinates[0]);
			}

			if(strlen($value) > 27)
			{
				$this->SetFont('Arial','',10);
				$this->Cell(45, round(strlen($value)/27) * 4, $key , $this->_border , 0, 'L', 1 );
				$this->SetFont('Arial','',10);
				$this->MultiCell(50, 4, $value , $this->_border  );
				$this->ln(1);
			}
			else
			{ 
				$this->SetFont('Arial','',10);
				$this->Cell(45, 4, $key , $this->_border , 0, 'L', 1 );
				$this->SetFont('Arial','',10);
				$this->MultiCell(50, 4, $value , $this->_border  );
				$this->ln(1);
			}
		}
	}


}