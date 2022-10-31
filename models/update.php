<?php
include_once 'config/db.php';

class update extends db{ 

    function departamento($body){
        $data=json_decode($body, true);
        $db=$this->connect();

        foreach ($data as &$item) {
            $id=(int) $item['id'];
            $nombre=$item['nombre'];
            $sql = "UPDATE departamento SET nombre='$nombre' WHERE id=$id";
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
            $sexo=$item['sexo'];
            $sql = "UPDATE personal SET nombre='$nombre',apellido_paterno='$apellido_paterno',apellido_materno='$apellido_materno',
            edad=$edad, telefono='$telefono',email='$email',contraseña='$contraseña',rol='$rol',sexo='$sexo' WHERE id=$id";
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
            $id=(int) $item['id'];
            $nombre=$item['nombre'];
            $marca=$item['marca'];
            $no_serie=(int) $item['no_serie'];
            $modelo= $item['modelo'];
            $no_inventario=(int) $item['no_inventario'];
            $disponibilidad=$item['disponibilidad'];
            $foto=$item['foto'];
            $etiquetas=$item['etiquetas'];
            $sql = "UPDATE equipo SET nombre='$nombre',marca='$marca',no_serie=$no_serie,
            modelo='$modelo', no_inventario=$no_inventario,disponibilidad='$disponibilidad',foto='$foto',etiquetas='$etiquetas' WHERE id=$id";
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
        $id=(int) $data['id'];
        $mensaje=$data['mensaje'];
        $inicia=$data['inicia'];
        $expira=$data['expira'];
        $destinatario=$data['destinatario'];
        $sql = "UPDATE anuncios set mensaje='$mensaje',inicia='$inicia',expira='$expira',destinatario='$destinatario' WHERE id=$id";
        $result=$db->query($sql);
        if(!$result){
                echo "false";
                return;
        }
        $db->close(); 
        echo "true";

    }

    function personalfoto($body){
        
        if(isset($_FILES['file'])){
            
            $db=$this->connect();

            $name=$_FILES['file']['name'];
            $id=(int) $_POST['id'];
            copy($_FILES['file']['tmp_name'], "img/$name");  
     
            $url =  "http://localhost/cenidet/img/$name";

            $sql = "UPDATE personal SET foto='$url' WHERE id=$id";
            $result=$db->query($sql);
            if(!$result){
                echo "false";
                return;
            }
            $db->close();
            echo "true";
               
        }

    }


}

?>