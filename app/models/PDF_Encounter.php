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
	    $this->MultiCell(0 ,6,"Encounter", $this->_border,'C',1);
	    $this->ln(1);
	    //Line_1
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(20 ,4,"Patient", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->last_name.' '.$patient->name.' '.$patient->middle_name , $this->_border) ;
	    $this->SetFont('Arial','B',8);
	    $this->Cell(20 ,4,"Number", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->id, $this->_border ) ;
	    //Line_2
	    $this->Ln(5);
	    $this->SetFont('Arial','B',8);$this->SetX(40);
	    $this->Cell(20 ,4,"DOBasdsa", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->date_of_birth, $this->_border) ;

	    $this->SetFont('Arial','B',8);
	    $this->Cell(20 ,4,"Signed at", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	  	
	    $this->Cell(50 ,4, $this->_encounter->signed_at_format , $this->_border) ;
	   	
	    //Line_3
	    $this->Ln(5);
	    $this->SetX(40);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(20 ,4,"Patient id", $this->_border,0,'L',1) ;
	    $this->SetFont('Arial','',8);
	    $this->Cell(55 ,4,$patient->id, $this->_border) ;
	    
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(20 ,4,"Date service", $this->_border,0,'L',1);
	    $this->SetFont('Arial','',8);
	    $this->Cell(50 ,4,$this->_encounter->create_at, $this->_border);
	    

	    $this->Line(10,32, 206, 32);
	    $this->SetXY(10, 33);
	}

	
	function Footer()
	{	
	    $this->SetY(-20);
	    $this->SetFont('Arial','B',8);
	   	$this->Cell(40 ,4,"Signed by", $this->_border,0,'L',1);
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
		

		$this->_cell( 'Chief complaint', $encounter->chief_complaint);
		if($encounter->current_medications)
		{
			$this->_cell( 'Current medication', $encounter->current_medications);	
		}
		
		if(!$encounter->hide_job)
		{
			$this->_cell( 'Incident relation', [
				'Employment' => $encounter->condition_employment,
				'Autoaccident' => $encounter->condition_autoaccident,
				'State' => $encounter->condition_state,
				'Other accident' => $encounter->condition_other_accident
			]);	
		}
		

		if(!$encounter->hide_heart)
		{	
			$this->_cell('Heart Vitals' , [
				'Pulse' => $encounter->heart_pulse,
				'Respiratory' => $encounter->heart_respiratory,
				'Temperature' => $encounter->heart_temperature,
				'Hemoglobin' => $encounter->heart_hemoglobin,
				'Hematocrit' => $encounter->heart_hematocrit,
				'Head Circ' => $encounter->heart_head_circ,
				'LMP' => $encounter->heart_last_menstrual_period
			]);
		}
		
		if(!$encounter->hide_physical)
		{	
			$this->_cell('Physical Vitals', [
				'Birth weight' => $encounter->physical_birth_weight,
				'Weight' => $encounter->physical_weight,
				'Height' => $encounter->physical_height,
				'BMI' => $encounter->physical_bmi,
				'BP Sys' => $encounter->blood_pressure_sys,
				'BP Dia' => $encounter->blood_pressure_dia,
			]);
		}
			
		if(!$encounter->hide_eye || !$encounter->hide_audio)
		{
			if($encounter->eye_questions==1)
			{
				$this->_cell('Eyes and ears' , [
					'Left eye' => $encounter->eye_withglasses_left,
					'Right eyes' => $encounter->eye_withglasses_right,
					'Both eyes' => $encounter->eye_withglasses_both,
					'Prescription glasses?' => $encounter->eye_prescription_glasses,
					'Worn during exam?' => $encounter->eye_worn_during_exam,
					'Left 1000 audio' =>  $encounter->audio_left_1000 ,
					'Left 2000 audio' => $encounter->audio_left_2000,
					'Left 3000 audio' => $encounter->audio_left_3000,
					'Left 4000 audio' => $encounter->audio_left_4000,
					'Right 1000 audio' => $encounter->audio_right_1000,
					'Right 2000 audio' => $encounter->audio_right_2000,
					'Right 3000 audio' => $encounter->audio_right_3000,
					'Right 4000 audio' => $encounter->audio_right_4000,
				]);
			}
			else
			{
				$this->_cell('Eyes and ears' , [
					'Left eye W/ glasses' => $encounter->eye_withglasses_left,
					'Right eye W/ glassess' => $encounter->eye_withglasses_right,
					'Both eyes W/ glasses' => $encounter->eye_withglasses_both,
					'Left eye W/Out glasses' => $encounter->eye_withoutglasses_left,
					'Right eye W/Out glasses' => $encounter->eye_withoutglasses_right,
					'Both eyes W/Out glasses' => $encounter->eye_withoutglasses_both,
					'Left 1000 audio' =>  $encounter->audio_left_1000 ,
					'Left 2000 audio' => $encounter->audio_left_2000,
					'Left 3000 audio' => $encounter->audio_left_3000,
					'Left 4000 audio' => $encounter->audio_left_4000,
					'Right 1000 audio' => $encounter->audio_right_1000,
					'Right 2000 audio' => $encounter->audio_right_2000,
					'Right 3000 audio' => $encounter->audio_right_3000,
					'Right 4000 audio' => $encounter->audio_right_4000,
				]);
			}
			
		}
		
		if(!$encounter->hide_urinalysis)
		{
			$this->_cell('Urinalysis Vitals', [
				'Color' => $encounter->urinalysis_color,
				'Gravity' => $encounter->urinalysis_specific_gravity,
				'PH' => $encounter->urinalysis_ph,
				'Protein' => $encounter->urinalysis_protein,
				'Glucose' => $encounter->urinalysis_glucose,
				'Ketones' => $encounter->urinalysis_ketones,
				'Bilirubim' => $encounter->urinalysis_bilirubim,
				'Blood' => $encounter->urinalysis_blood,
				'Leuktocytes' => $encounter->urinalysis_leuktocytes,
				'Nitrite' => $encounter->urinalysis_nitrite,
				'HCG' => $encounter->urinalysis_human_chorionic_gonadotropin,
			]);
		}
		
		if($encounter->present_illness_history)
		{
			$this->_cell('Present illness history', $encounter->present_illness_history );
		}
		

		if($encounter->physical_examinations)
		{
			$physical_examinations = [];
			foreach ($encounter->physical_examinations as $examination ) {
				$physical_examinations[$examination->title] = $examination->content;
			}
			$this->_cell('Physical examinations', $physical_examinations );
		}

		if($encounter->diagnostics)
		{
			$diagnosis = [];
			foreach ($encounter->diagnostics as $diagnostic ) {
				$comment = $diagnostic->comment;
				$comment.= ($diagnostic->chronic) ? ', (Crhonic)' : ''; 
				$diagnosis[] = $comment;
			}		
			$this->_cell('Diagnostics', $diagnosis );
		}

		if($encounter->procedure_patient_education)
		{	
			$this->_cell('Procedures', [
				'Pt. Education' => $encounter->procedure_patient_education
			]);
		}

		if($encounter->medications)
		{	
			$medications = [];
			foreach ($encounter->medications as $med ) {
				$content = " (  Amount: $med->amount )";
				$title = $med->title;
				//for old reasons
				if($med->dose != '')
					$title.= ' '.$med->dose;
				
				$medications[$title] = $content." Directions: ".$med->directions;
			}
			$this->_cell('Medications', $medications );
		}

		if($encounter->results)
		{	
			$results = [];
			foreach ($encounter->results as $result ) {
				$results[$result->title] = "({$result->type_result}), {$result->comments}";
			}
			$this->_cell('Results',  $results);
		}	


		if($encounter->referrals)
		{	
			$referrals = [];
			foreach ($encounter->referrals as $referr ) {
				$text = $referr->acuity." (Service: {$referr->service}) Comments: ".$referr->reason;
				$referrals[$referr->speciality] = $text;
			}	
			$this->_cell('Referrals', $referrals );
		}
		$encounter_child = $encounter->encounter_child;
		if($encounter_child->id)
		{
			if($encounter_child->show_basic)
			{
				$this->_cell('Child physical', [
					'Ethnic Code' => $encounter_child->ethnic_string,
					'Type of Screen' => $encounter_child->type_of_screen,
					'Referred to WIC' => $encounter_child->referred_to_wic,
					'Enrolled in WIC' => $encounter_child->enrolled_in_wic,
					'Treatment' => $encounter_child->treatment,
					'Assessment' => $encounter_child->assessment,
					'TB risk' => $encounter_child->tb_risk,
					'Lead risk' => $encounter_child->lead_risk
				]);
			}
			if($encounter_child->show_interval_history)
			{
				$this->_cell('Child interval history', [
					'Diet' => $encounter_child->referred_to_wic,
					'Immunization' => $encounter_child->interval_history_immunization,
					'Problems' => $encounter_child->interval_history_problems,
					'Illness' => $encounter_child->interval_history_illness,
					'Parental concerns' => $encounter_child->interval_history_parental_concerns,

				]);
			}

			if($encounter_child->show_development)
			{
				$this->_cell('Child development	', [
					'Result' => $encounter_child->development_result,
					'Checked' => str_replace(",",", ", $encounter_child->development_options),
					'Plans' => str_replace(",",", ", $encounter_child->development_plan),
					'Educations' => str_replace(",",", ", $encounter_child->educations)
				]);
			}

			if($encounter_child->show_examination)
			{	
				$this->_cell('Child examination', [
					'General appearance' => $encounter_child->physical_result_general_appearance.', '.$encounter_child->physical_comments_general_appearance,
					'Nutrition' => $encounter_child->physical_result_nutrition.', '.$encounter_child->physical_comments_nutrition,
					'Skin' => $encounter_child->physical_result_skin.', '.$encounter_child->physical_comments_skin,
					'Head, neck & nodes' => $encounter_child->physical_result_head_neck_nodes.', '.$encounter_child->physical_comments_head_neck_nodes,
					'Eyes/ Eq reflex' => $encounter_child->physical_result_eyes_eq_reflex.', '.$encounter_child->physical_comments_eyes_eq_reflex,
					'ENT/Hearing' => $encounter_child->physical_result_ent_hearing.', '.$encounter_child->physical_comments_ent_hearing,
					'Mouth/Dental' => $encounter_child->physical_result_mouth_dental.', '.$encounter_child->physical_comments_mouth_dental,
					'Chest/Lungs' => $encounter_child->physical_result_chest_lungs.', '.$encounter_child->physical_comments_chest_lungs,
					'Heart' => $encounter_child->physical_result_heart.', '.$encounter_child->physical_comments_heart,
					'Abdomen' => $encounter_child->physical_result_abdomen.', '.$encounter_child->physical_comments_abdomen,
					'Ext. genitalia' => $encounter_child->physical_result_external_genitalia.', '.$encounter_child->physical_comments_external_genitalia,
					'Back' => $encounter_child->physical_result_back.', '.$encounter_child->physical_comments_back,
					'Extremities/Hips' => $encounter_child->physical_result_extremities_hips.', '.$encounter_child->physical_comments_extremities_hips,
					'Neurological' => $encounter_child->physical_result_neurological.', '.$encounter_child->physical_comments_neurological,
					'Fem. pulses' => $encounter_child->physical_result_fem_pulses.', '.$encounter_child->physical_comments_fem_pulses,
				]);
			}

			if($encounter_child->show_tobacco)
			{	
				$this->_cell('Child tobacco assessment', [
					'Patient exposed' => $encounter_child->tobacco_patient_exposed,
					'Used by patient' => $encounter_child->tobacco_used_by_patient,
					'Prevention referred' => $encounter_child->tobacco_prevention_referred
				]);
			}
		}

		if($encounter->next_appointment)
		{
			$this->_cell('Next Appointment', $encounter->next_appointment );
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
	}


	private function _cell( $text, $result )
	{
		
		$this->SetFont('Arial','B',10);
		$this->setX(10);
		$this->Cell(0, 4, $text , $this->_border , 0, 'L', 1 );
		$this->Ln(4);
		
		if( is_array($result))
		{	
			
			$this->SetFontStyle( array('B','') );
			foreach ($result as $key => $value) {
				$this->setX(30);

				if($value === '' || ( is_numeric($value) && (int)$value===0 ) ) 
					continue ;

				$splitBold = explode("|||", $value);
				if(isset($splitBold[1]))
				{
					$this->SetWidths( array(50,125) );
					$keyName = $splitBold[0];
					$value = $splitBold[1];
				}
				else if(is_numeric($key))
				{		
					$this->SetWidths( array(0,175) );
					$keyName = '';
					$value = '° ' . $value;
				}
				else
				{
					$this->SetWidths( array(50,125) );
					$keyName = $key;
				}

				$this->Row(Array( $keyName, $value ) );
				
			}
		}
		else if( $result!='' )
		{
			$this->SetFont('Arial','',10);
			$this->setX(10);
			$this->MultiCell(150, 4, $result ,$this->_border );
	
		}
		
	}
}