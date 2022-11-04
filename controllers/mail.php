<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

function sendmail($data){

    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();   
        $mail->SMTPDebug 	= 0;			//Enable SMTP debugging 0 = off (for production use) 1 = client messages 2 = client and server messages
        $mail->Debugoutput 	= 'html';		//Ask for HTML-friendly debug output
        $mail->Host 		= 'smtp-relay.sendinblue.com';	//Set the hostname of the mail server
        $mail->Port 		= 587;
        $mail->SMTPSecure   = 'tls';		//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPAuth 	= true;			//Whether to use SMTP authentication
        $mail->Username 	= "cenidet.asp@gmail.com";
        $mail->Password 	= "H2JmYMFRG5hVawpU";
        $mail->CharSet = 'UTF-8';

        
        //Recipients
        $mail->setFrom('scese.cenidet@gmail.com', 'Sistema de Bienes');
        $mail->addAddress( $data['to'] , 'Usuario');    
        //Content
        $mail->isHTML(true);                                 
        $mail->Subject = $data['subject'] ;
        $mail->Body    = $data['message'];
    
        $mail->send();
        echo "true";
    } catch (Exception $e) {
        echo "false";
    }
    
}

?>