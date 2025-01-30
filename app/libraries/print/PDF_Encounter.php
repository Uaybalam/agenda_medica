<?php 

include_once __DIR__ . '/pdf/PDF_MC_Table.php';

class PDF_Encounter extends PDF_MC_Table{
	
	private $_encounter;

	private $_border = 0;

	function __construct( $null = null )
	{		
		parent::__construct('P','mm','Letter');
		$this->SetTextColor( 77, 77, 77 );
		$this->SetFillColor( 222, 239, 252);
		$this->SetTitle('Encounter', 1 );

		$this->enableRowFonts = TRUE;
	}

	function Header()
	{	
	    $patient = $this->_encounter->patient;
	    //float w [, float h [, string type [, mixed link]]]]]]
	    $this->Cell(29 ,22,"", $this->_border,0,'L',1);
	    $this->pdf_header_logo();
	    //Title
	    $this->SetFont('Arial','B',12);$this->SetX(40);
	    $this->MultiCell(0 ,6,"Consulta", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(32 ,4,"Paciente", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,utf8_decode($patient->last_name.' '.$patient->name.' '.$patient->middle_name) , $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"Numero de consulta", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->id, $this->_border ) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(32 ,4,"Fecha de nacimiento", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;

	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"Firmado el", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	  	
	    $this->Cell(50 ,4, $this->_encounter->signed_at_format , $this->_border) ;
	   	
	    //Line_3
	    $this->Ln(5);
	    $this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(32 ,4,"ID de paciente", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(32 ,4,"Fecha de servicio", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->create_at, $this->_border);
	    

	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);
	}

	
	function Footer()
	{	
	    $this->SetY(-20);
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(40 ,4,"Firmado por", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(0 ,4,$this->_encounter->user_signed, $this->_border);
	    $this->pdf_footer_print();
	    
	}	

	function body( $encounter = null )
	{	
		$this->_encounter = $encounter;
		$this->AddPage('P','Letter');
		$this->AliasNbPages();
		$this->SetFont('Arial','',10);
		
		$this->_cell( 'Motivo de consulta', iconv('UTF-8', 'UTF-8',$encounter->chief_complaint));

		if($encounter->current_medications)
		{
			$this->_cell( utf8_decode('Medicación actual '), utf8_decode($encounter->current_medications));	
		}
		
		if(!$encounter->hide_job)
		{
			$this->_cell( utf8_decode('Relación con el incidente'), [
				'Empleado' => $encounter->condition_employment,
				'Accidente automovilistico' => $encounter->condition_autoaccident,
				'Estado' => $encounter->condition_state,
				'Otro accidente' => $encounter->condition_other_accident
			]);	
		}
		

		if(!$encounter->hide_heart)
		{	
			$this->_cell(utf8_decode('Signos vitales cardíacos'), [
				'Pulso' => $encounter->heart_pulse,
				'Frecuencia respiratoria' => $encounter->heart_respiratory,
				'Temperatura' => $encounter->heart_temperature,
				'Hemoglobina' => $encounter->heart_hemoglobin,
				'Hematocrito' => $encounter->heart_hematocrit,
				'Circunferencia de la Cabeza' => $encounter->heart_head_circ,
				'Ultima Mestruación' => $encounter->heart_last_menstrual_period
			]);
		}
		
		if(!$encounter->hide_physical)
		{	
			$this->_cell(utf8_decode('Signos vitales físicos'), [
				'Peso al nacer' => $encounter->physical_birth_weight,
				'Peso' => $encounter->physical_weight,
				'Altura' => $encounter->physical_height,
				'IMC' => $encounter->physical_bmi,
				utf8_decode('Presión arterial sistólica') => $encounter->blood_pressure_sys,
				utf8_decode('Presión arterial diastólica') => $encounter->blood_pressure_dia,
			]);
		}
			
		if(!$encounter->hide_eye || !$encounter->hide_audio)
		{
			if($encounter->eye_questions==1)
			{
				$this->_cell('Ojos y oidos' , [
					'Ojo izquierdo' => $encounter->eye_withglasses_left,
					'Ojo derecho' => $encounter->eye_withglasses_right,
					'Ambos ojos' => $encounter->eye_withglasses_both,
					utf8_decode('¿El paciente usa lentes recetados?') => $encounter->eye_prescription_glasses,
					utf8_decode('¿Se usaron lentes durante el examen?') => $encounter->eye_worn_during_exam,
					'Izquierdo 1000' =>  $encounter->audio_left_1000 ,
					'Izquierdo 2000' => $encounter->audio_left_2000,
					'Izquierdo 3000' => $encounter->audio_left_3000,
					'Izquierdo 4000' => $encounter->audio_left_4000,
					'Derecho 1000' => $encounter->audio_right_1000,
					'Derecho 2000' => $encounter->audio_right_2000,
					'Derecho 3000' => $encounter->audio_right_3000,
					'Derecho 4000' => $encounter->audio_right_4000,
				]);
			}
			else
			{
				$this->_cell('Ojos y oidos' , [
					'Ojo izquierda con lentes' => $encounter->eye_withglasses_left,
					'Ojo derecho con lentes' => $encounter->eye_withglasses_right,
					'Ambos ojos con lentes' => $encounter->eye_withglasses_both,
					'Ojo izquierda sin lentes' => $encounter->eye_withoutglasses_left,
					'Ojo derecho sin lentes' => $encounter->eye_withoutglasses_right,
					'Ambos ojos sin lentes' => $encounter->eye_withoutglasses_both,
					'Izquierdo 1000' =>  $encounter->audio_left_1000 ,
					'Izquierdo 2000' => $encounter->audio_left_2000,
					'Izquierdo 3000' => $encounter->audio_left_3000,
					'Izquierdo 4000' => $encounter->audio_left_4000,
					'Derecho 1000' => $encounter->audio_right_1000,
					'Derecho 2000' => $encounter->audio_right_2000,
					'Derecho 3000' => $encounter->audio_right_3000,
					'Derecho 4000' => $encounter->audio_right_4000,
				]);
			}
			
		}
		
		if(!$encounter->hide_urinalysis)
		{
			$this->_cell('Urinarios', [
				'Color' => $encounter->urinalysis_color,
				'Densidad' => $encounter->urinalysis_specific_gravity,
				'PH' => $encounter->urinalysis_ph,
				'Proteina' => $encounter->urinalysis_protein,
				'Glucosa' => $encounter->urinalysis_glucose,
				'Cetonas' => $encounter->urinalysis_ketones,
				'Bilirrubina' => $encounter->urinalysis_bilirubim,
				'Sangre' => $encounter->urinalysis_blood,
				'Leucocitos' => $encounter->urinalysis_leuktocytes,
				'Nitritos' => $encounter->urinalysis_nitrite,
				'HCG' => $encounter->urinalysis_human_chorionic_gonadotropin,
			]);
		}
		
		if($encounter->present_illness_history)
		{
			$this->_cell('Historial de enfermedades actuales', $encounter->present_illness_history );
		}
		

		if($encounter->physical_examinations)
		{
			$physical_examinations = [];
			foreach ($encounter->physical_examinations as $examination ) {
				$physical_examinations[$examination->title] = $examination->content;
			}
			$this->_cell(utf8_decode('Examinaciones físicas'), $physical_examinations );
		}

		if($encounter->diagnostics)
		{
			$diagnosis = [];
			foreach ($encounter->diagnostics as $diagnostic ) {
				$comment = $diagnostic->comment;
				$comment.= ($diagnostic->chronic) ? ', (Crónico)' : ''; 
				$diagnosis[] = utf8_decode($comment);
			}		
			$this->_cell('Diagnosticos', $diagnosis );
		}

		if($encounter->procedure_patient_education)
		{	
			$this->_cell('Procedimientos', [
				utf8_decode('Educación del paciente') => $encounter->procedure_patient_education
			]);
		}

		if($encounter->medications)
		{	
			$medications = [];
			foreach ($encounter->medications as $med ) {
				$content = " (  Cantidad: $med->amount )";
				$title = $med->title;
				//for old reasons
				if($med->dose != '')
					$title.= ' '.$med->dose;
				
				$medications[$title] = $content." Indicaciones: ".$med->directions;
			}
			$this->_cell('Medications', $medications );
		}

		if($encounter->results)
		{	
			$results = [];
			foreach ($encounter->results as $result ) {
				$results[$result->title] = "({$result->type_result}), {$result->comments}";
			}
			$this->_cell('Resultados',  $results);
		}	


		if($encounter->referrals)
		{	
			$referrals = [];
			foreach ($encounter->referrals as $referr ) {
				$text = ($referr->acuity == "Routine" ? "Rutina" : "Urgente")." (Servicio: {$referr->service}) Comentario: ".$referr->reason;
				$referrals[$referr->speciality] = $text;
			}	
			$this->_cell('Derivaciones', $referrals );
		}
		$encounter_child = $encounter->encounter_child;
		if($encounter_child->id)
		{
			if($encounter_child->show_basic)
			{
				$this->_cell(utf8_decode('Examen físico del niño'), [
					'Código Étnico' => $encounter_child->ethnic_string,
					'Tipo de prueba' => $encounter_child->type_of_screen == "Initial" ? "Inicial" : "Periodico",
					'Derivado a WIC' => $encounter_child->referred_to_wic,
					'Inscrito en WIC' => $encounter_child->enrolled_in_wic,
					'Tratamiento' => $encounter_child->treatment,
					utf8_decode('Evaluación') => $encounter_child->assessment,
					'Riesgo de TB' => $encounter_child->tb_risk,
					'Riesgo de plomo' => $encounter_child->lead_risk
				]);
			}
			if($encounter_child->show_interval_history)
			{
				$this->_cell(utf8_decode('Historial de intervalos del niño'), [
					'Dieta' => $encounter_child->referred_to_wic,
					utf8_decode('inmunización') => $encounter_child->interval_history_immunization,
					'Problemas' => $encounter_child->interval_history_problems,
					'Enfermedad' => $encounter_child->interval_history_illness,
					'Preocupaciones de los padres' => $encounter_child->interval_history_parental_concerns,

				]);
			}

			if($encounter_child->show_development)
			{
				$this->_cell(utf8_decode('Desarrolllo del niño'), [
					'Resultado' => $encounter_child->development_result,
					'Opciones' => str_replace(",",", ", $encounter_child->development_options),
					'Plans' => str_replace(",",", ", $encounter_child->development_plan),
					utf8_decode('Educación') => str_replace(",",", ", iconv('UTF-8', 'UTF-8',$encounter_child->educations))
				]);
			}

			if($encounter_child->show_examination)
			{	
				$this->_cell(utf8_decode('Examen del niño'), [
					'Apariencia general' => $encounter_child->physical_result_general_appearance== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_general_appearance),
					utf8_decode('Nutrición') => $encounter_child->physical_result_nutrition== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_nutrition),
					'Piel' => $encounter_child->physical_result_skin== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_skin),
					'Cabeza, cuello y ganglios' => $encounter_child->physical_result_head_neck_nodes== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_head_neck_nodes),
					'Ojos/reflejo equitativo' => $encounter_child->physical_result_eyes_eq_reflex== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_eyes_eq_reflex),
					utf8_decode('Oídos/Audición') => $encounter_child->physical_result_ent_hearing== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_ent_hearing),
					'Boca/Dental' => $encounter_child->physical_result_mouth_dental== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_mouth_dental),
					'Pecho/Pulmones' => $encounter_child->physical_result_chest_lungs== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_chest_lungs),
					utf8_decode('Corazón') => $encounter_child->physical_result_heart== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_heart),
					'Abdomen' => $encounter_child->physical_result_abdomen== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_abdomen),
					'Genitales externos' => $encounter_child->physical_result_external_genitalia== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_external_genitalia),
					'Espalda' => $encounter_child->physical_result_back== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_back),
					'Extremidades/Caderas' => $encounter_child->physical_result_extremities_hips== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_extremities_hips),
					utf8_decode('Neurológico') => $encounter_child->physical_result_neurological== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_neurological),
					'Pulsos femorales' => $encounter_child->physical_result_fem_pulses== "AB" ? "Anormal" : "Normal".', '.utf8_decode($encounter_child->physical_comments_fem_pulses),
				]);
			}

			if($encounter_child->show_tobacco)
			{	
				$this->_cell(utf8_decode('Evaluación de tabaco en el niño'), [
					'Paciente expuesto' => $encounter_child->tobacco_patient_exposed == "Yes" ? "Si" : "No",
					'Usado por el paciente' => $encounter_child->tobacco_used_by_patient == "Yes" ? "Si" : "No",
					utf8_decode('Referido para la prevención') => $encounter_child->tobacco_prevention_referred == "Yes" ? "Si" : "No"
				]);
			}
		}

		if($encounter->next_appointment)
		{
			$this->_cell('Siguiente cita', $encounter->next_appointment );
		}
		
		if($encounter->addendums)
		{	
			$addendums = [];

			foreach ($encounter->addendums as $addendum ) {
				
				$signedAt = new DateTime($addendum->create_at);
		    	
			    if($signedAt->format('Ymd') < 20180901)
			    {
			    	$dateAddendum = $signedAt->format('m/d/Y');
			    }
			    else
			    {
			    	$dateAddendum = $signedAt->format('m/d/Y H:i A');
			    }
				//$addendums["{$dateAddendum}"] = "({$addendum->user}), \t" . $addendum->notes;
				$addendums[] = $dateAddendum . "|||({$addendum->user}) \t" . $addendum->notes;
			}	
			$this->_cell('Addendums', $addendums );
		}

		$this->InFooter = true;
		$this->Footer();
		$this->InFooter = false;
	}


	private function _cell( $text, $result )
	{

		$this->SetFont('Arial','B',10);
		$this->setX(10);
		
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
			
			
			$this->setX(10);
			$this->Cell(0, 9, $text , $this->_border , 0, 'L', 1 );
			$this->Ln(10);

			foreach ($result as $key => $value) 
			{
				$i++;

				$this->setX(30);

				if($value === '' || ( is_numeric($value) && (int)$value===0 ) ) 
					continue ;

				$splitBold = explode("|||", $value);

				if(isset($splitBold[1]))
				{
					$this->SetWidths( array(70,105) );
					$keyName = $splitBold[0];
					$value = $splitBold[1];
				}
				else if(is_numeric($key))
				{		
					$this->SetWidths( array(70,105) );
					$keyName = '';
					$value = '° ' . $value;
				}
				else
				{
					$this->SetWidths( array(70,105) );
					$keyName = $key;
				}


				$this->Row(Array($keyName, utf8_decode($value)),30);
				
				if(count($result) > $i)
				{
					$this->Line(10,$this->GetY(), 206, $this->GetY());
					$this->Ln(1);
				}

			}
		}
		else if( $result!='' )
		{
			$this->setX(10);
			$this->Cell(0, 9, $text , $this->_border , 0, 'L', 1 );
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