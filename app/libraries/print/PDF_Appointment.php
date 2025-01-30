<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';


class PDF_Appointment extends PDF_MC_Table{
	
	private $_data;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Appointment PDF', 1 );
	}

	function Header()
	{	
		$patient     = $this->_data['patient_info'];
		$appointment = $this->_data['appointment'];
		$user_info   = $this->_data['user_info'];

		$fullName[] = $patient->name;
		$fullName[] = $patient->middle_name;
		$fullName[] = $patient->last_name;
		
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,22,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Cita ", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(30 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,utf8_decode(implode(" ",array_filter($fullName))), $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(30 ,4,"Numero de cita", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4, $appointment->id, $this->_border ) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(30 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(30 ,4,"Creado Por", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$user_info->nick_name, $this->_border);
	    //Line_3
	    $this->Ln(5);$this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(30 ,4,"Id. de paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(30 ,4,"Creado el", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,date('m/d/Y h:i A', strtotime($appointment->create_at)), $this->_border) ;
	    $this->SetDrawColor(0,0,0);
	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);
	    
		$this->_cell( 'Detalle de cita', [
			'Estatus' => utf8_decode($appointment->status),
			'Tipo' => ( $appointment->type_appointment) ? 'Con cita' : 'Sin cita',
			//'Patient' => implode(" ", array_filter($fullName) ),
			'Codigo' => $appointment->code,
			'Tipo de seguro' => $appointment->insurance_type,
			'Tipo de visita' => $appointment->visit_type,
			'Fecha de cita' => date('m/d/Y h:i A', strtotime($appointment->date_appointment)),
			'Notas' => $appointment->notes
		]);

		$this->_cell('Horario', [
			'Llegada'=> $appointment->time_arrival,
			'Con Enfermera' => $appointment->time_nurse,
			'Cuarto' => $appointment->time_room,
			'Abierto' => $appointment->time_open,
			'Firmado' => $appointment->time_signed,
			'Completado' => $appointment->time_done,
			'Total de tiempo' => utf8_decode($appointment->time_length)
		], [109 , 33] );

		$heigh = 0;
		$rows  = explode("\n",$appointment->notes);

		foreach($rows as $r)
		{
			$heigh+= (4 * ceil(strlen($r)/31));
		}
		$this->setXY(10,$this->getY()+$heigh+2);

		$this->SetFont('Arial','B',10);
		$this->cell(0,8,sprintf("Registro de Eventos (%d)", count($this->_data['events'])), $this->_border, 0, 'C', 1); 
		$this->ln(9);

		$this->cell(10,6,"#", $this->_border, 0, 'C', 1); 
		$this->setX( $this->getX() + 1);
		$this->cell(25,6,"Usuario", $this->_border, 0, 'C', 1); 
		$this->setX( $this->getX() + 1);
		$this->cell(40,6,"Fecha", $this->_border, 0, 'C', 1); 
		$this->setX( $this->getX() + 1);
		$this->cell(45,6,"Evento", $this->_border, 0, 'C', 1);
		$this->setX( $this->getX() + 1); 
		$this->cell(72,6,"Notas", $this->_border, 0, 'C', 1); 
		$this->ln(8);
		$this->SetFont('Arial','',10);
		
	    
	}

	
	function Footer()
	{	
	    $this->pdf_footer_print();
	}

	function body( $data = null )
	{	

		$this->_data      = $data;
		$events           = $this->_data['events'];
		$available_events = $this->_data['available_events'];

		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		
		$this->SetWidths(array(11,25,41,46,74));
		$this->SetAligns(array("C","C","C","C","L"));
		$totalEvents = count($events);
		
		foreach ($events as $key => $evt) {
			
			$num = $totalEvents - $key;

			$this->Row([
				$num,
				$evt->user,
				date('m/d/Y h:i A', strtotime($evt->date)),
				utf8_decode($available_events[$evt->event]['name']),
				utf8_decode(str_replace("Reason Cancel:","Razón de cancelación:",str_replace("Changed by:","Cambiado por:",strip_tags($evt->notes))))
			]);
			
		}

	}


	private function _cell( $title, $content = [],  $coordinates = array(0,1) )
	{
		
		if($coordinates[0])
		{	
			$this->setXY($coordinates[0],$coordinates[1]);
		}
		$this->SetFont('Arial','B',10);
		$this->cell(98,4,$title, $this->_border, 0, 'C', 1); 
		$this->ln(5);
		
		foreach ($content as $key => $value) {
			if($coordinates[0])
			{		
				$this->setX($coordinates[0]);
			}

			$this->SetFont('Arial','',10);

			if($key == "Notas")
			{
				$heigh = 0;
				$rows  = explode("\n",$value);

				foreach($rows as $r)
				{
					$heigh+= (4 * ceil(strlen($r)/31));
				}

				$this->Cell(35, $heigh, $key , $this->_border , 0, 'L', 1 );
			}
			else
			{
				$this->Cell(35, 4, $key , $this->_border , 0, 'L', 1 );
			}
			
			$this->SetFont('Arial','',10);
			$this->MultiCell(62, 4, $value , $this->_border  );
			$this->ln(1);
		}
		
	}
}