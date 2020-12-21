<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include_once 'controllers/router.php';
date_default_timezone_set('America/Monterrey');


if(isset($_GET['url'])) 
{
    $body = file_get_contents('php://input');
    router($_GET['url'],$body);
}


?>