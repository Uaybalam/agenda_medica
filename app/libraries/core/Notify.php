<?php
class Notify{

	/**
     *  @var $CI: instance at framework
     */
    protected $CI;
    
    /**
     *  @var $session_error: error message on session flash
     */
    protected $session_error       = 'msg_error';
    
    /**
     *  @var $session_success: success message on session flash
     */
    protected $session_success     = 'msg_success';
    
    public function __construct()
    {
    	$this->CI =& get_instance();
    }   

    public function get_messages()
    {	
    	
        $response_messages = '';
        if( $info = $this->CI->session->flashdata ( $this->session_error ) )
        {       
            $response_messages.='<input type="text" ' 
                .'style="display:none;"'
                .'name="flash-notify[]" data-type="error" '
                .'data-msg="'.$info['msg'].'" />';
        }
        
        if( $info = $this->CI->session->flashdata($this->session_success))
        {
            $response_messages.='<input type="text"'
                .'style="display:none;"'
                .'name="flash-notify[]" data-type="success" '
                .'data-msg="'.$info['msg'].'" />';
        }

        return $response_messages;
    }

	public function error( $message_error )
    {
        $msg = '';
        if(is_array($message_error)){
            foreach ( $message_error as $key => $value) 
            {
                $msg.= "<p>".$value."</p>";
            }
        }else{ 
            $msg = $message_error;          
        }   

        $this->CI->session->set_flashdata(  $this->session_error , ['msg' => $msg ] ); 
        return $this;
    }   

    public function success(  $message_success )
    {
        $this->CI->session->set_flashdata ( $this->session_success , ['msg' => $message_success] );
        return $this;
    }
}