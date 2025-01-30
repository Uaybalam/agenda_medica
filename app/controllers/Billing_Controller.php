<?php
/**
* @route:billing
*/
class Billing_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Billing_Model' => 'Billing_DB',
			'Billing_Charges_Model' => 'Billing_Charges_DB',
			'Patient_Model' => 'Patient_DB',
			'Encounter_Model' => 'Encounter_DB',
			'Custom_Setting_Model' => 'Custom_Setting'
		]);
		$this->validate_access(['manager','billing','admin','root'],'/');
			
	}

	/**
	 * @route:{get}notFound/
	 */
	function notFound()
	{
		echo "Not FOund";
	}

	/**
	 * @route:{post}updateComments/(:num)
	 */
	function updateComments( $billId = 0 )
	{
		$billing = $this->Billing_DB->get_info( $billId );
		if(!$billing)
		{
			return $this->template->json([
				'message' => 'Facturación no encontrada'
			]); 
		}

		$comments = trim($this->input->post('comments'));

		$this->db->where(['id' => $billId])->update('billing', Array(
			'comments' => $comments
		));

		return $this->template->json([
			'status' => 1,
			'message' => 'Comentarios actualizados'
		]);

	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{

		$status    = $this->Billing_DB->get_status();
		
		$this->template->css('daterangepicker','/assets/vendor/datepickerrange/');	
		$this->template->js('daterangepicker.min','/assets/vendor/datepickerrange/');
		
		$this->template
			->set_title('Billings')
			->body([
				'ng-app' => 'app_billing',
				'ng-controller' => 'ctrl_billing',
				'ng-init' => 'initialize('.$this->template->json_entities( $status ) .')'
			])
			->js('billing/billing.list')
			->modal('billing/modal.edit.detail', [
				'title' => 'Factura {{ response.billing.encounter_id }}',
				'size' => 'modal-md'
			])
			->render('billing/view.panel.billing.list', [
				'status' => $status,
				'options_insurances' => $this->Custom_Setting_DB->getElements('setting_insurance')
			]);
	}

	/**
	 * @route:{get}list/(:num)/(:num)
	 */
	function records_list( $maxRecords = 0, $page = 0)
	{
		$bills = $this->Billing_DB->getPagination( $maxRecords, $page );
		
		$pendingPrint = $this->db->from('billing')->where(['print' => 1 ])->count_all_results();

		$this->template->json( [
			'total_count' => $bills['total_count'],
			'billings' => $bills['result_data'],
			'pendingPrint' => $pendingPrint
		] , 'JSON_PRESERVE_ZERO_FRACTION' );
	}

	/**
	 * @route:pdf/(:num)
	 * @route:pdf/(all)
	 * @route:pdf/(specialFilter)
	 */
	function pdf( $id )
	{
		$bills = Array();
		
		if($id === 'all')
		{	
			$bills = $this->Billing_DB->getResultsBy([ 'print' => 1 ]);
		}
		elseif($id==='specialFilter')
		{
			$specialFilter = Array(
				'encounter_id' => $this->input->get('encounter_id'),
				'start_date'   => $this->input->get('start_date'),
				'end_date'     => $this->input->get('end_date'),
				'insurance'    => $this->input->get('insurance'),
				'biller'       => $this->input->get('biller'),
				'status'       => $this->input->get('status')
			);
			$qb = $this->Billing_DB->db;
			
			$qb->select('billing.*')->from('billing');

			$qb->join('user', 'user.id=billing.user_id' , 'left');

			if( $encounterID = $specialFilter['encounter_id'] )
       	 		$qb->where( [ 'billing.encounter_id' => $encounterID ] );

	        if( $insurance = $specialFilter['insurance'] )
	        	$qb->where(['billing.insurance_title'  => $insurance ] );
	        
	        if( $start = $specialFilter['start_date'] )
	        {
	        	$qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') >=" => $start ] );
	        }
	       	if( $end = $specialFilter['end_date'] )
	        {
	        	$qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') <= " => $end ] );
	        }
	        
	        $status = $specialFilter['status'];
	         
			if( $status >= 1 || $status != "" )
			{  
	        	$qb->where( [ 'billing.status' => $status  ] );
			}
			else
			{
				$qb->where( [ 'billing.status !=' =>  0 ] );
			}
	        
	        if( $biller = $specialFilter['biller'])
	        	$qb->like( [ 'IFNULL(user.names,"")' => $biller ] );

			$bills 	= $qb->get()->result_object(); 

		}
		else
		{
			$bills[] = $this->Billing_DB->getRowBy([ 'id' => $id ]);
		}

		if(!count($bills))
		{
			show_error("No hay nada pendiente para imprimir");
		}

		if($this->input->get("printLines"))
		{
			define("PRINT_MARGIN_LEFT",  9.8 );
			define("PRINT_MARGIN_TOP", 38.5 );
			
			$this->load->library('print/PDF_Billing_Lines');
			$pdfBill = $this->pdf_billing_lines;
		}
		else
		{
			$this->load->library('print/PDF_Billing');
			$pdfBill = $this->pdf_billing;

		}

		$pdfBill->SetTitle('PRINT BILL');

		$this->load->model( [ 'Bill_CSV_Data' => 'Bill_CSV_Data'] );
		$data = $this->Bill_CSV_Data->content( $bills[0] );
		$content = "";
		$file = 'billings'.date("Y-m-d").'.txt';
		$txt  = fopen($file, "w") or die("Unable to open file!");

		/*
		$f = fopen('php://memory', 'w'); 

		fputcsv($f, array_keys($data),"\t");*/

		foreach ($bills as $bill ) {

			if(!is_object($bill))
			{
				continue;
			}
			
		 
			/*
 			$data = $this->Bill_CSV_Data->content( $bill );
			
			fputcsv($f, $data,"\t"," ");
			*/
			$bill->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $bill->id ]); 
			
			$bill->PlanMedicare          =  active_plan( $bill->plan_type , 'PlanMedicare' , 'X');
			$bill->PlanMedicaid          =  active_plan( $bill->plan_type , 'PlanMedicaid' , 'X');
			$bill->PlanChampus           =  active_plan( $bill->plan_type , 'PlanChampus' , 'X');
			$bill->PlanChampVA           =  active_plan( $bill->plan_type , 'PlanChampVA' , 'X');
			$bill->PlanGroupHealthPlan   =  active_plan( $bill->plan_type , 'PlanGroupHealthPlan' , 'X');
			$bill->PlanFECA              =  active_plan( $bill->plan_type , 'PlanFECA' , 'X');
			$bill->PlanOther             =  active_plan( $bill->plan_type , 'PlanOther' , 'X');
			
			$bill->PatientRelationSELF   =  active_patientrelation( $bill->patient_relationship, 'PatientRelationSELF', 'X' );
			$bill->PatientRelationSPOUSE =  active_patientrelation( $bill->patient_relationship, 'PatientRelationSPOUSE', 'X' );
			$bill->PatientRelationCHILD  =  active_patientrelation( $bill->patient_relationship, 'PatientRelationCHILD', 'X' );
			$bill->PatientRelationOTHER  =  active_patientrelation( $bill->patient_relationship, 'PatientRelationOTHER', 'X' );

			$bill->patient 			   	= $this->Patient_DB->get( $bill->patient_id );
			if( $bill->patient_telephone  )
			{
				$telephone = str_replace(["(",")"," "],"", $bill->patient_telephone );
				$bill->patient_phone_code 	= substr($telephone, 0 , 3 ); 
				$bill->patient_phone_number = substr($telephone, 3 , strlen( $telephone) ); 
			}
			else
			{
				$bill->patient_phone_code = $bill->patient_phone_number = '';
			}

			if($bill->insured_telephone)
			{
				$bill->insured_telephone_code 	= substr($bill->insured_telephone, 0, 3);
				$bill->insured_telephone_number = substr($bill->insured_telephone, 3);
			}

			
			$bill->encounter 			= $this->Encounter_DB->get($bill->encounter_id);
			$bill->doctor 				= $this->User_DB->get( $bill->encounter->user_id ); 
			$bill->biller 				= $this->User_DB->get( $bill->user_id ); 
			
			$bill->date_patient_work_from = $this->_get_format_date($bill->date_patient_work_from);
			$bill->date_patient_work_to   = $this->_get_format_date($bill->date_patient_work_to);
			$bill->date_hospital_from     = $this->_get_format_date($bill->date_hospital_from);
			$bill->date_hospital_to       = $this->_get_format_date($bill->date_hospital_to);
			
			$bill->service_1 = $this->Billing_DB->getService( $bill->id , 1);
			$bill->service_2 = $this->Billing_DB->getService( $bill->id , 2);
			$bill->service_3 = $this->Billing_DB->getService( $bill->id , 3);
			$bill->service_4 = $this->Billing_DB->getService( $bill->id , 4);
			$bill->service_5 = $this->Billing_DB->getService( $bill->id , 5);
			$bill->service_6 = $this->Billing_DB->getService( $bill->id , 6);
			
			//echo "<pre>".print_r($bill,1)."<pre>"; exit;
			//$pdfBill->page( $bill );
			$this->administration = \libraries\Administration::init();
			$this->_provider            = new StdClass;
			//$this->_provider->name      = $this->administration->getValue('billing_provider_name');
			//$this->_provider->npi       = $this->administration->getValue('billing_provider_npi');
			$this->_provider->tax       = $this->administration->getValue('billing_federal_tax');
			//$this->_provider->group_npi = $this->administration->getValue('billing_group_npi');

			$this->_facility          = new StdClass;
			$this->_facility->name    = $this->administration->getValue('billing_facility_name');
			$this->_facility->phone   = $this->administration->getValue('billing_facility_telephone');
			$this->_facility->zip     = $this->administration->getValue('billing_facility_zip');
			$this->_facility->address = $this->administration->getValue('billing_facility_streetAddr');
			$this->_facility->city    = $this->administration->getValue('billing_facility_city');
			$this->_facility->state   = $this->administration->getValue('billing_facility_state');
			$this->_facility->npi 	  = $this->administration->getValue('billing_facility_npi'); 

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
	    	
	    	$insuranceFullName = $this->Custom_Setting->getFullname($bill->insurance_title);
	    	 
			$content.= "Plan Medicare: ".active_plan( $bill->plan_type , 'PlanMedicare' , 'YES')."\n";
			$content.= "Plan Medicaid: ".active_plan( $bill->plan_type , 'PlanMedicaid' , 'YES')."\n";
			$content.= "Plan Tricare: ".active_plan( $bill->plan_type , 'PlanChampus' , 'YES')."\n";
			$content.= "Plan ChampVA: ".active_plan( $bill->plan_type , 'PlanChampVA' , 'YES')."\n";
			$content.= "Plan Group Health Plan: ".active_plan( $bill->plan_type , 'PlanGroupHealthPlan' , 'YES')."\n";
			$content.= "Plan FECA: ".active_plan( $bill->plan_type , 'PlanFECA' , 'YES')."\n";
			$content.= "Plan Other: ".active_plan( $bill->plan_type , 'PlanOther' , 'YES')."\n";
			$content.= "Insurance ID number: ".$bill->insurance_number."\n";
		    $content.= "Patiente Name: ".implode(", ",$patient_names)."\n"; 
		    $content.= "Patiente birth date: ".$bill->patient->date_of_birth."\n";
		    $content.= "Patiente Sex: ".$bill->patient->gender."\n";
		    $content.= "Insured name: ".implode(", ",$insured_full_name)."\n";
		    $content.= "Patient address: ".$bill->patient_address."\n";
			$content.= "Patient Relation SELF: ".active_patientrelation( $bill->patient_relationship, 'PatientRelationSELF', 'YES' )."\n";
			$content.= "Patient Relation SPOUSE: ".active_patientrelation( $bill->patient_relationship, 'PatientRelationSPOUSE', 'YES' )."\n";
			$content.= "Patient Relation CHILD: ".active_patientrelation( $bill->patient_relationship, 'PatientRelationCHILD', 'YES' )."\n";
			$content.= "Patient Relation OTHER: ".active_patientrelation( $bill->patient_relationship, 'PatientRelationOTHER', 'YES' )."\n";
			$content.= "Insured address: ".$bill->insured_address."\n";
			$content.= "Patiente city: ".$bill->patient_city."\n";
			$content.= "Patiente state: ".$bill->patient_state."\n";
			$content.= "Insured city: ".$bill->insured_city."\n";
			$content.= "Insured state: ".$bill->insured_state."\n";
		    $content.= "Patient zipcode: ".$bill->patient_zipcode."\n";  
			$content.= "Patient Telephone: ".$bill->patient_phone_code." ".$bill->patient_phone_number."\n";
			$content.= "Insurence zipcode: ".$bill->insured_zipcode."\n"; 
			$content.= "Insurence Telephone: ".$bill->insured_telephone."\n";
			$content.= "Other insured's name: ".implode(", ",$insured_other_names)."\n";
			$content.= "Is patiente's condition related to employment : ".$bill->patient_condition_employment."\n";
			$content.= "Is patiente's condition related to auto accident: ".$bill->patient_condition_autoaccident."\n";
			$content.= "Auto accident place: ".$bill->patient_condition_autoaccident_place."\n";
			$content.= "Is patiente's condition related to other accident: ".$bill->patient_condition_otheraccident."\n";
			$content.= "Insured's policy group: ".$bill->insured_policy."\n";
			$content.= "Other insured's policy: ".$bill->insured_other_policy."\n";
			$content.= "Insured's date of birth: ".$bill->insured_of_birth."\n";
			$content.= "Insured's sex: ".$bill->insured_gender."\n";
			$content.= "Other Claim ID: ".$bill->claim_id."\n";
			$content.= "Insurance plan name: ".($insuranceFullName ? $insuranceFullName : $bill->insurance_title)."\n";
			$content.= "Claim Codes: ".$bill->patient_condition_claimcodes."\n";
			$content.= "Is there another Health benefit plan: ".$bill->other_benefit_plan."\n";
			$content.= "Date of current illness, injury or pregnancy: ".$bill->encounter->create_at."\n"; 
			$content.= "Other Date: ".$bill->other_date."\n"; 
			$content.= "Dates Patiente unable to work From: ".implode("/",array_filter((array)$bill->date_patient_work_from))."\n"; 
			$content.= "Dates Patiente unable to work To: ".implode("/",array_filter((array)$bill->date_patient_work_to))."\n"; 
			$content.= "Name of reffering provider or other source: ".$bill->provider_name."\n"; 
			$content.= "Name of reffering provider or other NIP: ".$bill->provider_npi."\n"; 
			$content.= "Hospitalization dates related to current services from: ".implode("/",array_filter((array)$bill->date_hospital_from))."\n"; 
			$content.= "Hospitalization dates related to current services to: ".implode("/",array_filter((array)$bill->date_hospital_to))."\n"; 
			$content.= "Aditional Claim information: ".$bill->aditional_claim."\n"; 
			$content.= "Outside Lab: ".$bill->outside_lab."\n"; 
			$content.= "Outside Lab charges: ".strtok($bill->outside_lab_fee,'.')."\n";
			$content.= "Diagnosis Illness a: ".$bill->diagnosis_illness_a."\n"; 
		    $content.= "Diagnosis Illness b: ".$bill->diagnosis_illness_b."\n";
		    $content.= "Diagnosis Illness c: ".$bill->diagnosis_illness_c."\n";
		    $content.= "Diagnosis Illness d: ".$bill->diagnosis_illness_d."\n";  
		    $content.= "Diagnosis Illness e: ".$bill->diagnosis_illness_e."\n";  
		    $content.= "Diagnosis Illness f: ".$bill->diagnosis_illness_f."\n";  
		    $content.= "Diagnosis Illness g: ".$bill->diagnosis_illness_g."\n"; 
		    $content.= "Diagnosis Illness h: ".$bill->diagnosis_illness_h."\n"; 
		    $content.= "Diagnosis Illness i: ".$bill->diagnosis_illness_i."\n"; 
		    $content.= "Diagnosis Illness j: ".$bill->diagnosis_illness_j."\n"; 
		    $content.= "Diagnosis Illness k: ".$bill->diagnosis_illness_k."\n"; 
		    $content.= "Diagnosis Illness l: ".$bill->diagnosis_illness_l."\n";
		    $content.= "Resubmission code: ".$bill->resubmission_code."\n";
		    $content.= "Original ref no: ".$bill->original_ref_no."\n";
		    $content.= "Prica Authorization Number: ".$bill->authorization_number."\n";
		    
			if(count($bill->extraCharges))
			{
				
				foreach ($bill->extraCharges as $pos => $charges) {

					for($i=1; $i<7; $i++ )
					{
						//$bill->{"service_".$i}->active                = $charges->{"active_$i"};
						$bill->{"service_".$i}->procedure_cpt_hcpcs   = $charges->{"procedure_cpt_hcpcs_$i"};
						$bill->{"service_".$i}->place_of_service      = $charges->{"place_of_service_$i"};
						$bill->{"service_".$i}->emg                   = $charges->{"emg_$i"};
						$bill->{"service_".$i}->modifier_a            = $charges->{"modifier_a_$i"};
						$bill->{"service_".$i}->modifier_b            = $charges->{"modifier_b_$i"};
						$bill->{"service_".$i}->modifier_c            = $charges->{"modifier_c_$i"};
						$bill->{"service_".$i}->modifier_d            = $charges->{"modifier_d_$i"};
						$bill->{"service_".$i}->diagnosis_pointer     = $charges->{"diagnosis_pointer_$i"};
						$bill->{"service_".$i}->charges               = $charges->{"charges_$i"};
						$bill->{"service_".$i}->days_units            = $charges->{"days_units_$i"};
						$bill->{"service_".$i}->family_plan           = $charges->{"family_plan_$i"};
						$bill->{"service_".$i}->id_qual               = $charges->{"id_qual_$i"};
						$bill->{"service_".$i}->rendering_provider_id = $charges->{"rendering_provider_id_$i"};
						$bill->{"service_".$i}->date_of_service       = $charges->{"date_of_service_$i"};

						//$content.= "Service ".$i." active: ".$bill->{"service_".$i}->active."\n";
						$content.= "Service ".$i." procedure cpt hcpcs: ".$bill->{"service_".$i}->procedure_cpt_hcpcs."\n";
						$content.= "Service ".$i." place of service: ".$bill->{"service_".$i}->place_of_service."\n";
						$content.= "Service ".$i." emg: ".$bill->{"service_".$i}->emg."\n";
						$content.= "Service ".$i." modifier a: ".$bill->{"service_".$i}->modifier_a."\n";
						$content.= "Service ".$i." modifier b: ".$bill->{"service_".$i}->modifier_b."\n";
						$content.= "Service ".$i." modifier c: ".$bill->{"service_".$i}->modifier_c."\n";
						$content.= "Service ".$i." modifier d: ".$bill->{"service_".$i}->modifier_d."\n";
						$content.= "Service ".$i." diagnosis pointer: ".$bill->{"service_".$i}->diagnosis_pointer."\n";
						$content.= "Service ".$i." charges: ".$bill->{"service_".$i}->charges."\n";
						$content.= "Service ".$i." days units: ".$bill->{"service_".$i}->days_units."\n";
						$content.= "Service ".$i." family plan: ".$bill->{"service_".$i}->family_plan."\n";
						$content.= "Service ".$i." id qual: ".$bill->{"service_".$i}->id_qual."\n";
						$content.= "Service ".$i." rendering provider id: ".$bill->{"service_".$i}->rendering_provider_id."\n";
						$content.= "Service ".$i." date of service: ".$bill->{"service_".$i}->date_of_service."\n";
						
					}
				}

				$bill->total_charge = $charges->total_charge; 
				//$pdfBill->page( $bill );
			}
			else
			{
				$total_charge = 0;
				
				for($i=1; $i<7; $i++ )
				{
					//$content.= "Service ".$i." active: ".$bill->{"service_".$i}->active."\n";
					$content.= "Service ".$i." procedure cpt hcpcs: ".$bill->{"service_".$i}->procedure_cpt_hcpcs."\n";
					$content.= "Service ".$i." place of service: ".$bill->{"service_".$i}->place_of_service."\n";
					$content.= "Service ".$i." emg: ".$bill->{"service_".$i}->emg."\n";
					$content.= "Service ".$i." modifier a: ".$bill->{"service_".$i}->modifier_a."\n";
					$content.= "Service ".$i." modifier b: ".$bill->{"service_".$i}->modifier_b."\n";
					$content.= "Service ".$i." modifier c: ".$bill->{"service_".$i}->modifier_c."\n";
					$content.= "Service ".$i." modifier d: ".$bill->{"service_".$i}->modifier_d."\n";
					$content.= "Service ".$i." diagnosis pointer: ".$bill->{"service_".$i}->diagnosis_pointer."\n";
					$content.= "Service ".$i." charges: ".$bill->{"service_".$i}->charges."\n";
					$content.= "Service ".$i." days units: ".$bill->{"service_".$i}->days_units."\n";
					$content.= "Service ".$i." family plan: ".$bill->{"service_".$i}->family_plan."\n";
					$content.= "Service ".$i." id qual: ".$bill->{"service_".$i}->id_qual."\n";
					$content.= "Service ".$i." rendering provider id: ".$bill->{"service_".$i}->rendering_provider_id."\n";
					$content.= "Service ".$i." date of service: ".$bill->{"service_".$i}->date_of_service."\n";

					$total_charge = $total_charge+$bill->{"service_".$i}->charges;
					
				}
				
				$bill->total_charge = $total_charge;

			}

			$content.= "Federal Tax ID Number: ".$this->_provider->tax." EIN\n";
		    $content.= "Patiente Account No: ".$bill->encounter_id." ".$bill->patient_id."\n";
		    $content.= "Accept Assigned?: ".$bill->accept_assignment."\n"; 
			$content.= "Total charge ".$bill->total_charge ."\n";
		    $content.= "Amount paid: ".strtok($bill->amount_paid,'.')."\n";
		    $content.= "Signature of Physician:\n"; 
		    $content.= "Signature on file: ".date('m/d/Y')."\n";
		    $content.= "Service facility Location information: ".$this->_facility->address." ".strtoupper( $this->_facility->city.', '.$this->_facility->state.', '.$this->_facility->zip)."\n";
		    $content.=  "Billing provider info & ph: ".$this->_facility->name." ".$this->_facility->phone."\n";
			

			$content.= "\n\n";
			//
			//$pdfBill->page( $bill );
		}

		fwrite($txt, $content);
		fclose($txt);

		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		header("Content-Type: text/plain");
		readfile($file);

		/*
		if($this->input->get("printLines"))
		{
			$pdfBill->setLines();
		}

		$pdfBill->IncludeJS("print('true');");*/
		//$pdfBill->IncludeJS("print('true');");
		//$pdfBill->output();
		
		/*fseek($f, 0); 
	    header('Content-Type: text/csv'); 
	    header('Content-Disposition: attachment; filename="billings'.date("Y-m-d").'.csv";'); 
	    fpassthru($f);*/
	}

	/**
	 * @route:pdfc/(:num)
	 * @route:pdfc/(all)
	 * @route:pdfc/(specialFilter)
	 */
	function pdfc( $id )
	{
		$bills = Array();
		
		if($id === 'all')
		{	
			$bills = $this->Billing_DB->getResultsBy([ 'print' => 1 ]);
		}
		elseif($id==='specialFilter')
		{
			$specialFilter = Array(
				'encounter_id' => $this->input->get('encounter_id'),
				'start_date'   => $this->input->get('start_date'),
				'end_date'     => $this->input->get('end_date'),
				'insurance'    => $this->input->get('insurance'),
				'biller'       => $this->input->get('biller'),
				'status'       => $this->input->get('status')
			);
			$qb = $this->Billing_DB->db;
			
			$qb->select('billing.*')->from('billing');

			$qb->join('user', 'user.id=billing.user_id' , 'left');

			if( $encounterID = $specialFilter['encounter_id'] )
       	 		$qb->where( [ 'billing.encounter_id' => $encounterID ] );

	        if( $insurance = $specialFilter['insurance'] )
	        	$qb->like('billing.insurance_title', $insurance  );
	        
	        if( $start = $specialFilter['start_date'] )
	        {
	        	$qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') >=" => $start ] );
	        }
	       	if( $end = $specialFilter['end_date'] )
	        {
	        	$qb->where( [ "DATE_FORMAT(billing.create_at, '%Y%m%d') <= " => $end ] );
	        }
	        
	        $status = $specialFilter['status'];
			if( $status >= 0 )
			{
	        	$qb->where( [ 'billing.status' => $status  ] );
			}
	        
	        if( $biller = $specialFilter['biller'])
	        	$qb->like( [ 'IFNULL(user.names,"")' => $biller ] );

	        $qb->where( [ 'billing.status !=' =>  0 ] );

			$bills 	= $qb->get()->result_object();

		}
		else
		{
			$bills[] = $this->Billing_DB->getRowBy([ 'id' => $id ]);
		}

		if(!count($bills))
		{
			show_error("No hay nada pendiente para imprimir");
		}

		if($this->input->get("printLines"))
		{
			define("PRINT_MARGIN_LEFT",  9.8 );
			define("PRINT_MARGIN_TOP", 38.5 );
			
			$this->load->library('print/PDF_Billing_Lines');
			$pdfBill = $this->pdf_billing_lines;
		}
		else
		{
			$this->load->library('print/PDF_Billing');
			$pdfBill = $this->pdf_billing;

		}

		$pdfBill->SetTitle('PRINT BILL');
		
		foreach ($bills as $bill ) {

			if(!is_object($bill))
			{
				continue;
			}
			
			$bill->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $bill->id ]); 
			
			$bill->PlanMedicare          =  active_plan( $bill->plan_type , 'PlanMedicare' , 'X');
			$bill->PlanMedicaid          =  active_plan( $bill->plan_type , 'PlanMedicaid' , 'X');
			$bill->PlanChampus           =  active_plan( $bill->plan_type , 'PlanChampus' , 'X');
			$bill->PlanChampVA           =  active_plan( $bill->plan_type , 'PlanChampVA' , 'X');
			$bill->PlanGroupHealthPlan   =  active_plan( $bill->plan_type , 'PlanGroupHealthPlan' , 'X');
			$bill->PlanFECA              =  active_plan( $bill->plan_type , 'PlanFECA' , 'X');
			$bill->PlanOther             =  active_plan( $bill->plan_type , 'PlanOther' , 'X');
			
			$bill->PatientRelationSELF   =  active_patientrelation( $bill->patient_relationship, 'PatientRelationSELF', 'X' );
			$bill->PatientRelationSPOUSE =  active_patientrelation( $bill->patient_relationship, 'PatientRelationSPOUSE', 'X' );
			$bill->PatientRelationCHILD  =  active_patientrelation( $bill->patient_relationship, 'PatientRelationCHILD', 'X' );
			$bill->PatientRelationOTHER  =  active_patientrelation( $bill->patient_relationship, 'PatientRelationOTHER', 'X' );

			$bill->patient 			   	= $this->Patient_DB->get( $bill->patient_id );
			if( $bill->patient_telephone  )
			{
				$telephone = str_replace(["(",")"," "],"", $bill->patient_telephone );
				$bill->patient_phone_code 	= substr($telephone, 0 , 3 ); 
				$bill->patient_phone_number = substr($telephone, 3 , strlen( $telephone) ); 
			}
			else
			{
				$bill->patient_phone_code = $bill->patient_phone_number = '';
			}

			if($bill->insured_telephone)
			{
				$bill->insured_telephone_code 	= substr($bill->insured_telephone, 0, 3);
				$bill->insured_telephone_number = substr($bill->insured_telephone, 3);
			}

			
			$bill->encounter 			= $this->Encounter_DB->get($bill->encounter_id);
			$bill->doctor 				= $this->User_DB->get( $bill->encounter->user_id ); 
			$bill->biller 				= $this->User_DB->get( $bill->user_id ); 
			
			$bill->date_patient_work_from = $this->_get_format_date($bill->date_patient_work_from);
			$bill->date_patient_work_to   = $this->_get_format_date($bill->date_patient_work_to);
			$bill->date_hospital_from     = $this->_get_format_date($bill->date_hospital_from);
			$bill->date_hospital_to       = $this->_get_format_date($bill->date_hospital_to);
			
			$bill->service_1 = $this->Billing_DB->getService( $bill->id , 1);
			$bill->service_2 = $this->Billing_DB->getService( $bill->id , 2);
			$bill->service_3 = $this->Billing_DB->getService( $bill->id , 3);
			$bill->service_4 = $this->Billing_DB->getService( $bill->id , 4);
			$bill->service_5 = $this->Billing_DB->getService( $bill->id , 5);
			$bill->service_6 = $this->Billing_DB->getService( $bill->id , 6);
			
			$pdfBill->page( $bill );
			
			if(count($bill->extraCharges))
			{
				
				foreach ($bill->extraCharges as $pos => $charges) {

					for($i=1; $i<7; $i++ )
					{
						$bill->{"service_".$i}->active                = $charges->{"active_$i"};
						$bill->{"service_".$i}->procedure_cpt_hcpcs   = $charges->{"procedure_cpt_hcpcs_$i"};
						$bill->{"service_".$i}->place_of_service      = $charges->{"place_of_service_$i"};
						$bill->{"service_".$i}->emg                   = $charges->{"emg_$i"};
						$bill->{"service_".$i}->modifier_a            = $charges->{"modifier_a_$i"};
						$bill->{"service_".$i}->modifier_b            = $charges->{"modifier_b_$i"};
						$bill->{"service_".$i}->modifier_c            = $charges->{"modifier_c_$i"};
						$bill->{"service_".$i}->modifier_d            = $charges->{"modifier_d_$i"};
						$bill->{"service_".$i}->diagnosis_pointer     = $charges->{"diagnosis_pointer_$i"};
						$bill->{"service_".$i}->charges               = $charges->{"charges_$i"};
						$bill->{"service_".$i}->days_units            = $charges->{"days_units_$i"};
						$bill->{"service_".$i}->family_plan           = $charges->{"family_plan_$i"};
						$bill->{"service_".$i}->id_qual               = $charges->{"id_qual_$i"};
						$bill->{"service_".$i}->rendering_provider_id = $charges->{"rendering_provider_id_$i"};
						$bill->{"service_".$i}->date_of_service       = $charges->{"date_of_service_$i"};

						
					}
					
					$bill->total_charge = $charges->total_charge;
					$pdfBill->page( $bill );
				}
			}

			//
			//$pdfBill->page( $bill );
		}
		
		if($this->input->get("printLines"))
		{
			$pdfBill->setLines();
		}

		$pdfBill->IncludeJS("print('true');");
		//$pdfBill->IncludeJS("print('true');");
		$pdfBill->output();
	}

	/**
	 * @route:toggleChangeSpecialFilter
	 */
	function toggleChangeSpecialFilter()
	{
		$specialFilter = Array(
			'encounter_id' => trim($this->input->get('encounter_id')),
			'date'         => trim($this->input->get('date')),
			'insurance'    => trim($this->input->get('insurance')),
			'biller'       => trim($this->input->get('biller')),
			'status'       => trim($this->input->get('status'))
		);
		$qb = $this->Billing_DB->db;
		
		$qb->select('billing.id')->from('billing');
		
		$qb->join('user', 'user.id=billing.user_id' , 'left');

		if( $encounterID = $specialFilter['encounter_id'] )
   	 		$qb->where( [ 'billing.encounter_id' => $encounterID ] );

        if( $insurance = $specialFilter['insurance'] )
        	$qb->like('billing.insurance_title', $insurance  );
        
        if( $date = $specialFilter['date'] )
        	$qb->like( [ "DATE_FORMAT(billing.create_at, '%m/%d/%Y')" => $date ] );
        
		$status = $specialFilter['status'];
		if( $status >= 0 )
			$qb->where( [ 'billing.status' => $status  ] );
		
        if( $biller = $specialFilter['biller'])
        	$qb->like( [ 'IFNULL(user.names,"")' => $biller ] );
        
		$qb->where( [ 'billing.status !=' =>  0 ] );

		if(!$encounterID && !$insurance && !$date && !is_numeric($status) && !$biller)
		{
			show_error('Please select an filter 1');
		}
		
		$bills 	= $qb->get()->result_array();
		if(!count($bills))
		{
			show_error('Please select an filter 2');
		}
		
		$data = [];
		foreach ($bills as $bill) {
			$data[] = $bill['id'];
		}

		//Change
		$queryStr = "UPDATE billing SET print=0, 
			print_date='".date('Y-m-d H:i:s')."',
			print_user_nickname='".$this->current_user->nick_name."' 
			WHERE id in (".implode(',', $data ).")";
		$this->db->query($queryStr);
		
		//Change2
		$queryStr2 = "UPDATE billing SET status=2 WHERE status=1 and id in (".implode(',', $data ).")";
		$this->db->query($queryStr2);
		
		return $this->template->json([
			'status' => 1,
			'message' => 'Facturas impresas '.count($data)
		]);
		//
	}


	/**
	 * @route:export-csv/(:num)
	 */
	function export_csv( $ID )
	{

		$this->load->model( [ 'Bill_CSV_Data' => 'Bill_CSV_Data'] );

		if( ! $bill = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		/*
		else if( $bill->status != 5 )
		{
			redirect('/billing/');
		}
		*/
		//PR($bill);
		//die();
		$data = $this->Bill_CSV_Data->content( $bill );
		
		if( $this->input->get('test') == 1 )
		{	
			$notFound = [];
			foreach ($data as $key =>  $value) {
				if($value==='__pending__'){
					$notFound[] = $key;
				} 
			}
			echo "<h1>No encontradas</h1>";
			PR( $notFound );
			//echo "<p>".implode(", ", $notFound)."</p>";
			echo "<hr><h1>Todos</h1>";
			PR( $data );
			exit;
		}

		$encounter_id = $bill->encounter_id;
		
		//$this->db->query("UPDATE billing SET print=0, status=2  WHERE status=1 and id=".$ID);

		$this->template->download_csv(
				"bill_{$encounter_id}.csv", 
				array( array_values($data)),
				array_keys($data),
				"\t"
			);
	}

	/**
	 * @route:export-selected
	 */
	function export_selected()
	{

		$this->load->model( [ 'Bill_CSV_Data' => 'Bill_CSV_Data'] );
		$dataValues = Array();
		$columns = [
			'encounter_id',
			'patient_id',
			'user_id',
			'id',
			'insurance_number',
			'insured_address',
			'insured_city',
			'insured_state',
			'insured_zipcode',
			'insured_telephone',
			'plan_type',
			'insured_last_name',
			'insured_first_name',
			'insured_middle_initial',
			'patient_address',
			'patient_city',
			'patient_state',
			'patient_zipcode',
			'patient_relationship',
			'patient_state',
			'patient_telephone',
			'patient_marital_status',
			'insured_other_last_name',
			'insured_other_first_name',
			'insured_other_middle_initial',
			'insured_other_policy',
			'patient_condition_employment',
			'patient_condition_autoaccident',
			'patient_condition_autoaccident_place',
			'patient_condition_otheraccident',
			'insurance_title',
			'other_benefit_plan',
			'other_benefit_plan',
			'date_patient_work_from',
			'date_patient_work_to',
			'name_referring',
			'date_hospital_from',
			'date_hospital_to',
			'aditional_claim',
			'outside_lab',
			'outside_lab_fee',
			'diagnosis_illness_a',
			'diagnosis_illness_b',
			'diagnosis_illness_c',
			'diagnosis_illness_d',
			'diagnosis_illness_e',
			'diagnosis_illness_f',
			'diagnosis_illness_g',
			'diagnosis_illness_h',
			'diagnosis_illness_i',
			'diagnosis_illness_j',
			'diagnosis_illness_k',
			'diagnosis_illness_l',
			'resubmission_code',
			'original_ref_no',
			'authorization_number',
			'patients_account',
			'accept_assignment',
			'total_charge',
			'amount_paid',
		];

		$billPrinted = $this->db->query("SELECT ".implode(",", $columns)." FROM billing WHERE print=1 ")->result_array();
		$billIds     = Array();

		foreach ($billPrinted as $bill ) {
			$billIds[]    = $bill['id'];
			$dataValues[] = array_values($this->Bill_CSV_Data->contentArray( $bill ) );
		}
		
		if(count($dataValues))
		{
			$sqlQuery1 = "UPDATE billing SET print=0, status=2 WHERE id in (".implode(",", $billIds).") and print=1 and status=1 ";
			$this->db->query($sqlQuery1);

			$sqlQuery2 = "UPDATE billing SET print=0 WHERE id in (".implode(",", $billIds).") and print=1 ";
			$this->db->query($sqlQuery2);
			
			$this->template->download_csv(
				"bill_multiple.csv", 
				$dataValues,
				Array(),
				"\t"
			);
		}
	}

	/**
	 * @route:{post}togglePrint
	 */
	function togglePrint()
	{
		$checkPrint = $this->input->post('checkPrint');
		$bills      = $this->input->post('billsPending');
		
		if(!is_array($bills))
		{
			show_error('Error');
		}

		if($checkPrint)
		{
			$this->db->query("UPDATE billing SET print=1 WHERE id in (".implode(",",$bills).")");
		}
		else
		{
			$this->db->query("UPDATE billing SET print=0 WHERE id in (".implode(",",$bills).")");
		}

		return $this->template->json([
			'status' => 1,
			'message' => 'ok'
		]);
	}

	/**
	 * @route:toggle-print/(:num)
	 */
	function toggle_print( $ID )
	{
		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			$this->template->json(['status' => 0, 'message' =>  'Facturación no encontrada']);
		}
		else if( $billing->status == 0 )
		{
			$this->template->json(['status' => 0, 'message' => 'Check status Bill']);
		}
		
		$this->Billing_DB->print = ( $billing->print ) ? 0 : 1; 
		$this->Billing_DB->save( $ID );

		$pendingPrint = $this->db->from('billing')->where(['print' => 1 ])->count_all_results();

		$this->template->json([
			'status' => 1, 
			'pendingPrint' => $pendingPrint 
		]);
	}

	/**
	 * @route:{post}done/(:num)
	 */
	function done( $ID )
	{
		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			//redirect('/billing/');
		}
		else if( $billing->status != 0 )
		{
			//redirect('/billing/');
		}
		
		$response = $this->_save_data( $ID, TRUE , $billing );

		$billing  = $this->Billing_DB->get_info( $ID );

		$not_edit        = ( in_array( $billing->status , [ 5 , 6 ]) ) ?  true : false;
		$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

		$billing->detail       = $this->Billing_DB->get_detail( $ID );
		$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
		
		$response['refresh'] = [
			'billing' => $billing,
			'not_edit' => $not_edit,
			'can_print' => $can_print,
		];

		$this->template->json( $response ); 
	}

	/**
	 * @route:{post}update/(:num)
	 */
	function update( $ID )
	{
		
		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		else if( in_array($billing->status, [5,6] ) )
		{
			$this->template->json(['status' => 0, 'message' => 'La factura esta pagada o denegada']);
		}

		$response = $this->_save_data( $ID, FALSE );


		$billing = $this->Billing_DB->get_info( $ID );

		$not_edit        = ( in_array( $billing->status , [ 5 , 6 ]) ) ?  true : false;
		$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

		$encounter             = $this->Encounter_DB->get_info($billing->encounter_id );
		$billing->detail       = $this->Billing_DB->get_detail( $ID );
		$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
		
		$response['refresh'] = [
			'billing' => $billing,
			'not_edit' => $not_edit,
			'can_print' => $can_print,
			
		];
		
		$this->template->json( $response , false ); 
	}

	/**
	 * @route:{post}denied/(:num)
	 */
	function denied( $ID )
	{

		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		else if( in_array($billing->status, [5,6] ) )
		{
			$this->template->json(['status' => 0, 'message' => 'La factura esta pagada o denegada']);
		}

		$this->form_validation
			->set_rules('comments','Comentarios','required|trim|max_length[500]')
			->set_rules('pin','Pin de usuario','required|trim|pin_verify')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response = [
				'status' => 0,
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{

			$this->Billing_DB->status   = 6;
			$this->Billing_DB->comments = $this->input->post('comments');
			$this->Billing_DB->save( $ID );

			$billing = $this->Billing_DB->get_info( $ID );

			$not_edit        = ( in_array( $billing->status , [ 5 , 6 ]) ) ?  true : false;
			$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

			$encounter             = $this->Encounter_DB->get_info( $billing->encounter_id );
			$billing->detail       = $this->Billing_DB->get_detail( $ID );
			$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
			
			$response = [
				'status' => 1,
				'message' => 'Factura denegada',
				'refresh' => [
					'billing' => $billing,
					'not_edit' => $not_edit,
					'can_print' => $can_print,
					
				]
			];
		}

		
		
		$this->template->json( $response , false ); 
	}

	/**
	 * @route:{post}setComments/(:num)
	 */
	function setComments( $ID )
	{

		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		else if( $billing->status != 0 )
		{
			$this->template->json([
				'status' => 0, 
				'message' => 'Factura no esta pendiente',
				'aux' => $billing
			]);
		}

		$this->form_validation
			->set_rules('comments','Comentaro','required|trim|max_length[500]')
			->set_rules('pin','Pin de usuario','required|trim|pin_verify')
		;

		if($this->form_validation->run() === FALSE )
		{
			$response = [
				'status' => 0,
				'message' => $this->form_validation->error_string()
			];
		}
		else
		{

			$this->Billing_DB->status   = 7;
			$this->Billing_DB->comments = $this->input->post('comments');
			$this->Billing_DB->save( $ID );

			$billing = $this->Billing_DB->get_info( $ID );

			$not_edit        = ( in_array( $billing->status , [ 5 , 6, 7 ]) ) ?  true : false;
			$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

			$encounter             = $this->Encounter_DB->get_info( $billing->encounter_id );
			$billing->detail       = $this->Billing_DB->get_detail( $ID );
			$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
			
			$response = [
				'status' => 1,
				'message' => 'Facura fue cambiada a comentada',
				'refresh' => [
					'billing' => $billing,
					'not_edit' => $not_edit,
					'can_print' => $can_print,
					
				]
			];
		}

		
		
		$this->template->json( $response , false ); 
	}

	/**
	 * @route:{post}update-status/(:num)
	 */
	function update_status( $ID )
	{
		
		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		else if( in_array($billing->status, [0,1,5,6] ) )
		{
			$this->template->json(['status' => 0, 'message' => 'Favor de checar el estatus de factura']);
		}

		$response = $this->_save_data_status( $ID, FALSE );

		$billing = $this->Billing_DB->get_info( $ID );

		$not_edit        = ( in_array( $billing->status , [ 5 , 6 ]) ) ?  true : false;
		$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

		$encounter       = $this->Encounter_DB->get_info($billing->encounter_id );
		$billing->detail = $this->Billing_DB->get_detail( $ID );
		$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
		
		$response['refresh'] = [
			'billing' => $billing,
			'not_edit' => $not_edit,
			'can_print' => $can_print,
			
		];
		
		$this->template->json( $response , false ); 
	}

	/**
	 * @route:{get}detail/(:num)
	 */
	function detail( $ID )
	{

		$this->load->model([
			'Custom_Setting_Model' => 'Custom_Setting_DB'
		]);
		$providers = $this->User_DB->getProviders();

		if( ! $billing = $this->Billing_DB->getRowBy( ['encounter_id' => $ID] ) ) 
		{	
			redirect('/billing/');
		}

		$billing = $this->Billing_DB->refreshPatientData( $billing );
		
		//$plans = $this->Custom_Setting_DB->getElements('setting_bill_insurance_plans');
		$patient = $this->Patient_DB->get($billing->patient_id);

		$plans = Array();
		if($patient)
		{
			$plans[] = $patient->insurance_primary_plan_name;
			if($patient->insurance_secondary_plan_name)
				$plans[] = $patient->insurance_secondary_plan_name;
		}

		
		$this->template
			->set_title('Billing detail '. $billing->encounter_id)
			->body([
				'ng-app' => 'app_billing_detail',
				'ng-controller' => 'ctrl_billing_detail',
				'ng-init' => 'initialize('.$billing->id.')'
			])
			->modal('patient/relatedfiles/modal-preview-files', ['title' => 'Documento','size' => 'modal-xl'] )
			->modal('billing/modal.denied', ['title' => 'Denegar facturación','size' => 'modal-md'] )
			->modal('billing/modal.comments', ['title' => 'Comentar factura','size' => 'modal-md'] )
			->js('billing/billing.detail')
			->render('billing/view.panel.billing.detail',[
				'setting_bill_insurance_plans' => $plans,
				'options_insurances' =>  $this->Custom_Setting_DB->getElements('setting_insurance'),
				'providers' => $providers,
			] );
	}
		
	/**
	 * @route:{get}data-edit/(:num)
	 */
	function data_edit( $ID )
	{

		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}

		$billing->detail       = $this->Billing_DB->get_detail( $ID );
		$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
		
		$Data = [
			'billing' => $billing,
		];
		
		$this->template->json($Data, false );
	}
	
	/**
	 * @route:initialize/(:num)
	 */
	function initialize( $ID )
	{

		if( ! $billing = $this->Billing_DB->get_info( $ID ) ) 
		{	
			redirect('/billing/');
		}
		
		$this->load->model([
			'Encounter_Diagnosis_Model' => 'Encounter_Diagnosis_DB',
			'Encounter_Medication_Model' => 'Encounter_Medication_DB',
			'Encounter_Referrals_Model' => 'Encounter_Referrals_DB',
			'Encounter_Physicalexam_Model' => 'Encounter_Physicalexam_DB',
			'Encounter_Results_Model' => 'Encounter_Results_DB',
			'Encounter_Addendum_Model' => 'Encounter_Addendum_DB',
			'Encounter_Child_Model' => 'Encounter_Child_DB',
			'Patient_Related_Files_Model' => 'Patient_Related_Files_DB',
		]);
		
		/**
		 * return [
			0 => 'Pending',
			1 => 'Complete',
			2 => 'Sent',
			3 => 'Partial Payment',
			4 => 'Re Billed',
			5 => 'Paid',
			6 => 'Denied'
		];
		 */

		$not_edit        = ( in_array( $billing->status , [ 5 , 6 , 7]) ) ?  true : false;
		$can_print 		 = ( in_array( $billing->status,  [ 1, 2, 3, 4, 5 ] )) ? true : false;

		$encounter             = $this->Encounter_DB->get_info($billing->encounter_id );
		$billing->detail       = $this->Billing_DB->get_detail( $ID );
		$billing->extraCharges = $this->Billing_Charges_DB->getResultsBy(['billing_id' => $billing->id ]);
		
		$administration        = \libraries\Administration::init();

		
		$documents = $this->Patient_Related_Files_DB->getPagination(1000,1,null, [
			'encounter_id' => $billing->encounter_id 
		]);

		
		$Data = [
			'not_edit' => $not_edit,
			'can_print' => $can_print,
			'billing' => $billing,
			'plan_types' => $this->Billing_DB->get_plan_types(),
			'patient_relationship' => $this->Billing_DB->get_patient_relationship(),
			'information' => $this->Billing_DB->get_information( $ID ),
			'encounter' => $encounter,
			'encounter_diagnosis' => $this->Encounter_Diagnosis_DB->getResultsBy(['encounter_id' => $encounter->id ]),
			'encounter_medications' => $this->Encounter_Medication_DB->getResultsBy(['encounter_id' => $encounter->id] ),
			'encounter_referrals' => $this->Encounter_Referrals_DB->getResultsBy(['encounter_id' => $encounter->id ]),
			'encounter_physicalexam' => $this->Encounter_Physicalexam_DB->getResultsBy(['encounter_id' => $encounter->id ]),
			'encounter_results' =>  $this->Encounter_Results_DB->getResultsBy(['encounter_id' => $encounter->id]),
			'status_results' => $this->Encounter_Results_DB->get_status(),
			'encounter_results_availible' => $this->Encounter_Results_DB->get_results_availible(),
			'encounter_addendums' => $this->Encounter_Addendum_DB->get_data( $encounter->id ),
			'catalog_results' =>  $this->Custom_Setting_DB->getElements('setting_request',true),
			'status_referrals'	=> $this->Encounter_Referrals_DB->getStatus(),
			'encounter_child' => $this->Encounter_Child_DB->get_data( $encounter->id ),
			'additional_claim_data' => $this->Billing_DB->additionalClaimData(),
			'providerManager'=> [
				'signature' =>  $administration->getValue('billing_provider_name'),
				'npi'       =>  $administration->getValue('billing_provider_npi')
			],
		  	'documents' => $documents
		];
		
		$this->template->json($Data, false );
	}
	
	private function _save_data_status( $ID, $complete = false )
	{
		$response['status'] = 0;
		
		$field_required = ( $complete ) ? '|required' : '';
		
		$this->form_validation
			->set_rules('comments','Comentario','xss_clean|trim')
			->set_rules('total_paid','Pago','numeric')
			->set_rules('total_charge','Cargos','numeric')
			->set_rules('total_due','Pendiente de pago','numeric')
			->set_rules('status','Estatus','required|in_list[2,3,4,5]')
		;

		if($this->input->post('status') == 5 )
		{	
			$this->form_validation
				->set_rules('pin','PIN de usuario','required|trim|pin_verify')
			;
		}

		$details         = $this->input->post('detail');
		$details         = is_array($details) ? $details : [];
		$total_write_off = $total_due  = $total_paid = 0;
		
		foreach ($details as $pos => $det ) 
		{
			$position        =  $pos + 1;
			$total_paid      += $this->input->post('detail['.$pos.'][paid]');
			$total_write_off += $this->input->post('detail['.$pos.'][write_off]');

			$this->form_validation
				->set_rules('detail['.$pos.'][procedure_cpt_hcpcs]',"Procedimientos OPT/HCPCS ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][paid]',"Pago ( $position )", 'trim|numeric')
				->set_rules('detail['.$pos.'][write_off]',"Anulación ( $position )", 'trim|numeric')
			;
		}

		if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		} 
		else if(  count($details) != 6 )
		{
			$response['message'] = 'Es posible agregar un máximo de 6 detalles y un mínimo de 1';
		} 
		else
		{	

			$this->Billing_DB->total_paid      = $total_paid;
			$this->Billing_DB->total_write_off = $total_write_off;
			$this->Billing_DB->total_due       = $this->input->post('total_due');

			$this->Billing_DB->comments        = is_null($this->input->post('comments')) ? '' : $this->input->post('comments');
			$this->Billing_DB->status          = $this->input->post('status');
			
			$this->Billing_DB->save( $ID );
			$this->Billing_DB->save_details_status( $details, $ID );

			$response = [
				'status' => 1,
				'message' => 'Facturación guardada'
			];
			
		}

		return $response;
	}

	private function _save_data( $ID, $complete = false, $billing = null )
	{
		$response['status'] = 0;
		
		$field_required = ( $complete ) ? '|required' : '';
		
		$this->form_validation
			->set_rules('insurance_title','Insurance plan','trim|required')
			->set_rules('insurance_number','Insurance ID','trim|required')
			->set_rules('plan_type','Plan type','numeric|in_list[0,1,2,3,4,5,6]'.$field_required)
			->set_rules('patient_relationship','Patient relationship','numeric|in_list[0,1,2,3]'.$field_required)

			->set_rules('insured_last_name',"Insured's last name",'trim|xss_clean|max_length[50]')
			->set_rules('insured_first_name',"Insured's first name",'trim|xss_clean|max_length[50]')
			->set_rules('insured_middle_initial',"Insured's middle initial",'trim|xss_clean|max_length[50]')
			
			->set_rules('patient_address',"Insured's address",'trim|xss_clean|max_length[150]')
			->set_rules('patient_city',"Insured's city",'trim|xss_clean|max_length[75]')
			->set_rules('patient_state',"Insured's state",'trim|xss_clean|max_length[20]')
			->set_rules('patient_zip_code',"Insured's zip code",'trim|xss_clean|max_length[20]')
			->set_rules('patient_telephone',"Insured's telephone",'trim|xss_clean|max_length[10]')

			->set_rules('insured_address',"Insured's address",'trim|xss_clean|max_length[50]')
			->set_rules('insured_city',"Insured's city",'trim|xss_clean|max_length[50]')
			->set_rules('insured_state',"Insured's state",'trim|xss_clean|max_length[50]')
			->set_rules('insured_zipcode',"Insured's zip code",'trim|xss_clean|max_length[20]')
			->set_rules('insured_telephone',"Insured's telephone",'trim|xss_clean|max_length[10]')

			->set_rules('other_benefit_plan','Other benefit plan','in_list[Yes,No]')
			
			->set_rules('patient_condition_employment','Patient employment','required|in_list[Yes,No]')
			->set_rules('patient_condition_autoaccident','Patient autoaccident','required|in_list[Yes,No]')
			->set_rules('patient_condition_otheraccident','Patient other accident','required|in_list[Yes,No]')
			->set_rules('patient_condition_claimcodes','Patient claimcodes','xss_clean|trim|max_length[100]')
			
			->set_rules('date_patient_work_from', 'Date patient work from', 'trim|exist_date')
			->set_rules('date_patient_work_to', 'Date patient work to', 'trim|exist_date')
			
			->set_rules('date_hospital_from', 'Date hospital from', 'trim|exist_date')
			->set_rules('date_hospital_to', 'Date hospital to', 'trim|exist_date')
			->set_rules('aditional_claim','Aditional claim','xss_clean|trim|max_length[100]')
			->set_rules('outside_lab','Outside lab','required|in_list[Yes,No]')
			
			->set_rules('diagnosis_illness_a','Diagnosis illness A','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_b','Diagnosis illness B','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_c','Diagnosis illness C','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_d','Diagnosis illness D','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_e','Diagnosis illness E','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_f','Diagnosis illness F','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_g','Diagnosis illness G','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_h','Diagnosis illness H','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_i','Diagnosis illness I','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_j','Diagnosis illness J','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_k','Diagnosis illness K','xss_clean|trim|max_length[8]')
			->set_rules('diagnosis_illness_l','Diagnosis illness L','xss_clean|trim|max_length[8]')

			->set_rules('resubmission_code','Resubmission code','trim|xss_clean|max_length[50]')
			->set_rules('original_ref_no','Original ref. no.','trim|max_length[50]')
			->set_rules('authorization_number','Prior authorization','trim|max_length[50]')
			
			->set_rules('patients_account','Patients account','xss_clean|trim')
			->set_rules('accept_assignment','Accept assignment','required|in_list[Yes,No]')
			
			->set_rules('amount_paid','Amount paid','trim|numeric|callback__valid_paid'.$field_required)
			->set_rules('rsvd_for_nucc','RSVD for nucc','trim|xss_clean|max_length[50]'.$field_required)
		;

		$providerSignature = $providerNPI = "";
		if($this->input->post('type_provider') == 1 )
		{
			$selectProvider = explode('|',$this->input->post('select_provider'));
			if( !isset($selectProvider[1]) )
			{
				return Array(
					'status' => 0,
					'message' => 'El campo Seleccionar proveedor es obligatorio'
				);
			}
			
			$providerSignature = $selectProvider[0];
			$providerNPI       = $selectProvider[1];
			
			$data = $this->db
				->select('id')
				->from('user')
				->where(Array(
					'digital_signature' => $providerSignature,
					'medic_npi' => $providerNPI,
					'medic_npi !=' => ''
				))->get()->row_array();

			if( !is_array($data) || !count($data) )
			{
				return Array(
					'status' => 0,
					'message' => 'El campo Seleccionar proveedor es obligatorio, proveedor incorrecto'
				);
			}
		}

		if($this->input->post('outside_lab') === 'Yes')
		{	
			$this->form_validation->set_rules('outside_lab_fee','Tarifa de laboratorio externo','xss_clean|numeric'.$field_required);
		}

		

		if($this->input->post('patient_condition_autoaccident') === 'Yes')
		{	
			$this->form_validation->set_rules('patient_condition_autoaccident_place','Lugar del accidente automovilístico del paciente','xss_clean|trim|max_length[100]'.$field_required);
		}

		if($this->input->post('other_benefit_plan') === 'Yes')
		{	
			$this->form_validation
				->set_rules('insured_other_last_name',"Apellido del otro asegurado",'trim|xss_clean|max_length[50]'.$field_required)
				->set_rules('insured_other_first_name',"Nombre del otro asegurado",'trim|xss_clean|max_length[50]'.$field_required)
				->set_rules('insured_other_middle_initial',"Segundo nombre del otro asegurado",'trim|xss_clean|max_length[50]'.$field_required)
				->set_rules('insured_other_policy',"Póliza del otro asegurado",'trim|xss_clean|max_length[50]'.$field_required)
				->set_rules('insured_other_insurance_plan_name',"Nombre del plan del asegurado",'trim|xss_clean|max_length[50]'.$field_required)
			;
		} 

		$details                  = $this->input->post('detail');
		$extraCharges  			  = $this->input->post('extraCharges');
		if(is_array($extraCharges) && count($extraCharges)>0 )
		{
			foreach ($extraCharges as $key => $validCharges) {
				$position = $key + 2;
				for($i=1; $i<7;$i++)
				{	
					if( $validCharges['procedure_cpt_hcpcs_'.$i] === '' )
					{
						continue;
					}

					$this->form_validation
						->set_rules('extraCharges['.$key.'][place_of_service_'.$i.']','Cargos ( '.$position.' ) 	Lugar del servicio', 'trim')
						->set_rules('extraCharges['.$key.'][emg_'.$i.']','Cargos('.$position.') EMG', 'trim|numeric')
						->set_rules('extraCharges['.$key.'][procedure_cpt_hcpcs_'.$i.']',"Cargos(".$position.") Procedimientos OPT/HCPCS", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][modifier_a_'.$i.']',"Cargos(".$position.") Modificador A", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][modifier_b_'.$i.']',"Cargos(".$position.") Modificador B", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][modifier_c_'.$i.']',"Cargos(".$position.") Modificador C", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][modifier_d_'.$i.']',"Cargos(".$position.") Modificador D", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][diagnosis_pointer_'.$i.']',"Cargos(".$position.") Indicador de diagnóstico", 'trim|xss_clean|max_length[10]')
						->set_rules('extraCharges['.$key.'][charges_'.$i.']',"Cargos(".$position.") ", 'trim|numeric')
						->set_rules('extraCharges['.$key.'][family_plan_'.$i.']',"Cargos(".$position.") Plan familiar", 'trim|numeric')
						->set_rules('extraCharges['.$key.'][days_units_'.$i.']',"Cargos(".$position.") Días por unidad	", 'trim')
						->set_rules('extraCharges['.$key.'][id_qual_'.$i.']',"Cargos(".$position.") ID de Calificación	", 'trim|numeric')
						->set_rules('extraCharges['.$key.'][rendering_provider_id_'.$i.']',"Cargos(".$position.") Id de proveedor de servicios",'trim|numeric')
						->set_rules('extraCharges['.$key.'][active_'.$i.']',"Cargos(".$position.") Id activo",'in_list[0,1]')
					;
				}
			}
		}

		$details                  = is_array($details) ? $details : [];
		$total_charge             = 0;
		$flag 					  = false;
		foreach ($details as $pos => $det ) 
		{
			$position 	   =  $pos + 1;
			$total_charge +=  floatval($det['charges']);
			
			if( $det['procedure_cpt_hcpcs'] === '' )
			{
				continue;
			}
			
			$flag = TRUE;	
			
			$greater_than = ($complete ) ? "|greater_than[0]" : '';
			
			$this->form_validation
				->set_rules('detail['.$pos.'][place_of_service]',"Lugar del servicio ( $position )", 'trim|numeric'.$field_required)
				->set_rules('detail['.$pos.'][emg]',"EMG ( $position )", 'trim|numeric')
				->set_rules('detail['.$pos.'][procedure_cpt_hcpcs]',"Procedimientos OPT/HCPCS ( $position )", 'trim|xss_clean|max_length[10]'.$field_required)
				->set_rules('detail['.$pos.'][modifier_a]',"Modificador A ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][modifier_b]',"Modificador B ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][modifier_c]',"Modificador C ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][modifier_d]',"Modificador D ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][diagnosis_pointer]',"Indicador de diagnóstico ( $position )", 'trim|xss_clean|max_length[10]')
				->set_rules('detail['.$pos.'][charges]',"Cargos ( $position )", 'trim|numeric')
				->set_rules('detail['.$pos.'][days_units]',"Días por unidad	 ( $position )", 'trim|max_length[10]')
				->set_rules('detail['.$pos.'][family_plan]',"Plan familiar ( $position )", 'trim|numeric')
				->set_rules('detail['.$pos.'][id_qual]',"ID de Calificador( $position )", 'trim|numeric')
				->set_rules('detail['.$pos.'][rendering_provider_id]',"Id de proveedor de servicios ( $position )",'trim|numeric')
				->set_rules('detail['.$pos.'][active]',"ID activo ( $position )",'in_list[0,1]')
				->set_rules('detail['.$pos.'][notes_unit]',"Notas ( $position )",'trim')
			;
		}

		$_POST['total_charge'] = $total_charge;

		if(!$flag && $complete)
		{	
			$response['message'] = 'Debe capturar al menos un servicio';
		}	
		else if( $this->form_validation->run() === FALSE )
		{	
			$response['message'] = $this->form_validation->error_string();
		} 
		else if(  count($details) != 6 )
		{
			$response['message'] = 'Es posible agregar un máximo de 6 detalles y un mínimo de 1';
		} 
		else
		{	
			
			$this->Billing_DB->insurance_title 						= $this->input->post('insurance_title');
			$this->Billing_DB->insurance_number 					= $this->input->post('insurance_number');
			$this->Billing_DB->plan_type                            = $this->input->post('plan_type');
			$this->Billing_DB->patient_relationship                 = $this->input->post('patient_relationship');
			$this->Billing_DB->insured_last_name                    = strtoupper( $this->input->post('insured_last_name'));
			$this->Billing_DB->insured_first_name                   = strtoupper( $this->input->post('insured_first_name'));
			$this->Billing_DB->insured_middle_initial               = strtoupper( $this->input->post('insured_middle_initial'));
			
			$this->Billing_DB->patient_address                      = strtoupper( $this->input->post('patient_address'));
			$this->Billing_DB->patient_city                         = strtoupper( $this->input->post('patient_city'));
			$this->Billing_DB->patient_state                        = strtoupper( $this->input->post('patient_state'));
			$this->Billing_DB->patient_zipcode                      = $this->input->post('patient_zipcode');
			$this->Billing_DB->patient_telephone                    = $this->input->post('patient_telephone');
			
			$this->Billing_DB->insured_address                      = strtoupper( $this->input->post('insured_address'));
			$this->Billing_DB->insured_city                         = strtoupper( $this->input->post('insured_city'));
			$this->Billing_DB->insured_state                        = strtoupper( $this->input->post('insured_state'));
			$this->Billing_DB->insured_zipcode                      = $this->input->post('insured_zipcode');
			$this->Billing_DB->insured_telephone                    = $this->input->post('insured_telephone');
			
			$this->Billing_DB->other_benefit_plan                   = $this->input->post('other_benefit_plan');
			$this->Billing_DB->insured_other_last_name              = strtoupper( $this->input->post('insured_other_last_name') );
			$this->Billing_DB->insured_other_first_name             = strtoupper( $this->input->post('insured_other_first_name') );
			$this->Billing_DB->insured_other_middle_initial         = strtoupper( $this->input->post('insured_other_middle_initial') );
			$this->Billing_DB->insured_other_policy                 = strtoupper( $this->input->post('insured_other_policy') );
			$this->Billing_DB->insured_other_insurance_plan_name    = $this->input->post('insured_other_insurance_plan_name');
			$this->Billing_DB->patient_condition_employment         = $this->input->post('patient_condition_employment');
			$this->Billing_DB->patient_condition_autoaccident       = $this->input->post('patient_condition_autoaccident');
			$this->Billing_DB->patient_condition_otheraccident      = $this->input->post('patient_condition_otheraccident');
			$this->Billing_DB->patient_condition_autoaccident_place = strtoupper( $this->input->post('patient_condition_autoaccident_place'));
			$this->Billing_DB->patient_condition_claimcodes         = $this->input->post('patient_condition_claimcodes');
			$this->Billing_DB->date_patient_work_from               = $this->input->post('date_patient_work_from');
			$this->Billing_DB->date_patient_work_to                 = $this->input->post('date_patient_work_to');
			
			$this->Billing_DB->aditional_claim                      = strtoupper( $this->input->post('aditional_claim') );
			$this->Billing_DB->outside_lab                          = $this->input->post('outside_lab');
			$this->Billing_DB->outside_lab_fee                      = $this->input->post('outside_lab_fee');
			$this->Billing_DB->diagnosis_illness_a                  = $this->input->post('diagnosis_illness_a');
			$this->Billing_DB->diagnosis_illness_b                  = $this->input->post('diagnosis_illness_b');
			$this->Billing_DB->diagnosis_illness_c                  = $this->input->post('diagnosis_illness_c');
			$this->Billing_DB->diagnosis_illness_d                  = $this->input->post('diagnosis_illness_d');
			$this->Billing_DB->diagnosis_illness_e                  = $this->input->post('diagnosis_illness_e');
			$this->Billing_DB->diagnosis_illness_f                  = $this->input->post('diagnosis_illness_f');
			$this->Billing_DB->diagnosis_illness_g                  = $this->input->post('diagnosis_illness_g');
			$this->Billing_DB->diagnosis_illness_h                  = $this->input->post('diagnosis_illness_h');
			$this->Billing_DB->diagnosis_illness_i                  = $this->input->post('diagnosis_illness_i');
			$this->Billing_DB->diagnosis_illness_j                  = $this->input->post('diagnosis_illness_j');
			$this->Billing_DB->diagnosis_illness_k                  = $this->input->post('diagnosis_illness_k');
			$this->Billing_DB->diagnosis_illness_l                  = $this->input->post('diagnosis_illness_l');
			$this->Billing_DB->resubmission_code                    = $this->input->post('resubmission_code');
			$this->Billing_DB->original_ref_no                      = $this->input->post('original_ref_no');
			$this->Billing_DB->authorization_number                 = $this->input->post('authorization_number');
			
			$this->Billing_DB->accept_assignment                    = $this->input->post('accept_assignment');
			$this->Billing_DB->total_charge                         = $this->input->post('total_charge');
			$this->Billing_DB->amount_paid                          = $this->input->post('amount_paid');
			$this->Billing_DB->rsvd_for_nucc                        = $this->input->post('rsvd_for_nucc');
			$this->Billing_DB->type_provider 						= $this->input->post('type_provider');

			if( $this->input->post('type_provider') == 1 )
			{
				$this->Billing_DB->provider_name = $providerSignature;
				$this->Billing_DB->provider_npi  = $providerNPI;
			}
			else
			{
				$administration = \libraries\Administration::init();
				$this->Billing_DB->provider_name = $administration->getValue('billing_provider_name');
				$this->Billing_DB->provider_npi  = $administration->getValue('billing_provider_npi');
			}

			if( $complete )
			{
				
				$this->Billing_DB->status  = 1;
				$this->Billing_DB->user_id = $this->current_user->id;
				$this->Billing_DB->print   = 1;
				$this->Billing_DB->done_at = date('Y-m-d H:i:s');
			}

			$this->Billing_DB->save( $ID );
			$this->Billing_DB->save_details( $details, $ID );
			if(is_array($extraCharges) && count($extraCharges)>0 )
			{
				$this->Billing_Charges_DB->updateCharges($extraCharges);
			}
			$response = [
				'status' => 1,
				'message' => ($complete) ? 'La facturación se marcó como completada' : 'La facturacion fue actualizada'
			];
			
		}

		return $response;
	}

	private function _get_format_date( $date )
	{
		$format = new StdClass;
		if( $date === '')
		{	
			$format->month = '';
			$format->day   = '';
			$format->year  = '';
		}
		else
		{	
			$format->month = date('m', strtotime($date) );
			$format->day   = date('d', strtotime($date) );
			$format->year  = date('Y', strtotime($date) );
		}

		return $format;
	}
	
    public function _valid_paid( $val, $field  )
    {
		$charge      = isset( $_POST['total_charge'] ) ? $_POST['total_charge'] : 0;
		
		$paid        = $val; 
		
		$balance_due = $charge - $paid;
		
    	if($balance_due < 0 )
    	{
    		$this->form_validation->set_message('_valid_paid', 'El campo {field} no puede ser menor que el cargo total');
            return false;
    	}
    	else
    	{
    		return true;
    	}
    }

    /**
	 * @route:{post}toggleChange
	 */
    public function toggleChange()
    {
    	//Change to Sent
		$queryStr = "UPDATE billing SET status=2 WHERE status=1 and print=1 ";
		$this->db->query($queryStr);
		$affectedRowsSent = $this->db->affected_rows();
		$message = "{$affectedRowsSent} La facturación ha sido cambiada a enviada <br>";
		//Change
		$queryStr = "UPDATE billing SET print=0, 
			print_date='".date('Y-m-d H:i:s')."',
			print_user_nickname='".$this->current_user->nick_name."' WHERE print=1";
		$this->db->query($queryStr);
		$affectedRowsPrint = $this->db->affected_rows();
		$message.= "{$affectedRowsPrint} La facturación ha sido añadida con la fecha de impresión";

    	return $this->template->json([
    		'status' => 1,
    		'message' => $message,
    		'affectedRowsSent' => $affectedRowsSent,
    		'affectedRowsPrint' => $affectedRowsPrint,
    	]);
    }

    /**
     * @route:{post}addCharges/(:num)
     */
    public function addCharges($billingID)
    {
    	$billing = $this->Billing_DB->get($billingID);
    	if(!$billing)
    		show_error('Facturación no encontrada');

    	if( in_array( $billing->status , [ 5 , 6 ] ) )
    	{
    		return $this->template->json([
	    		'status' => 0,
	    		'message' => 'Por favor, verifica el estado de facturación',
    		]);
    	}
    	
		$this->Billing_Charges_DB->billing_id = $billingID;
    	$chargesID = $this->Billing_Charges_DB->save();

    	return $this->template->json([
    		'status' => 1,
    		'message' => 'Cargos agregados',
    		'charges' =>  $this->Billing_Charges_DB->get($chargesID),
    	], false );
    }

     /**
     * @route:{post}removeCharges/(:num)
     */
    public function removeCharges($chargesID)
    {
    	$charges = $this->Billing_Charges_DB->get($chargesID);
    	if(!$charges)
    		show_error('Cargos no encontrados');
    	
    	$billing = $this->Billing_DB->get($charges->billing_id);
    	if( in_array( $billing->status , [ 5 , 6 ] ) )
    	{
    		return $this->template->json([
	    		'status' => 0,
	    		'message' => 'Por favor, verifica el estado de facturación',
    		]);
    	}

 		$this->Billing_Charges_DB->delete($chargesID);
    	
    	return $this->template->json([
    		'status' => 1,
    		'message' => 'Cargos removidos',
    	]);
    }
}