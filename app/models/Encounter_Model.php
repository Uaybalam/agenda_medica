<?php
class Encounter_Model extends APP_Model
{
	private $_current_user;

	protected $timestamp = TRUE;
	
	protected $table   = 'encounter';      

		
	private $_status = [
		1 => 'Consultation process',
		2 => 'Signed'
	];	
	

	protected $fields = [
		'encounter.id',
		'encounter.chief_complaint',
		'encounter.patient_id',
		'encounter.status',
		'encounter.user_id',
		'encounter.has_insurance',
		'encounter.insurance_title',
		'encounter.insurance_number',
		"DATE_FORMAT(encounter.create_at,'%m/%d/%Y') as create_at",
		"encounter.signed_at",
		'encounter.current_medications',
		'encounter.next_appointment',
		//
		'encounter.heart_pulse',
		'encounter.heart_respiratory',
		'encounter.heart_temperature',
		'encounter.heart_hemoglobin',
		'encounter.heart_hematocrit',
		'encounter.heart_head_circ',
		'encounter.heart_last_menstrual_period',
		'encounter.physical_birth_weight',
		'encounter.physical_weight',
		'encounter.physical_height',
		'encounter.urinalysis_color',
		'encounter.urinalysis_specific_gravity',
		'encounter.urinalysis_ph',
		'encounter.urinalysis_protein',
		'encounter.urinalysis_glucose',
		'encounter.urinalysis_ketones',
		'encounter.urinalysis_bilirubim',
		'encounter.urinalysis_blood',
		'encounter.urinalysis_leuktocytes',
		'encounter.urinalysis_nitrite',
		'encounter.urinalysis_human_chorionic_gonadotropin',
		'encounter.condition_employment',
		'encounter.condition_autoaccident',
		'encounter.condition_other_accident',
		'encounter.condition_state',
		'encounter.eye_withglasses_left',
		'encounter.eye_withglasses_right',
		'encounter.eye_withglasses_both',
		'encounter.eye_withoutglasses_left',
		'encounter.eye_withoutglasses_right',
		'encounter.eye_withoutglasses_both',
		'encounter.audio_left_1000',
		'encounter.audio_left_2000',
		'encounter.audio_left_3000',
		'encounter.audio_left_4000',
		'encounter.audio_right_1000',
		'encounter.audio_right_2000',
		'encounter.audio_right_3000',
		'encounter.audio_right_4000',
		'encounter.eye_prescription_glasses',
		'encounter.eye_worn_during_exam',
		'encounter.eye_questions',
		//
		'encounter.procedure_patient_education',
		'encounter.present_illness_history',
		//
		'encounter.blood_pressure_sys',
		'encounter.blood_pressure_dia'
	];

	private $_fields_chart = [
		'encounter.id',
		"DATE_FORMAT(encounter.create_at,'%m/%d/%Y') as date",
		"DATE_FORMAT(encounter.create_at,'%h:%i %p') as time",
		'encounter.status',
		"encounter.chief_complaint",
		'appointment.id as appointment_id',
		'appointment.room'
	];

	
	function get_list_chart( $patient_id )
	{	
		$this->db->select( $this->_fields_chart )	
			->from('encounter')
			->join('appointment', 'encounter.appointment_id = appointment.id ', 'left')
			->join('patient', 'encounter.patient_id = patient.id ', 'left')
			->where(['encounter.patient_id' => $patient_id, 'patient.instance_id' => $_SESSION['User_DB']->instance_id])
			->order_by('encounter.id', 'DESC')
		;
		
		return $this->db->get()->result();
	}
	
	function get_data_chart( $encounter_id )
	{
		$this->db->select( $this->_fields_chart )
			->from('encounter')
			->join('appointment', 'encounter.appointment_id = appointment.id ', 'inner')
			->join('patient', 'encounter.patient_id = patient.id ', 'left')
			->where(['encounter.id' => $encounter_id, 'patient.instance_id' => $_SESSION['User_DB']->instance_id ])
		;
		
		return $this->db->get()->row();
	}

	function set_user( $current_user )
	{	
		$this->_current_user = $current_user;
	}

	function get_status( $encounter_id )
	{
		$this->db->select( 'status' )
			->from('encounter')
			->where(['id' => $encounter_id ]);
			
		return $this->db->get()->row();
	}

	function getStatus()
	{	
		return $this->_status;
	}
	
	function get_info_request( $encounter_id )
	{	
		$this->db->select([
				'encounter.id',
				'encounter.has_insurance',
				'encounter.insurance_title',
				'encounter.insurance_number',
				'encounter.appointment_id',
				'encounter.procedure_patient_education',
				'encounter.patient_id',
				'encounter.chief_complaint',
				"DATE_FORMAT(encounter.signed_at,'%m/%d/%Y %h:%i %p ') as signed_at",
				'encounter.user_id',
				'encounter.checked_out_id',
				'encounter.next_appointment',
				"concat(user.names,' ',user.last_name) as signed_by",
			])
			->from('encounter')
			->join('user','user.id = encounter.user_id','left')
			->where([ 'encounter.id' => $encounter_id])
		;

		return $this->db->get()->row();
	}

	function get_info( $encounter_id )
	{
		$this->db->select( $this->fields )
			->from('encounter')
			->where([ 'id' => $encounter_id])
		;
		
		if( $encounter = $this->db->get()->row() )
		{

			$encounter->heart_temperature           = ($encounter->heart_temperature > 0) ? $encounter->heart_temperature : '';
			$encounter->heart_hemoglobin            = ($encounter->heart_hemoglobin > 0) ? $encounter->heart_hemoglobin : '';
			$encounter->heart_hematocrit            = ($encounter->heart_hematocrit > 0) ? $encounter->heart_hematocrit : '';
			$encounter->heart_head_circ             = ($encounter->heart_head_circ > 0) ? $encounter->heart_head_circ : '';
			$encounter->physical_birth_weight       = ($encounter->physical_birth_weight > 0) ? $encounter->physical_birth_weight : '';
			$encounter->physical_weight             = ($encounter->physical_weight > 0) ? $encounter->physical_weight : '';
			$encounter->physical_height             = ($encounter->physical_height > 0) ? $encounter->physical_height : '';
			$encounter->urinalysis_specific_gravity = ($encounter->urinalysis_specific_gravity > 0) ? $encounter->urinalysis_specific_gravity : '';

			$BMI = '';
			if( $encounter->physical_weight >0 && $encounter->physical_height>0 )
			{			
				$BMI = $encounter->physical_weight / 
					(  $encounter->physical_height  * $encounter->physical_height );

				$BMI = round( $BMI , 2 );

			}
			
			$encounter->physical_bmi = $BMI;
			
			//#hide_heart
			$encounter->hide_heart = ( $encounter->heart_pulse ==='' 
				&& $encounter->heart_respiratory === ''
				&& $encounter->heart_temperature === '' 
				&& $encounter->heart_hemoglobin === '' 
				&& $encounter->heart_hematocrit === '' 
				&& $encounter->heart_head_circ === '' 
				&& $encounter->heart_last_menstrual_period === '' ) ? 1 : 0;
			//#hide_physical
			$encounter->hide_physical = ( $encounter->physical_birth_weight ==='' 
				&& $encounter->physical_height === ''
				&& $encounter->physical_weight === '' 
				&& $encounter->blood_pressure_sys == 0 
				&& $encounter->blood_pressure_dia == 0 ) ? 1 : 0;
			
			//#hide_eye
			$encounter->hide_eye = ( $encounter->eye_withglasses_right ==='' 
				&& $encounter->eye_withglasses_left === ''
				&& $encounter->eye_withglasses_both === '' 
				&& $encounter->eye_withoutglasses_right ==='' 
				&& $encounter->eye_withoutglasses_left === ''
				&& $encounter->eye_withoutglasses_both === '') ? 1 : 0;
			//#hide_audio
			$encounter->hide_audio = ( $encounter->audio_left_1000 == 0 
				&& $encounter->audio_left_2000 == 0
				&& $encounter->audio_left_3000 == 0
				&& $encounter->audio_left_4000 == 0 
				&& $encounter->audio_right_1000 == 0
				&& $encounter->audio_right_2000 == 0
				&& $encounter->audio_right_3000 == 0
				&& $encounter->audio_right_4000 == 0) ? 1 : 0;

			//#hide_urinalysis
			$encounter->hide_urinalysis = ( $encounter->urinalysis_color ==='' 
				&& $encounter->urinalysis_specific_gravity === ''
				&& $encounter->urinalysis_ph === ''
				&& $encounter->urinalysis_protein === ''
				&& $encounter->urinalysis_glucose === ''
				&& $encounter->urinalysis_ketones === ''
				&& $encounter->urinalysis_bilirubim === ''
				&& $encounter->urinalysis_leuktocytes === ''
				&& $encounter->urinalysis_nitrite === ''
				&& $encounter->urinalysis_human_chorionic_gonadotropin === '' ) ? 1 : 0;
			//#hide_job
			$encounter->hide_job = ( $encounter->condition_employment==='' 
				&& $encounter->condition_autoaccident===''
				&& $encounter->condition_state===''
				&& $encounter->condition_other_accident==='' ) ? 1 : 0;

			
			if((int)$encounter->status === 2)
			{
				$user = $this->db
					->select('digital_signature')
					->where(['id' => $encounter->user_id ])
					->get('user')->row();
				
				$encounter->user_signed = $user->digital_signature;
					
				$signedAt = explode(" ", $encounter->signed_at);
				
				$signedAtFormat = new \DateTime($encounter->signed_at);
	    		
			    if($signedAt[1]==="00:00:00")
			    {
			    	$encounter->signed_at_format = $signedAtFormat->format('m/d/Y');
			    }
			    else
			    {
			    	$encounter->signed_at_format = $signedAtFormat->format('m/d/Y H:i A');
			    }
			}

			return $encounter;
		}
		else
		{
			return null;
		}	
	}

	function get_info_by_patient( $patient_id , $from = '', $to = '')
	{
		$this->db
			->select('id')
			->from('encounter')
			->where([
				'patient_id' => $patient_id,
				'status' => 2
			]);
		
		if( $from!='')
		{
			$this->db->where(["DATE_FORMAT(encounter.create_at,'%Y%m%d')>= " => $from]);
		}
		if( $to!='')
		{
			$this->db->where(["DATE_FORMAT(encounter.create_at,'%Y%m%d')<=" => $to ]);
		}

		$encounters = [];
		foreach ($this->db->get()->result() as  $encounter) {
			$encounters[] = $this->get_info( $encounter->id );
		}
		return $encounters;
	}

	function get_all_info( $patient_id, $where = null )
	{
		if( !is_null($where) )
		{	
			$this->db->where( $where );
		}	
		
		$this->db->select( $this->fields )
			->from('encounter')
			->where([ 'encounter.patient_id' => $patient_id])
			->order_by('encounter.id', 'desc');
		;
		
		return $this->db->get()->result();

	}
	
	function set_activity( $encounter_id, $comments )
	{
		$currentDate = date("Y-m-d H:i:s");

		$param = [
			'encounter_id' => $encounter_id,
			'user_id' => $this->_current_user->id,
			'comments' => $comments,
			'date_create' => $currentDate,
			'date_last_update' => $currentDate
		];

		$this->db->insert( 'encounter_activity', $param );
	}
	
	function get_activity( $encounter_id )
	{	
		
		$fields = [
			'encounter_activity.id',
			'encounter_activity.comments',
			"DATE_FORMAT(encounter_activity.date_create,'%m/%d/%Y') as date",
			"DATE_FORMAT(encounter_activity.date_create,'%h:%i %p') as time",
			'user.nick_name',
			"concat(user.names,' ',user.last_name) as user",
		];
		
		$this->db
			->select( $fields )
			->from('encounter_activity')
			->join('user', 'user.id=encounter_activity.user_id','inner')
			->where(['encounter_id' => $encounter_id])
		;

		$this->db->order_by('date_create DESC');
		
		return $this->db->get()->result();
		
		return [];
	}

	function validate_vitals( $form_validation )
	{
		$form_validation
			->set_rules('chief_complaint','Motivo de consulta','required|trim|xss_clean')
			->set_rules('current_medications','Medicación actual','trim|xss_clean')
			//HEART
			->set_rules('heart_pulse','Pulso','trim|max_length[25]')
			->set_rules('heart_respiratory','Frecuencia respiratoria','trim|max_length[25]')
			->set_rules('heart_temperature','Temperatura','numeric')
			->set_rules('heart_hemoglobin','Hemoglobina','numeric')
			->set_rules('heart_hematocrit','Hematocrito','numeric')
			->set_rules('heart_head_circ','Circunferencia de la cabeza','numeric')
			//->set_rules('heart_last_menstrual_period','Ultima Mestruación','trim|max_length[250]')
			//PHYSICAL
			->set_rules('physical_birth_weight','Peso al nacer','numeric')
			->set_rules('physical_weight','Peso','numeric')
			->set_rules('physical_height','Altura','numeric')
			->set_rules('blood_pressure_sys','Presión arterial sistólica','trim|xss_clean|numeric')
			->set_rules('blood_pressure_dia','Presión arterial diastólica','trim|xss_clean|numeric')
			//EYES
			->set_rules('eye_withglasses_left','Ojo izquierdo','trim|xss_clean|max_length[175]')
			->set_rules('eye_withglasses_right','Ojo derecho','trim|xss_clean|max_length[175]')
			->set_rules('eye_withglasses_both','Ambos ojos','trim|xss_clean|max_length[175]')
			->set_rules('eye_withoutglasses_left','Ojo izquierdo  sin lentes','trim|xss_clean|max_length[175]')
			->set_rules('eye_withoutglasses_right','Ojo derecho sin lentes','trim|xss_clean|max_length[175]')
			->set_rules('eye_withoutglasses_both','Ambos ojos sin lentes','trim|xss_clean|max_length[175]')
			->set_rules('eye_prescription_glasses','¿El paciente usa lentes recetadas?','in_list["",Yes,No]')
			->set_rules('eye_worn_during_exam','¿Se usaron lentes durante el examen?','in_list["",Yes,No]')
			//URINAILYSIS
			->set_rules('urinalysis_color','Color','max_length[75]')
			->set_rules('urinalysis_specific_gravity','Densidad','numeric')
			->set_rules('urinalysis_ph','PH','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_protein','Proteina','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_glucose','Glucosa','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_ketones','Cetonas','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_bilirubim','Bilirrubina','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_blood','Sangre','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_leuktocytes','Leucocitos','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_nitrite','Nitritos','xss_clean|trim|max_length[250]')
			->set_rules('urinalysis_human_chorionic_gonadotropin','HCG','in_list["",Positive,Negative]')
			//CONDITION
			->set_rules('condition_employment','Empleo','in_list["",Yes,No]')
			->set_rules('condition_autoaccident','Accidente automovilistico','in_list["",Yes,No]')
			->set_rules('condition_state','Estado','max_length[75]')
			->set_rules('condition_other_accident','Otro accidente','in_list["",Yes,No]')
		;

		$has_insurance = isset($_POST['has_insurance']) ? (int)$_POST['has_insurance'] : 0;
		
		if( $has_insurance )
		{	
			$form_validation
				->set_rules('insurance_radio','Seguro','required|trim|xss_clean')
			;
		
			$insurance = isset($_POST['insurance_radio']) ? $_POST['insurance_radio'] : ''; 
			$I = explode('|', $insurance);
			if(!isset($I[0]) || $I[0] === '')
			{
				return "Debe ";
			}
			else if(!isset($I[1]) || $I[1] === '')
			{
				return "Insurance name can't be null";
			}
		}

		if( $form_validation->run() === FALSE )
		{	
			return $form_validation->error_string();
		}
		else
		{	
			return FALSE;
		}
	}


	function save_data( $input,	$encounter_id = 0 )
	{

		$last_encounter_id = ( $encounter_id == 0 ) ? $this->get_last_encounter_id( $this->data['patient_id'] ) : 0;
		
		//BASIC
		$this->chief_complaint = $input->post('chief_complaint');
		if( $input->post('insurance_radio') && $input->post('has_insurance') )
		{	
			$insurance = explode('|',$input->post('insurance_radio'));
			$this->insurance_title  = $insurance[0];
			$this->insurance_number = $insurance[1];
		}
		else
		{
			$this->insurance_title  = '';
			$this->insurance_number = '';
		}
		$this->current_medications = $input->post('current_medications');
		$this->has_insurance       = (int)$input->post('has_insurance');
		
		//HEART
		$this->heart_pulse                 = $input->post('heart_pulse');
		$this->heart_respiratory           = $input->post('heart_respiratory');
		$this->heart_temperature           = $input->post('heart_temperature');
		$this->heart_hemoglobin            = $input->post('heart_hemoglobin');
		$this->heart_hematocrit            = $input->post('heart_hematocrit');
		$this->heart_head_circ             = $input->post('heart_head_circ');
		$this->heart_last_menstrual_period = $input->post('heart_last_menstrual_period');	
		
		//PHYSICAL
		$this->physical_birth_weight = $input->post('physical_birth_weight');
		$this->physical_weight       = $input->post('physical_weight');
		$this->physical_height       = $input->post('physical_height');
		
		//Blood Pressure
		$this->blood_pressure_sys = is_null($input->post('blood_pressure_sys')) ? 0 : $input->post('blood_pressure_sys');
		$this->blood_pressure_dia = is_null($input->post('blood_pressure_dia')) ? 0 : $input->post('blood_pressure_dia');
		
		//EYES
		$this->eye_withglasses_left     = $input->post('eye_withglasses_left');
		$this->eye_withglasses_right    = $input->post('eye_withglasses_right');
		$this->eye_withglasses_both     = $input->post('eye_withglasses_both');
		$this->eye_prescription_glasses = $input->post('eye_prescription_glasses');
		$this->eye_worn_during_exam     = $input->post('eye_worn_during_exam');
		$this->eye_questions 			= 1;
		/*
		$this->eye_withoutglasses_left  =  $input->post('eye_withoutglasses_left');
		$this->eye_withoutglasses_right =  $input->post('eye_withoutglasses_right');
		$this->eye_withoutglasses_both  =  $input->post('eye_withoutglasses_both');
		*/
		//AUDIO
		$this->audio_left_1000  =  $input->post('audio_left_1000');
		$this->audio_left_2000  =  $input->post('audio_left_2000');
		$this->audio_left_3000  =  $input->post('audio_left_3000');
		$this->audio_left_4000  =  $input->post('audio_left_4000');
		$this->audio_right_1000 =  $input->post('audio_right_1000');
		$this->audio_right_2000 =  $input->post('audio_right_2000');
		$this->audio_right_3000 =  $input->post('audio_right_3000');
		$this->audio_right_4000 =  $input->post('audio_right_4000');
		

		//URINAILYSIS
		$this->urinalysis_color                        = $input->post('urinalysis_color');
		$this->urinalysis_specific_gravity             = $input->post('urinalysis_specific_gravity');
		$this->urinalysis_ph                           = $input->post('urinalysis_ph');
		$this->urinalysis_protein                      = $input->post('urinalysis_protein');
		$this->urinalysis_glucose                      = $input->post('urinalysis_glucose');
		$this->urinalysis_ketones                      = $input->post('urinalysis_ketones');
		$this->urinalysis_bilirubim                    = $input->post('urinalysis_bilirubim');
		$this->urinalysis_blood                        = $input->post('urinalysis_blood');
		$this->urinalysis_leuktocytes                  = $input->post('urinalysis_leuktocytes');
		$this->urinalysis_nitrite                      = $input->post('urinalysis_nitrite');
		$this->urinalysis_human_chorionic_gonadotropin = $input->post('urinalysis_human_chorionic_gonadotropin');

		//CONDITION
		$this->condition_employment     = $input->post('condition_employment');
		$this->condition_autoaccident   = $input->post('condition_autoaccident');
		$this->condition_state          = $input->post('condition_state');
		$this->condition_other_accident = $input->post('condition_other_accident');

		$encounter_new_id = $this->save( $encounter_id );

		
		//IF NEW ENCOUNTER
		if( $last_encounter_id > 0 )
		{
			//#CHRONIC DIAGNOSIS
			$this->db->distinct()
				->select('comment')
				->from('encounter_diagnosis')
				->where([ 
					'encounter_id' => $last_encounter_id,
					'chronic' => 1
				]);
			
			$diagnosis = $this->db->get()->result();
			
			foreach ($diagnosis as $diag) {
				
				$param = [
					'encounter_id' => $encounter_new_id,
					'patient_id' => $this->data['patient_id'],
					'chronic' => 1,
					'comment' => $diag->comment
				];
				
				$this->db->insert( 'encounter_diagnosis', $param );
			}
			
			//#CHRONIC MEDICATION
			$this->db
				->from('encounter_medication')
				->where([ 
					'encounter_id' => $last_encounter_id,
					'chronic' => 'Yes'
				]);
			
			$medications = $this->db->get()->result();
			
			foreach ($medications as $medication) {
				
				$param = [
					'encounter_id' => $encounter_new_id,
					'patient_id' => $this->data['patient_id'],
					'chronic' => 'Yes',
					'title' => $medication->title,
					'dose' => '',
					'amount' => $medication->amount,
					'directions' => $medication->directions,
					'refill' => $medication->refill
				];
				
				$this->db->insert( 'encounter_medication', $param );
			}
		}

		return $encounter_new_id;
	}
	
	function get_checked_out( $ID )
	{
		$this->table = 'checked_out';
		return $this->get($ID);
	}

	function get_last_encounter_id( $patient_id )
	{
		$last_encounter = $this->db->select('MAX(id) as id')
			->where([
				"patient_id" => $patient_id
			])
			->get('encounter')
			->row();

		return ($last_encounter) ? $last_encounter->id : 0;
	}
		
	function is_open( $Controller, $ID , $jsonError = FALSE )
	{
		if(!( $encounter = $Controller->Encounter_DB->get($ID) ) )
		{
			show_error('Encounter not found', 404);
		}

		$currentDate = new DateTime();
		$currentDate->format('Y-m-d H:i:s');
		$this->db->select([
				'id',
				'user_id',
				'date_create',
				'date_last_update',
				'comments',
			])->from('encounter_activity')
			->where([
				'encounter_id' => $ID ,
				'comments!=' => 'encounter_create'
			])
			->order_by('id desc')
			->limit(1);

		$activity = $this->db->get()->row();
		
		if(!$activity ) 
		{	
			$this->db->insert('encounter_activity',[
				'user_id' =>  $Controller->current_user->id,
				'encounter_id' => $ID,
				'date_create' => $currentDate->format('Y-m-d H:i:s'),
				'date_last_update' => $currentDate->format('Y-m-d H:i:s'),
				'comments' => 'encounter_open'
			]);

			return $encounter;
		}
		//check each 10 seconds
		$lastUpdate  = new DateTime($activity->date_last_update);
		$lastUpdate->add(new DateInterval('PT10S'));

		if ($lastUpdate < $currentDate) 
		{	
			$this->db->insert('encounter_activity',[
				'user_id' =>  $Controller->current_user->id,
				'encounter_id' => $ID,
				'date_create' => $currentDate->format('Y-m-d H:i:s'),
				'date_last_update' => $currentDate->format('Y-m-d H:i:s'),
				'comments' => 'encounter_open'
			]);

			return $encounter;
		}
		else if( $activity->user_id === $Controller->current_user->id )
		{	
			$this->db
				->where(['id' => $activity->id ])
				->update('encounter_activity',[
					'date_last_update' => $currentDate->format('Y-m-d H:i:s')
			]);
		}
		else if(!$jsonError)
		{
			$user = $Controller->User_DB->get($activity->user_id);
			$Controller->notify->error('Encounter is being used by and user <b>'.$user->nick_name.'</b>');
			redirect('/patient/chart/'.$encounter->patient_id );
		}
		else
		{
			$user = $Controller->User_DB->get($activity->user_id);
			$Controller->template->json([
				'status' => 0,
				'message' => 'Encounter is being used by user <b>'.$user->nick_name.'</b>'
			]);
		}


		return $encounter;
	}
	
	

	public function total_count( $where = null ) 
    {
        $this->_filter( $where );
        if(isset($where['diagnosis']) && $where['diagnosis']!='')
       	{
       		$this->db->select('COUNT(encounter.id)')
	        	->from('encounter')
	        	->where([ 'encounter.status' => 2 ]);

	        return count($this->db->get()->result());
       	}
       	else
       	{
       		$this->db->select('encounter.id')
	        	->from('encounter')
	        	->where([ 'encounter.status' => 2 ]);

	        return $this->db->count_all_results();	
       	}
       	
    }	
    
    public function get_data( $limit,  $start, $data = null ) 
    {
    	$fieldsSort = [
			'id'        => 'encounter.id',
			'date'      => 'DATE_FORMAT(encounter.signed_at,"%Y%m%d")',
			'insurance' => 'encounter.insurance_title'
    	];
		
		$basicFields = [
			'encounter.id',
			'encounter.chief_complaint',
			'encounter.insurance_title',
			'DATE_FORMAT(encounter.signed_at,"%m/%d/%Y") as date',
			'encounter.patient_id',
			'CONCAT(patient.name," ",patient.last_name) as patient'
		];

		$where = isset( $data['filters'] ) ?  $data['filters'] : null;
 		
		$this->_filter( $where ); 
		
        $this->db->select( $basicFields )
        	->from('encounter')
        	->join('patient','patient.id=encounter.patient_id','inner')
        	->where([ 'encounter.status' => 2 ]);

       	$this->db->limit(abs($limit),abs($start) );

        $sortName = isset( $data['sort']['name']) ? $data['sort']['name'] : '';
        $sortType = isset( $data['sort']['type']) ? $data['sort']['type'] : '';
        
        if( isset($fieldsSort[$sortName]) && in_array($sortType, ['asc','desc']) )
        {	
			$this->db->order_by($fieldsSort[$sortName].' '.$data['sort']['type']);
        }
        else
        {
        	$this->db->order_by('encounter.id');
        }

        
        
       	return $this->db->get()->result();    	
    	
    }

    private function _filter( $where = null )
    {
		
    	if(isset($where['diagnosis']) && $where['diagnosis']!='')
        {	
        	$this->db->select('min(encounter_diagnosis.id) as min_default_id');
            
            $this->db->join('encounter_diagnosis','encounter_diagnosis.encounter_id=encounter.id','inner')
            	->like('encounter_diagnosis.comment', $where['diagnosis'] );

           	$this->db->group_by('encounter.id');
        }

        if(isset($where['id']) && $where['id']!='')
        {	
            $this->db->where( [ 'encounter.id' => $where['id'] ] );
        }
        if(isset($where['insurance']) && $where['insurance']!='')
        {
          	$this->db->like('encounter.insurance_title', $where['insurance'] );
        }
        if(isset($where['chief_complaint']) && $where['chief_complaint']!='')
        {
          	$this->db->like('encounter.chief_complaint', $where['chief_complaint'] );
        }
        
        if(isset($where['date']) && $where['date']!='')
        {	
          	$this->db->where(['DATE_FORMAT(encounter.create_at,"%m-%Y")' => $where['date'] ] );
        }
    }
    
    function get_info_signature( $id )
    {
    	
    	$this->db->select([
    			'encounter.user_id',
    			'encounter.user_signature',
    			'user.medic_npi as user_npi'
    		])
    		->from('encounter')
    		->join('user','user.id=encounter.user_id','inner')
				->where([
    			'encounter.status' => 2,
    			'encounter.id' => $id
    		]);
				
		if(!$enc = $this->db->get()->row() )
		{
			$enc = new StdClass;
			$enc->user_id        = '';
			$enc->user_signature = '';
			$enc->user_npi       = '';
		}

		return $enc;
    }
    
}