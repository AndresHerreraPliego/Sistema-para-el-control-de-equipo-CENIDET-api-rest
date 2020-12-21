<?php
include_once 'config/db.php';

class create extends db{ 


    function salidas($body){
        $data=json_decode($body, true);
        $db=$this->connect();

        $equipos = $data['equipos'];
        $id_salida = $data['id_salida'];

        foreach ($equipos as &$item) {

            $no_serie=(int) $item['no_serie'];

            $response=$db->query("SELECT id FROM equipo WHERE no_serie=$no_serie");
            $id_equipo=$response->fetch_assoc()['id'];

            $sql = "INSERT INTO salida_equipo VALUES ($id_salida,$id_equipo)";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
        }
        $db->close(); 
        echo "true";
    }

    function departamento($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        foreach ($data as &$item) {
            $nombre=$item['nombre'];
            $sql = "INSERT INTO departamento (nombre) VALUES ('$nombre')";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
        }
        $db->close(); 
        echo "true";
    }

    function personal($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        foreach ($data as &$item) {
            $id=(int) $item['id'];
            $nombre=$item['nombre'];
            $apellido_paterno=$item['apellido_paterno'];
            $apellido_materno=$item['apellido_materno'];
            $edad=(int) $item['edad'];
            $telefono=$item['telefono'];
            $email=$item['email'];
            $contraseña=$item['contraseña'];
            $rol=$item['rol'];
            $sql = "INSERT INTO personal VALUES 
            ($id,'$nombre','$apellido_paterno','$apellido_materno',$edad,'$telefono','$email','$contraseña','$rol')";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
        }
        $db->close(); 
        echo "true";
    }

    function equipo($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        foreach ($data as &$item) {
            $nombre=$item['nombre'];
            $marca=$item['marca'];
            $no_serie=(int) $item['no_serie'];
            $modelo=$item['modelo'];
            $no_inventario=(int)$item['no_inventario'];
            $disponibilidad=$item['disponibilidad'];
            $foto=$item['foto'];
            $etiquetas=$item['etiquetas'];
            $id_personal=(int) $item['id_personal'];
            $id_tipo=(int) $item['id_tipo'];
            $sql = "INSERT INTO equipo (nombre,marca,no_serie,modelo,no_inventario,disponibilidad,foto,etiquetas,id_personal,id_tipo) VALUES 
            ('$nombre','$marca',$no_serie,'$modelo',$no_inventario,'$disponibilidad','$foto','$etiquetas')";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
        }
        $db->close(); 
        echo "true";
        

    }

    function entrada($body){


        $data=json_decode($body, true);
        $db=$this->connect();
        $id_salida=(int) $data['id_salida'];
        $equipos= $data['equipos'];
        $hora = date("H:i:s");
        $fecha = date("Y-m-d");


        foreach ($equipos as &$item) {

            $no_serie=(int) $item['no_serie'];

            $response=$db->query("SELECT id FROM equipo WHERE no_serie=$no_serie");
            $id_equipo=$response->fetch_assoc()['id'];
            
            $sql = "INSERT INTO entrada (id_salida,fecha,hora,id_equipo) VALUES ($id_salida,'$fecha','$hora',$id_equipo)";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
        }
        $db->close(); 
        echo "true";

    }

    function anuncio($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        $mensaje=$data['mensaje'];
        $inicia=$data['inicia'];
        $expira=$data['expira'];
        $destinatario=$data['destinatario'];
        $sql = "INSERT INTO anuncios (mensaje,inicia,expira,destinatario) VALUES ('$mensaje','$inicia','$expira','$destinatario')";
        $result=$db->query($sql);
        if(!$result){
                echo "false";
                return;
        }
        $db->close(); 
        echo "true";
    }

}

?>