<?php

function sendmail($data){
    
    $to = $data['to'];
    $subject = $data['subject'];
    $message = $data['message'];
    $response = mail($to,$subject,$message);
    if($response){
        echo "true";
    }else{
        echo "false";
    }  
    
}

?>