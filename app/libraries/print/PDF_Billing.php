<?php 

include_once __DIR__ . '/pdf/FPDF_Extends.php';

class PDF_Billing extends FPDF_Extends{
   	
	private $_MarginTop 	= 0;
	private $_MarginLeft 	= 0;
	private $_alignment 	= 'L';
	private $administration;
	private $_facility;

	function __construct( $null = null )
	{	
		parent::__construct('P','mm','Letter');
		$this->SetAutoPageBreak(0);
		
		$this->administration = \libraries\Administration::init();
		
		$this->_MarginLeft = $this->administration->getValue('PDF_Margin_Left');
		$this->_MarginTop  = $this->administration->getValue('PDF_Margin_Top');

		if( defined("PRINT_MARGIN_LEFT") && PRINT_MARGIN_LEFT)
			$this->_MarginLeft = PRINT_MARGIN_LEFT;

		if( defined("PRINT_MARGIN_TOP") && PRINT_MARGIN_TOP)
			$this->_MarginTop = PRINT_MARGIN_TOP;
				
		$this->_facility          = new StdClass;
		$this->_facility->name    = $this->administration->getValue('billing_facility_name');
		$this->_facility->phone   = $this->administration->getValue('billing_facility_telephone');
		$this->_facility->zip     = $this->administration->getValue('billing_facility_zip');
		$this->_facility->address = $this->administration->getValue('billing_facility_streetAddr');
		$this->_facility->city    = $this->administration->getValue('billing_facility_city');
		$this->_facility->state   = $this->administration->getValue('billing_facility_state');
		$this->_facility->npi 	  = $this->administration->getValue('billing_facility_npi'); 
		
		$this->_provider            = new StdClass;
		$this->_provider->name      = $this->administration->getValue('billing_provider_name');
		$this->_provider->npi       = $this->administration->getValue('billing_provider_npi');
		$this->_provider->tax       = $this->administration->getValue('billing_federal_tax');
		$this->_provider->group_npi = $this->administration->getValue('billing_group_npi');
		$fontSize 					= $this->administration->getValue('billing_font_size');
		
		$intStr = filter_var($this->_facility->phone, FILTER_SANITIZE_NUMBER_INT);
		$this->_facility->phone_code   = substr( $intStr, 0 , 3);
		$this->_facility->phone_number = substr( $intStr, 3);

		$fontSize = ($fontSize) ? $fontSize : 8;		
		$this->SetFont('Arial','',$fontSize);
		
	}

	/**
	 * _pos => set position and aligment cell
	 *
	 * @param left float(3,2)
	 * @param top float(3,2)
	 * @param align char(L,C,R)
	 */
	private function _pos( $left = 0, $top = 0 , $align = 'L')
	{
		$left 	= ($left) ? $this->_MarginLeft + $left : $this->_MarginLeft;
		$top 	= ($top)  ? $this->_MarginTop + $top : $this->_MarginTop;  
		$this->SetXY( $left, $top );

		$this->_alignment = $align;

		return $this;
	}

	/**
	 * _cell => print comun cell
	 *
	 * @param $t string
	 * @param $w float(3,2)
	 * @param $h float(3,2) 
	 */
	private function _cell(  $t = '' , $w = 4 , $h = 4)
	{
		$this->Cell( $w, $h , $t , $border = 0 , 0 , $this->_alignment);
	}
    
    public function page( $bill = null)
    {	
    	$insured_full_name = $insured_other_names =  $patient_names = array();
    	if( $bill->insured_last_name )
    		$insured_full_name[] = $bill->insured_last_name;
    	if( $bill->insured_first_name )
    		$insured_full_name[] = $bill->insured_first_name;
    	if($bill->insured_middle_initial)
    		$insured_full_name[] = $bill->insured_middle_initial;

    	if( $bill->insured_other_last_name != '' )
    		$insured_other_names[] = $bill->insured_other_last_name; 
    	if( $bill->insured_other_first_name != '' )
    		$insured_other_names[] = $bill->insured_other_first_name; 
    	if( $bill->insured_other_middle_initial != '' )
    		$insured_other_names[] = $bill->insured_other_middle_initial; 
    	if( $bill->patient->last_name != '' )
    		$patient_names[] = $bill->patient->last_name; 
    	if( $bill->patient->name != '' )
    		$patient_names[] = $bill->patient->name; 
    	if( $bill->patient->middle_name != '' )
    		$patient_names[] = $bill->patient->middle_name; 
    	

		$this->AddPage('P', 'Letter');
		//$this->Image('logo.png', 10, 10, 40, 20, 0 ,"javascript:'window.print()'");
		//Line_1
		$this->_pos( 0, 0, 'C' )->_cell( $bill->PlanMedicare );
		$this->_pos( 17, 0, 'C' )->_cell( $bill->PlanMedicaid );
		$this->_pos( 35, 0, 'C' )->_cell( $bill->PlanChampus );
		$this->_pos( 58, 0, 'C' )->_cell( $bill->PlanChampVA );
		$this->_pos( 76, 0, 'C' )->_cell( $bill->PlanGroupHealthPlan );
		$this->_pos( 96, 0, 'C' )->_cell( $bill->PlanFECA );
		$this->_pos( 111.5, 0, 'C' )->_cell( $bill->PlanOther );
		$this->_pos( 124)->_cell($bill->insurance_number, 76.5);
		//Line_2
		$this->_pos( 0, 8.5 )->_cell( strtoupper( implode(', ', $patient_names)), 72.5);
		$this->_pos( 75, 8.5 , 'C')->_cell(date('m',strtotime($bill->patient->date_of_birth) ),8);
		$this->_pos( 83, 8.5 , 'C')->_cell(date('d',strtotime($bill->patient->date_of_birth) ),8);
		$this->_pos( 91, 8.5 , 'C')->_cell(date('Y',strtotime($bill->patient->date_of_birth) ), 10);
		$this->_pos( 103.5, 8.5 , 'C')->_cell($bill->patient->gender =='Male' ? 'X'  : '');
		$this->_pos( 116.5, 8.5 )->_cell($bill->patient->gender =='Female' ? 'X'  : '');
		$this->_pos( 123.5, 8.5 )->_cell( strtoupper( implode(',', $insured_full_name )), 76.5);
		//Line_3
		$this->_pos( 0, 17 )->_cell( strtoupper($bill->patient_address), 72.5);
		$this->_pos( 81, 17 )->_cell(  $bill->PatientRelationSELF );
		$this->_pos( 93.5, 17 )->_cell( $bill->PatientRelationSPOUSE );
		$this->_pos( 104, 17 )->_cell( $bill->PatientRelationCHILD );
		$this->_pos( 116.5, 17 )->_cell( $bill->PatientRelationOTHER );
		$this->_pos( 123.5, 17 )->_cell( strtoupper( $bill->insured_address) , 76.5);
		//line_4
		$this->_pos( 0, 25 )->_cell( strtoupper( $bill->patient_city) , 63.5);
		$this->_pos( 63.5, 25 )->_cell( strtoupper( $bill->patient_state) , 9);
		$this->_pos( 123.5, 25 )->_cell( strtoupper( $bill->insured_city) , 60);
		$this->_pos( 183.5, 25 )->_cell( strtoupper($bill->insured_state), 16);
		//line_5
		$this->_pos( 0, 34 )->_cell( strtoupper($bill->patient_zipcode) , 32.5);
		$this->_pos( 36, 34 )->_cell( $bill->patient_phone_code, 8 );
		$this->_pos( 46, 34 )->_cell( $bill->patient_phone_number, 25.5 );
		$this->_pos( 123.5, 34 )->_cell( strtoupper($bill->insured_zipcode), 32.5);
		$this->_pos( 163, 34 )->_cell( @$bill->insured_telephone_code, 9);
		$this->_pos( 174, 34 )->_cell( @$bill->insured_telephone_number, 25.5);
		//Line_6
		$this->_pos( 0, 42 )->_cell( strtoupper(implode( ', ', $insured_other_names )) , 72.5);
		//Line_7
		$this->_pos( 0, 50 )->_cell( strtoupper($bill->insured_other_policy) , 72.5);
		$this->_pos( 86, 51 )->_cell( ($bill->patient_condition_employment==='Yes') ? 'X' : '');
		$this->_pos( 101, 51 )->_cell( ($bill->patient_condition_employment==='No') ? 'X' : '');
		//Line_8
		$this->_pos( 86, 59.5 )->_cell( ($bill->patient_condition_autoaccident==='Yes') ? 'X' : '');
		$this->_pos( 101, 59.5 )->_cell( ($bill->patient_condition_autoaccident==='No') ? 'X' : '');
		$this->_pos( 112, 59.5 )->_cell($bill->patient_condition_autoaccident_place,7);
		//Line_9
		$this->_pos( 86, 67.5 )->_cell(  ($bill->patient_condition_otheraccident==='Yes') ? 'X' : '');
		$this->_pos( 101, 67.5 )->_cell(  ($bill->patient_condition_otheraccident==='No') ? 'X' : '');
		//Line_10
		$this->_pos( 0, 76.5 )->_cell( strtoupper($bill->insurance_title) , 72.5);
		$this->_pos( 72.5, 76.5 )->_cell( strtoupper( $bill->patient_condition_claimcodes), 51);
		$this->_pos( 129, 76.5 )->_cell( $bill->other_benefit_plan ==='Yes' ? 'X' : '');
		$this->_pos( 141.6, 76.5 )->_cell( $bill->other_benefit_plan ==='No' ? 'X' : '');
		//Line_11
		$this->_pos( 142, 92 )->_cell( "SIGNATURE ON FILE" , 80);//repeat signature on file
		//$this->_pos( 89 , 92 )->_cell( date('m/d/Y', strtotime($bill->encounter->create_at)), 35);
		$this->_pos( 17, 92 )->_cell( "SIGNATURE ON FILE" , 80);
		//Line_12
		$this->_pos( 2 , 102 , 'C')->_cell( date('m', strtotime($bill->encounter->create_at) ), 7);
		$this->_pos( 9 , 102 , 'C')->_cell( date('d', strtotime($bill->encounter->create_at) ), 8);
		$this->_pos( 17 , 102 ,'C')->_cell(date('Y', strtotime($bill->encounter->create_at) ), 10);
		$this->_pos( 134 , 102 ,'C')->_cell( $bill->date_patient_work_from->month , 7);
		$this->_pos( 141 , 102 ,'C')->_cell( $bill->date_patient_work_from->day , 8);
		$this->_pos( 149 , 102 ,'C')->_cell( $bill->date_patient_work_from->year , 10);
		$this->_pos( 170 , 102 ,'C')->_cell( $bill->date_patient_work_to->month, 7);
		$this->_pos( 177 , 102 ,'C')->_cell( $bill->date_patient_work_to->day, 7);
		$this->_pos( 184 , 102 ,'C')->_cell(  $bill->date_patient_work_to->year, 10);
		
		//Line_13
		if($bill->type_provider == 0 ) // manager
		{
			$this->_pos( 7 , 110 )->_cell( $bill->provider_name, 60);
			$this->_pos( 79 , 110 )->_cell( $bill->provider_npi, 44);
		} 	
		
		//Line_14
		//$not_owner = ($bill->doctor->medic_type != 'MD') ? $bill->doctor->names.' '.$bill->doctor->last_name.'. NPI: '.$bill->doctor->medic_npi : '';
		//$outside_lab_fee_separated = explode('.',$bill->outside_lab_fee );
		//$this->_pos( 0 , 118.5 )->_cell( strtoupper($not_owner) , 123);	
		if($bill->type_provider == 1 )
		{
			$this->_pos( 0 , 118.5 )->_cell(  $bill->provider_name.' NPI: '.$bill->provider_npi , 123);
		}
		
		$this->_pos( 65 , 118.5 )->_cell( $bill->aditional_claim , 123);
		
		if($bill->outside_lab === 'Yes')
		{
			$this->_pos( 129 , 118.5 )->_cell( ($bill->outside_lab === 'Yes') ? 'X' : '' );
			$this->_pos( 142 , 118.5 )->_cell( ($bill->outside_lab === 'No') ? 'X' : '' );
			$this->_pos( 153 , 118.5 )->_cell( strtok($bill->outside_lab_fee,'.') ,24);
			$this->_pos( 177.5 , 118.5 )->_cell( strtok('.') ,22);
		}
		
		//+Se agrega siempre?
		$this->_pos( 104 , 123 )->_cell( '0', 17);
		//
		$this->_pos( 5 , 127 )->_cell( $bill->diagnosis_illness_a, 17);
		$this->_pos( 37.5 , 127 )->_cell( $bill->diagnosis_illness_b, 17);
		$this->_pos( 71 , 127 )->_cell( $bill->diagnosis_illness_c, 17);
		$this->_pos( 104 , 127 )->_cell( $bill->diagnosis_illness_d, 17);
		$this->_pos( 124, 127 )->_cell( $bill->resubmission_code , 29);
		$this->_pos( 153 , 127 )->_cell( $bill->original_ref_no , 47);
		$this->_pos( 5 , 131 )->_cell( $bill->diagnosis_illness_e, 17);
		$this->_pos( 37.5 , 131 )->_cell( $bill->diagnosis_illness_f, 17);
		$this->_pos( 71 , 131 )->_cell( $bill->diagnosis_illness_g, 17);
		$this->_pos( 104 , 131 )->_cell( $bill->diagnosis_illness_h, 17);
		$this->_pos( 5 , 135 )->_cell( $bill->diagnosis_illness_i, 17);
		$this->_pos( 37.5 , 135 )->_cell( $bill->diagnosis_illness_j, 17);
		$this->_pos( 71 , 135 )->_cell( $bill->diagnosis_illness_k, 17);
		$this->_pos( 104 , 135 )->_cell( $bill->diagnosis_illness_l, 17);
		$this->_pos( 124, 135 )->_cell( $bill->authorization_number , 76);
		//SERVICES
		$m = date( 'm', strtotime($bill->encounter->signed_at) );
		$d = date( 'd', strtotime($bill->encounter->signed_at) );
		$y = date( 'y', strtotime($bill->encounter->signed_at) );
		
		$this->_services(152, $bill->service_1 , $m,$d,$y);
		$this->_services(160.5, $bill->service_2 , $m,$d,$y);
		$this->_services(169, $bill->service_3 , $m,$d,$y);
		$this->_services(177.5, $bill->service_4 , $m,$d,$y);
		$this->_services(186, $bill->service_5 , $m,$d,$y);
		$this->_services(194.5, $bill->service_6 , $m,$d,$y);
		//Footer_Line_1
		$this->_pos( 0 , 203 )->_cell( $this->_provider->tax, 40 );
		$this->_pos( 40 , 203 )->_cell();
		$this->_pos( 45 , 203 )->_cell('X');
		$this->_pos( 55 , 203 )->_cell( "$bill->encounter_id $bill->patient_id", 37 );
		$this->_pos( 94 , 203 )->_cell($bill->accept_assignment==='Yes'?'X':'');
		$this->_pos( 106 , 203 )->_cell($bill->accept_assignment==='No'?'X':'');
		$this->_pos( 128 , 203 )->_cell( strtok($bill->total_charge, '.'), 17 );
		$this->_pos( 145 , 203 )->_cell( strtok('.'), 6.5 );
		if($bill->amount_paid>0)
		{
			$this->_pos( 156 , 203 )->_cell( strtok($bill->amount_paid,'.'), 14);
			$this->_pos( 170 , 203 )->_cell( strtok('.'), 6);
		}
		
		//$this->_pos( 176 , 203 )->_cell( $bill->rsvd_for_nucc, 17);
		$this->_pos( 178 , 203 )->_cell( strtok($bill->total_charge, '.'), 17 );
		$this->_pos( 192 , 203 )->_cell( strtok('.'), 6.5 );
		//Fotter_Line_2
		$this->_pos( 165 , 208)->_cell( $this->_facility->phone_code , 7 );
		$this->_pos( 175 , 208)->_cell( $this->_facility->phone_number , 25 );
		$this->_pos( 124 , 212)->_cell( $this->_facility->name , 25 );
		//Fotter_Line_3
		$this->_pos( 124, 216)->_cell( strtoupper($this->_facility->address) , 76.5 );
		//Fotter_Line_4
		//$this->_pos( 32 , 220 )->_cell( date('m/d/Y', strtotime($bill->done_at)), 24 );
		$this->_pos( 32 , 220 )->_cell( date('m/d/Y'), 24 );
		$this->_pos( 124, 220)->_cell( strtoupper( $this->_facility->city.', '.$this->_facility->state.', '.$this->_facility->zip) , 76.5 );
		//Fotter_Line_5
		$this->_pos( 130, 224 )->_cell( $this->_facility->npi , 29 );
    }

	private function _services( $top, $service , $m , $d , $y)
	{

		if( $service->active == 0 || $service->procedure_cpt_hcpcs==='')
		{	
			return false;
		}

		if($service->date_of_service)
		{
			$m = date( 'm', strtotime($service->date_of_service));
			$d = date( 'd', strtotime($service->date_of_service));
			$y = date( 'y', strtotime($service->date_of_service));
		}
		//

		$this->_pos( 0 , $top, 'C' )->_cell( $m, 6.5);
		$this->_pos( 6.5 , $top, 'C' )->_cell( $d, 7.5);
		$this->_pos( 14 , $top, 'C' )->_cell( $y, 8);
		$this->_pos( 22 , $top, 'C' )->_cell( $m, 7);
		$this->_pos( 29 , $top, 'C' )->_cell( $d, 8);
		$this->_pos( 37 , $top, 'C' )->_cell( $y, 7.5);

		$this->_pos( 44.5 , $top )->_cell( $service->place_of_service, 8.5);
		$this->_pos( 53 , $top )->_cell( $service->emg , 7.5);
		$this->_pos( 61.5 , $top )->_cell( $service->procedure_cpt_hcpcs, 18.5);
		$this->_pos( 80 , $top )->_cell( $service->modifier_a, 8);
		$this->_pos( 88 , $top )->_cell( $service->modifier_b, 7.5);
		$this->_pos( 95.5 , $top )->_cell( $service->modifier_c, 7.5);
		$this->_pos( 103 , $top )->_cell( $service->modifier_d, 8);
		$this->_pos( 111 , $top )->_cell( $service->diagnosis_pointer, 13);

		$this->_pos( 124, $top )->_cell( strtok($service->charges,'.'), 16);
		$this->_pos( 140 , $top )->_cell( strtok('.'), 6.5);
		$this->_pos( 146.5 , $top )->_cell( $service->days_units, 10);
		$this->_pos( 156.5 , $top )->_cell( $service->family_plan , 5);
		$this->_pos( 161.5 , $top - 4 )->_cell( $service->id_qual, 7.5);
		$this->_pos( 169 , $top - 4 )->_cell( $service->rendering_provider_id , 31);
		$this->_pos( 169 , $top )->_cell( $this->_provider->npi , 31);

		$this->_pos( 0 , $top - 4 , 'L' )->_cell( $service->notes_unit, 6.5);

	}
}