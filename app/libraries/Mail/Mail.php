<?php
namespace Lib;


/**
 * 
 */
class Mail
{
	private $mail         = null;
	
	public $errorMessage = '';

	public function __construct( $debug = 0){

		require __DIR__ . '/PHPMailer/class.phpmailer.php';
		require __DIR__ . '/PHPMailer/PHPMailerAutoload.php';

        $this->mail = new \PHPMailer;
        //
        $this->mail->isSMTP();    
        
        $config = include_once __DIR__ . '/config.php';

        $this->mail->SMTPDebug  = $debug;
        
        $this->mail->Host       = $config['smtp_host'];// $config['smtp_host'];
        $this->mail->Port       = $config['smtp_port'];//$config['smtp_port'];
        $this->mail->Username   = $config['smtp_user'];//$config['smtp_user'];
        $this->mail->Password   = $config['smtp_pass'];//$config['smtp_pass'];
        
        $this->mail->CharSet    = 'UTF-8';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->SMTPAuth   = true;
        
        $parseUrl = parse_url(site_url(''));
        
        $this->mail->setFrom($config['smtp_user'], $parseUrl['host']);
	}

	public function setSubject( $subject )
	{
		$this->mail->Subject = $subject;
	}

	public function setFrom( $email , $name  = '' )
	{
        $this->mail->setFrom($email ,  $name );
    }

	public function addAddress( $email  )
	{
		$this->mail->addAddress( $email );
	}

    public function send( $content , $type = 'HTML')
    {
        
        if($type ==='HTML')
        {
        	$this->mail->isHTML(true);
        	$this->mail->msgHTML( $content );
        }
        else
        {
        	$this->mail->isHTML(false); 
        	$this->mail->Body = $content;
        }

        try {
    	    $send = $this->mail->send();
            
            $this->mail->clearAddresses();
            if(!$send)
            {
				$this->errorMessage = $this->mail->ErrorInfo;
            }
            return $send;

        }catch(\phpmailerException $e){
           
            $this->errorMessage = $e->errorMessage();
            
            return FALSE;
        }
        catch(\Exception $e)
        {
        	return FALSE;	
        }
    }
}