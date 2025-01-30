<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Vaccine extends PDF_MC_Table{
	
	private $_patient;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Vaccines', 1 );
	}

	function Header()
	{
	    $patient = $this->_patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,21.2,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Vacunas", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,utf8_decode($patient->last_name.' '.$patient->name.' '.$patient->middle_name) , $this->_border) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(35 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	     //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(35 ,4,"ID del Paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    $this->SetTextColor( 255, 255, 255 );
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
		$this->SetWidths(array(8,24,31,23,33,35,42));
		$this->SetFont('Arial','',10);
		$this->SetXY(10, 33);

		$titles = Array();
		foreach ($param['vaccines_data'] as $key => $value) {

			if( !in_array( $value['title'] , $titles ) )
			{
				$titles[] = utf8_decode($value['title']);
				$this->ln(8);
				$this->SetFont('Arial','B',10);
				$this->Cell(0, 4, utf8_decode($value['title']) , $this->_border , 0, 'L', 0 );
				$this->SetFont('Arial','',10);
				$this->ln(5);
				$this->Cell(10, 4, utf8_decode("Núm") , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(22, 4, "Fecha" , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(30, 4, utf8_decode("Núm de fab. y lote") , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(22, 4, "Vencimiento" , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(32, 4, "Subtitulo" , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(34, 4, "Sitio" , $this->_border , 0, 'L', 1 );
				$this->setX( $this->getX() + 1 );
				$this->Cell(42, 4, "Administtrado por" , $this->_border , 0, 'L', 1 );
				$this->ln(5);
			}
			
			$this->Row(Array(
				$value['number'],
				$value['date_given'],
				$value['code'],
				$value['exp_date'],
				$value['subtitle'],
				$value['site'],
				$value['administered_by'],
			));
		}
		
		$this->_print_tb( $param['tb'] );

	}

	function filter( $search,  $data )
	{
		$response  = [];
		foreach ( $search as $value) {
			$value  = (array)$value;
			$add 	= TRUE;
			foreach ($data as $key => $data_tmp) {
				if($value[$key] != $data_tmp )
				{
					$add = FALSE;
					break;
				}
			}	
			if($add) $response[] = $value;
		}
		return $response;
	}

	private function _print_tb( $tb = null )
	{	
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','B',10);
		$this->SetXY(10, 33);
		$this->Cell(0, 8, 'Tuberculosis' , $this->_border , 0, 'L', 1 );
		$this->SetFont('Arial','',10);
		$this->setY( $this->getY() + 4 );

		$this->_line('Tipo',  $tb->type );
		$this->_line('Resultado',  $tb->result );
		$this->_line(utf8_decode('Tamaño'),  $tb->size );
		$this->_line('Fecha',  $tb->date );
		$this->_line(utf8_decode('Induración'),  $tb->induration);
		$this->_line('Leido por',  $tb->read_by);
		$this->_line('Fecha de lectura',  $tb->date_read);
		$this->_line('Riesgo',  $tb->risk_assessment );	
		$this->_line(utf8_decode('Radiografía de tórax'),  $tb->chest_x_ray );
		$this->_line('Tratamiento administrado',  $tb->treatment_given );
		$this->_line('Fecha de inicio del tratamiento',  $tb->treatment_start_date );
		$this->_line('Fecha de fin del tratamiento',  $tb->treatment_end_date );
	}


	private function _line( $name, $value = '')
	{
		$this->ln(5);
		
		$this->Cell(55, 5,  $name , $this->_border , 0, 'L', 1 );
		$this->setX( $this->getX() + 1 );
		$this->setY( $this->getY() + 1 );
		$this->Cell(0, 6, $value , $this->_border , 0, 'L', 0 );
	}

}