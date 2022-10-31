<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: user, password, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
date_default_timezone_set('America/Monterrey');

$headers = getallheaders();
$email = array_key_exists('user', $headers) ? $headers['user'] : '';
$pass = array_key_exists('password', $headers) ? $headers['password'] : '';

$data = file_get_contents("config/access.json");
$user = json_decode($data, true)["user_api"];

if(isset($_GET['url'])) {

    $url= $_GET['url'];
    if(substr($url,0,3) =='img'){
        header("Content-type: image/*");
        readfile($_GET['url']);
        die;
    }
    
}

if($email ==  $user['email'] && $pass ==  $user['password']){

    include_once 'controllers/router.php';
    if(isset($_GET['url'])) 
    {
        $body = file_get_contents('php://input');
        router($_GET['url'],$body);
    }
  
} else{
    echo 'acceso denegado';
    die;
} 




?>