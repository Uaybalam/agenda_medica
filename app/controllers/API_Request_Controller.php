<?php

Class API_Request_Controller extends CI_Controller{

    function __construct()
	{
		parent::__construct();
		$this->load->model([
			'Patient_Model' => 'Patient_DB', 
            'Appointment_Model' => 'Appointment_DB',
		]);
	}

    /**
	 * @route:{post}setAppointment
	 */
	function setAppointment()
	{
        $token = explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1];
        $tParts = explode(".",$token);
        $header = json_decode($this->_decode($tParts[0]));
        $payload = json_decode($this->_decode($tParts[1]));

        $this->db->select()
			->from('user')
			->where(['id'=> $payload->uid]);

		$user = $this->db->get()->row();

        print_r($_POST); 
        
        if($this->_gen_jwt($user->instance_id,$header,$payload) === $token)
        {
            $where  = [];
            $detail = json_decode(str_replace("\\","",$_POST['detail'])); 
            $names  = explode(" ",$detail->name);

            $where['name'] = trim(strtoupper($names[0]));
            $where['last_name'] = trim(strtoupper($detail->lastname));
            $where['phone'] = $detail->phone; 

            if(count($names) > 1){
                $where['middle_name'] =  trim( strtoupper($names[1]) );
            } 

            $this->db->select('id, create_at,phone')
                     ->from('patient')
                     ->where($where);

            $patients = $this->db->get()->result_array();
             
            if($patients)
            {
                $patientId = $patients[0]['id'];
            }
            else
            {
                $data_patient = $where;
                $data_patient['instance_id'] = $user->instance_id;
                $data_patient['email'] =  $detail->email;
                $data_patient['date_of_birth'] = date('m/d/Y', strtotime(date("Y-m-d")." -18 year"));
                $patientId = $this->Patient_DB->create_basic_patient( $data_patient );
            }

            $notes = "Cita creada en dirmedal ";

            if($detail->comments)
            {
                $notes.= "\nComentario: ".$detail->comments;
            }

            if($detail->subject)
            {
                $notes.= "\nAsunto: ".$detail->subject;
            }

            if($_POST['service'])
            {
                $notes.= "\nServicio: ".$_POST['service'];
            }

            if($_POST['virtual_meet'] != "false")
            {
                $notes.= "\n\n\nEsta cita tiene una cita virtual, para entrar a esta cita se debe entrar en https://dirmedal.com/videoconsulta/?room=".$_POST['appId'];
                $notes.= "\nEMail:".$detail->email;
                $notes.= "\nContraseña:".$_POST['password'];
            }

            $this->Appointment_DB->code             = rand(100000, 999999);
			$this->Appointment_DB->insurance_type   = ""; 
			$this->Appointment_DB->visit_type       = 0; 
			$this->Appointment_DB->type_appointment = 0; 
			$this->Appointment_DB->date_appointment = $_POST['date']." ".$_POST['time'].":00";
			$this->Appointment_DB->patient_id       = $patientId;
			$this->Appointment_DB->notes            = $notes;
			$this->Appointment_DB->create_user_by   = $user->id;
            $this->Appointment_DB->status           = 1;
			
			$appointment_id = $this->Appointment_DB->save();

            $this->template->json( [
                'message' => "Token Correct",
                'status' => 1
            ] );
        }
        else
        {
            $this->template->json( [
                'message' => "Token no Correct",
                'status' => 0
            ] );
        } 
    }

    /**
	 * @route:{post}getDataDirmedal
	 */
	function getDataDirmedal()
	{
        echo "getDataDirmedal";
        exit;
    }

    private function _gen_jwt($signing_key,$header,$payload):String
    { 
        $header = $this->_encode(json_encode($header));
        $payload = $this->_encode(json_encode($payload));
        $signature = $this->_encode(hash_hmac('sha512', "$header.$payload", $signing_key, true));
        $jwt = "$header.$payload.$signature";
        
        return $jwt;    
    }
    
    private function _encode($text):String
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    private function _decode($text):String
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_decode($text));
    }
}