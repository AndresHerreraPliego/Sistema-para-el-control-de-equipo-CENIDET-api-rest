<?php
include_once 'models/create.php';
include_once 'models/read.php';
include_once 'models/update.php';
include_once 'models/delete.php';
include_once 'mail.php';


$create= new create();
$read= new read();
$update= new update();
$delete= new delete();

function router($url, $body){
    global $create;
    global $read;
    global $update;
    global $delete;

    

    switch($url){
        case 'login':
            $read->login($body);
        break;

        case 'admin':
            $read->admin();
        break;

        case 'usuario':
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $read->usuario($id);
            }  
        break;

        case 'departamento':
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $read->departamento($id);
            }    
        break;

        case 'departamentos':
            $read->departamentos();
        break;

        case 'departamento/create':
            $create->departamento($body);
        break;

        case 'departamento/update':
            $update->departamento($body);
        break;

        case 'departamento/delete':
            $delete->departamento($body);
        break;

        case 'personales':
            $read->personales();
        break;

        case 'personal':
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $read->personal($id);
            }
        break;

        case 'personal/create':
            $create->personal($body);
        break;

        case 'usuarios/create':
            $create->usuarios($body);
        break;

        case 'personal/update':
            $update->personal($body);
        break;

        case 'personal/delete':
            $delete->personal($body);
        break;

        case 'equipos':
            $read->equipos();
        break;

        case 'equipo':
            if(isset($_GET['param'])){
                $no_serie = (int) $_GET['param'];
                $read->equipo($no_serie);
            }
           
        break;

        case 'equipos/id_salida':
            if(isset($_GET['param'])){
                $id_salida = (int) $_GET['param'];
                $read->equiposidsalida($id_salida);
            }
           
        break;

        case 'equipos/no_serie':
            if(isset($_GET['param'])){
                $no_serie = (int) $_GET['param'];
                $read->equiposnoserie($no_serie);
            }
           
        break;

        case 'equipo/create':
            $create->equipo($body);
        break;

        case 'equipos/create':
            $create->equipos($body);
        break;

        case 'equipo/update':
            $update->equipo($body);
        break;

        case 'equipo/delete':
            $delete->personal($body);
        break;

        case 'entradas':
            $read->entradas();
        break;

        case 'entrada':
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $read->entrada($id);
            }
        break;

        case 'entrada/create':
            $create->entrada($body);
        break;

        case 'salidas':
            $read->salidas();
        break;

        case 'salidas/activas':
            $read->salidasactivas();
        break;

        case 'salidas/recolectadas':
            $read->salidasrecolectadas();
        break;

        case 'salida':
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $read->salida($id);
            }
        break;

        case 'salidas/create':
            $create->salidas($body);
        break;

        case 'salida/create':
            $create->salida($body);
        break;

        case 'anuncios':
            $read->anuncios();
        break;

        case 'anuncio/create':
            $create->anuncio($body);
        break;

        case 'anuncio/update':
            $update->anuncio($body);
        break;

        case 'anuncio/delete':
            
            if(isset($_GET['param'])) {
                $id = (int) $_GET['param'];
                $delete->anuncio($id);
            }
             
        break;

        case 'buscar/equipo':  
            $read->buscarequipo($body);
        break;

        case 'sendmail':
            sendmail($body);
        break;

        case 'recoverpass':
            $response = $read->recoverpass($body);
            if($response){
                $data = array(
                    "to" => $response['email'] ,
                    "subject" => "Recuperación de contraseña" ,
                    "message" => "Estimado usuario, tu contraseña es: ".$response['contraseña']
                );
                sendmail( $data );
            }else{
                echo "false";
            }


        break;

        case 'image/user':  
            $update->personalfoto($body);
        break;
        
        case 'prestamo/create':  
            $create->prestamo($body);
        break;




    }
}


?>