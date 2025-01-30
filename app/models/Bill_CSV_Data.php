<?php
class Bill_CSV_Data extends CI_Model {
	
	private $_provider;

	function __construct()
	{
		parent::__construct();
		$this->_provider      = new StdClass;
		$this->administration = \libraries\Administration::init();
	}
	 
	function content( $bill )
	{
		$encounter = $this->get_encounter( $bill->encounter_id );
		$patient   = $this->get_patient( $bill->patient_id );
		$user      = $this->get_user( $bill->user_id );
		$services  = $this->get_services( $bill->id );
		$doctor    = $this->get_user( $encounter->user_id );
		
		$data_insurance = [
			//'ICD_IND' => '',
			'InsurancePlanName' => $bill->insurance_title,
			'InsurancePayerID' => $bill->insurance_number,
			'InsuranceStreetAddr' => $bill->insured_address,
			'InsuranceCity' => $bill->insured_city,
			'InsuranceState' => $bill->insured_state,
			'InsuranceZip' => $bill->insured_zipcode,
			'InsuranceCityStateZip' => $bill->insured_city . '' . $bill->insured_state . '' . $bill->insured_zipcode,
			'InsurancePhone' => $bill->insured_telephone
		];
		$data_plan = [
			'PlanMedicare' => active_plan( $bill->plan_type , 'PlanMedicare' ),
			'PlanMedicaid' => active_plan( $bill->plan_type , 'PlanMedicaid' ),
			'PlanChampus' => active_plan( $bill->plan_type , 'PlanChampus' ),
			'PlanChampVA' => active_plan( $bill->plan_type , 'PlanChampVA' ),
			'PlanGroupHealthPlan' => active_plan( $bill->plan_type , 'PlanGroupHealthPlan' ),
			'PlanFECA' => active_plan( $bill->plan_type , 'PlanFECA' ),
			'PlanOther' => active_plan( $bill->plan_type , 'PlanOther' ),
		];
		$data_patient = [
			'PatientID' => $patient->id,
			'PatientLast' => $patient->last_name,
			'PatientFirst' =>  $patient->name,
			'PatientMidInit' =>  $patient->middle_name,
			'PatientDOB' => $patient->date_of_birth,
			'PatientMale' => ($patient->gender == 'Male' ) ? 1 : '',
			'PatientFemale' => ($patient->gender == 'Female' ) ? 1 : '',
			'InsuredLast' => $bill->insured_last_name,
			'InsuredFirst' => $bill->insured_first_name,
			'InsuredMidInit' => $bill->insured_middle_initial,
			'PatientStreetAddress' => $bill->patient_address,
			'PatientCity' => $bill->patient_city,
			'PatientState' => $bill->patient_state,
			'PatientZip' => $bill->patient_zipcode,
			'PatientPhone' => str_replace(["(",")"," "],"",$patient->phone),
			'PatientRelationSELF' => active_patientrelation( $bill->patient_relationship, 'PatientRelationSELF' ),
			'PatientRelationSPOUSE' => active_patientrelation( $bill->patient_relationship, 'PatientRelationSPOUSE' ),
			'PatientRelationCHILD' => active_patientrelation( $bill->patient_relationship, 'PatientRelationCHILD' ),
			'PatientRelationOTHER' => active_patientrelation( $bill->patient_relationship, 'PatientRelationOTHER' ), 
			'InsuredStreetAddress' =>  $bill->insured_address,
			'InsuredCity' => $bill->patient_city,
			'InsuredState' => $bill->patient_state,
			'InsuredZip' => $bill->patient_zipcode,
			'InsuredPhone' => $bill->patient_telephone,
			'PatientMaritalSingle' => ($bill->patient_marital_status == 1) ? 'Yes' : 'No',
			'PatientMaritalMarried' => ($bill->patient_marital_status == 2) ? 'Yes' : 'No',
			'PatientMaritalOther' => ($bill->patient_marital_status == 3) ? 'Yes' : 'No',
			'PatientEmploymentEmployed' => $bill->patient_condition_employment,
			'PatientEmploymentFullTimeStudent' => '',
			'PatientEmploymentPartTimeStudent' => '',
		];
		$data_other_insured = [
			'OtherInsuredLast'                       => $bill->insured_other_last_name,
			'OtherInsuredFirst'                      => $bill->insured_other_first_name,
			'OtherInsuredMidInit'                    => $bill->insured_other_middle_initial,
			'OtherInsuredPolicyOrGroupNumber'        => $bill->insured_other_policy,
			'OtherInsuredDOB'                        => '', //_blank
			'OtherInsuredSexMale'                    => '', //_blank
			'OtherInsuredSexFemale'                  => '', //_blank
			'OtherInsuredEmlpoyerNameOrSchoolName'   => '', //_blank
			'OtherInsuredInsurancePlanorProgramName' => '', //_blank
		];
		$data_condition = [
			'CondtionRelatedToEmlpoymentYes' => ($bill->patient_condition_employment === 'Yes') ? 1 : '',
			'CondtionRelatedToEmlpoymentNo' => ($bill->patient_condition_employment === 'No') ? 1 : '',
			'CondtionRelatedToAutoAccidentYes' => ($bill->patient_condition_autoaccident === 'Yes' ) ? 1 : '',
			'CondtionRelatedToAutoAccidentNo' => ($bill->patient_condition_autoaccident === 'No' ) ? 1 : '',
			'AutoAccidentState' => $bill->patient_condition_autoaccident_place,
			'CondtionRelatedToOtherAccidentYes' => ($bill->patient_condition_otheraccident==='Yes') ? 1 : '',
			'CondtionRelatedToOtherAccidentNo' => ($bill->patient_condition_otheraccident==='No') ? 1 : '',
			'ReservedForLocalUse' => str_replace('"',"'",$this->administration->getValue('reserved_for_local_use')),
			'InsuredPolicyGroupOrFecaNumber' => $bill->insurance_title,//
			'InsuredDOB' => $patient->date_of_birth,
			'InsuredGenderMale' => ($patient->gender == 2 ) ? 1 : '',
			'InsuredGenderFemale' => ($patient->gender == 2 ) ? 1 : '',
			'InsuredEmployerNameOrSchoolName' => '', //_blank
			'InsuredInsurancePlanName' => $bill->insurance_title,
			'IsThereAnotherHealhPlanBenefitYes' => ($bill->other_benefit_plan==='Yes') ? 1 : 0,
			'IsThereAnotherHealhPlanBenefitNo' => ($bill->other_benefit_plan==='No') ? 1 : 0,
		];
		$data_signature = [
			'PatientSignature' => $patient->name,
			'PatientSignatureDate' => date( 'm/d/Y' ,strtotime($encounter->signed_at) ),
			'InsuredSignature' => date( 'm/d/Y' ,strtotime($encounter->signed_at) ),
			'DateOfCurrent' => date('m/d/Y'),
		];
		$data_extra = [
			'DateOfSimilarIllness' => '', //_blank
			'UnableToWorkFromDate' => $bill->date_patient_work_from,
			'UnableToWorkToDate' => $bill->date_patient_work_to,
			'ReferringPhysician' => $bill->name_referring,
			//'ReferPhysQualifier' => '', //_blank
			'ReferringPhysicianID' => '', //_blank
			//'Refer_Phys_NPI' => $this->administration->getValue('phys_npi'),
			//'Super_Phys_NPI' => $this->administration->getValue('phys_npi'),
			'HospitalizationFromDate' => $bill->date_hospital_from,
			'HospitalizationToDate' => $bill->date_hospital_to,
			'Box19Notes' => $bill->aditional_claim,
			'OutsideLabChargesYes' => ($bill->outside_lab==='Yes') ? 1 : '',
			'OutsideLabChargesNo' => ($bill->outside_lab==='No') ? 1 : '',
			'OutsideLabFees' => $bill->outside_lab_fee,
		];
		$data_diagnosis = [
			'DiagCode1' => $bill->diagnosis_illness_a,
			'DiagCode2' => $bill->diagnosis_illness_b,
			'DiagCode3' => $bill->diagnosis_illness_c,
			'DiagCode4' => $bill->diagnosis_illness_d,
			//'DiagCode5' => $bill->diagnosis_illness_e,
			//'DiagCode6' => $bill->diagnosis_illness_f,
			//'DiagCode7' => $bill->diagnosis_illness_g,
			//'DiagCode8' => $bill->diagnosis_illness_h,
			//'DiagCode9' => $bill->diagnosis_illness_i,
			//'DiagCode10' => $bill->diagnosis_illness_j,
			//'DiagCode11' => $bill->diagnosis_illness_k,
			//'DiagCode12' => $bill->diagnosis_illness_l,
			'MedicaidResubCode' => $bill->resubmission_code,
			'MedicaidRefNumber' => $bill->original_ref_no,
			'PriorAuthNo' => $bill->authorization_number,
			'HCFACLIANumber' => $bill->authorization_number ,
		];

		$data_services = [];

		foreach ($services as $s ) {
			
			$n = $s->number;
			 
			$exist_service = ($s->place_of_service!='') ? true : false; 
			//print_r($s); 
			$data_services += [
				"FromDateOfService{$n}" => ($exist_service) ? $encounter->signed_at : '',
				"ToDateOfService{$n}" => ($exist_service) ? $encounter->signed_at : '',
				"PlaceOfService{$n}" => $s->place_of_service,
				"TypeOfService{$n}" => "",
				"CPT{$n}" => $s->procedure_cpt_hcpcs,
				"ModifierA{$n}" => $s->modifier_a,
				"ModifierB{$n}" => $s->modifier_b,
				"ModifierC{$n}" => $s->modifier_c,
				"ModifierD{$n}" => $s->modifier_d,
				"DiagCodePointer{$n}" => $s->diagnosis_pointer,
				"Charges{$n}" => ($s->charges > 0) ? $s->charges : '',
				"Units{$n}" => $s->days_units,
				"EPSDT{$n}" => $s->family_plan,
				"EMG{$n}" => $s->emg,
				"COB{$n}" => "",
				"Local{$n}" => "",
				//"RenderingPhysQualifier{$n}" => $s->id_qual,
				//"RenderingPhysID{$n}" => $s->rendering_provider_id,
				//"RenderingPhysNPI{$n}" => $s->rendering_provider_npi,
			];
			//echo "<br>";
			
		}
		//exit;
		$data_payment = [
			'TaxID' => str_replace("-","", $this->administration->getValue('billing_federal_tax')),
			'SSN' => '',
			'EIN' => 1,
			'PatientAcctNumber' => $bill->patients_account,
			'AcceptAssignYes' => ($bill->accept_assignment === 'Yes' ) ? 1 : 0,
			'AcceptAssignNo' => ($bill->accept_assignment === 'No' ) ? 1 : 0,
			'TotalCharges' => $bill->total_charge,
			'AmountPaid' => $bill->amount_paid,
			'BalanceDue' => $bill->total_due,
		];

		$doctorNames = explode(" ", $doctor->names);
		
		$data_physician = [
			'PhysicianSignature' => $doctor->digital_signature,
			'PhysicianSignatureDate' => $encounter->signed_at,
			'PhysicianLast' => $doctor->last_name,
			'PhysicianFirst' => isset($doctorNames[0]) ? $doctorNames[0] : '',
			'PhysicianMidInit' => isset($doctorNames[1]) ? $doctorNames[1] : '',
		];
		
		$data_facility = [
			'FacilityName' => $this->administration->getValue('billing_facility_name'),
			'FacilityStreetAddr' => $this->administration->getValue('billing_facility_streetAddr'),
			'FacilityCity' => $this->administration->getValue('billing_facility_city'),
			'FacilityState' => $this->administration->getValue('billing_facility_state'),
			'FacilityZip' => $this->administration->getValue('billing_facility_zip'),
			'FacilityCityStateZip' => $this->administration->getValue('billing_facility_citystatezip'),
			//'FacilityNPI' => $this->administration->getValue('billing_facility_npi'),
			'FacilityID' => $this->administration->getValue('billing_facility_id'),
		];
		
		$data_supplier = [
			'MammographyCertification' => '', //_blank
			'SupplierName' => $this->administration->getValue('billing_supplier_name'),
			'SupplierStreetAddr' => $this->administration->getValue('billing_supplier_streetAddr'),
			'SupplierCity' => $this->administration->getValue('billing_supplier_city'),
			'SupplierState' => $this->administration->getValue('billing_supplier_state'),
			'SupplierZip' => $this->administration->getValue('billing_supplier_zip'),
			'SupplierCityStateZip' => $this->administration->getValue('billing_supplier_citystatezip'),
			'SupplierPhone' => $this->administration->getValue('billing_supplier_phone'),
			//'SupplierNPI' => $this->administration->getValue('billing_supplier_npi'),
			'PracticeID' => "",
			'GroupID' => $this->administration->getValue('billing_group_npi')
		]; 
		
		$concat = $data_insurance
			+ $data_plan
			+ $data_patient
			+ $data_other_insured
			+ $data_condition
			+ $data_signature
			+ $data_extra
			+ $data_diagnosis
			+ $data_services
			+ $data_payment
			+ $data_physician
			+ $data_facility
			+ $data_supplier
		;
		 
		return $concat;
	}

	private function get_encounter( $encounter_id  )
	{
		$this->db->select([
				'encounter.insurance_number',
				'encounter.user_id',
				"DATE_FORMAT(encounter.signed_at, '%m/%d/%Y') as signed_at",
			])
			->from('encounter')
			->where( [ 'encounter.id' => $encounter_id ] )
		;

		return $this->db->get()->row();
	}

	private function get_user( $user_id )
	{
		$this->db->select([
				'user.names',
				'user.last_name',
				'user.digital_signature'
			])
			->from('user')
			->where( [ 'user.id' => $user_id ] )
		;

		return $this->db->get()->row();
	}

	private function get_patient( $patient_id  )
	{
		$this->db->select([
				'patient.id',
				'patient.name',
				'patient.middle_name',
				'patient.last_name',
				"patient.date_of_birth",
				'patient.gender',
				'patient.address',
				'patient.phone'
			])
			->from('patient')
			->where( [ 'patient.id' => $patient_id ] )
		;

		return $this->db->get()->row();
	}

	private function get_services( $billing_id )
	{
		$this->db
			->select('*')
			->from('billing_detail')
			->where(['billing_id' => $billing_id ])
		;

		return $this->db->get()->result();
	}

}

