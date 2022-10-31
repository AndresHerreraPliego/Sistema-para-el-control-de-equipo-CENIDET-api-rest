<?php
include_once 'config/db.php';
include_once 'controllers/mail.php';


class create extends db{ 


    function salidas($body) {
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

    function salida($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        
        $id = (int) $data['id'];
        $departamento = (int) $data['departamento'];

           
        $sql = "INSERT INTO salida (fecha,hora,id_departamento,id_personal,estatus) 
        VALUES (CURRENT_DATE(),CURRENT_TIME(),$departamento,$id,'pendiente por extraer')";
        $result=$db->query($sql);
        if($result){
            $lastid = mysqli_insert_id($db); 
            $result=$db->query("SELECT salida.id,salida.fecha,salida.hora,salida.estatus,departamento.nombre AS departamento FROM salida 
            INNER JOIN departamento ON salida.id_departamento = departamento.id  WHERE salida.id = $lastid");
            $salida=$result->fetch_assoc();
            echo json_encode($salida);
        }
        $db->close(); 
    }

    function prestamo($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        
        $id = (int) $data['id'];
        $departamento = (int) $data['departamento'];
        $nombre = $data['nombre'];
        $rol = $data['rol'];
        $email = $data['email'];

           
        $sql = "INSERT INTO salida (fecha,hora,id_departamento,id_personal,estatus) 
        VALUES (CURRENT_DATE(),CURRENT_TIME(),$departamento,$id,'activo')";
        $result=$db->query($sql);
        if($result){
            $lastid = mysqli_insert_id($db); 
            $result=$db->query("SELECT salida.id,salida.fecha,salida.hora,salida.estatus,departamento.nombre AS departamento FROM salida 
            INNER JOIN departamento ON salida.id_departamento = departamento.id  WHERE salida.id = $lastid");
            $salida=$result->fetch_assoc();

            $db->query("INSERT INTO salida_prestamo VALUES ($lastid,'$nombre','$rol')");
            echo json_encode($salida);
        }

        /*
        $config = array(
            "to" => $email ,
            "subject" => "Prestamo de equipo" ,
            "message" => "Hola $nombre, te han prestado equipo para poder usarlo debes presentar el código $lastid con vigilancia"
        );
        sendmail( $config );*/

        $db->close(); 

    }

    function departamento($body){
        $data=json_decode($body, true);
        $db=$this->connect();
      
        $nombre=$data['nombre'];
        $sql = "INSERT INTO departamento (nombre) VALUES ('$nombre')";
        $result=$db->query($sql);
        if(!$result){
                echo "false";
                return;
        }
        
        $db->close(); 
        echo "true";
    }

    function personal($body){

        $data=json_decode($body, true);
        $db=$this->connect();

        $id= $data['id'];
        $nombre=$data['nombre'];
        $apellido_paterno=$data['apellido_paterno'];
        $apellido_materno=$data['apellido_materno'];
        $edad=(int) $data['edad'];
        $telefono=$data['telefono'];
        $email=$data['email'];
        $contraseña=$data['contraseña'];
        $rol=$data['rol'];
        $sexo=$data['sexo'];
        $departamentos=$data['departamentos'];

        $sql = "INSERT INTO personal (nombre,apellido_paterno,apellido_materno,edad,matricula,telefono,email,contraseña,rol,sexo) VALUES 
        ('$nombre','$apellido_paterno','$apellido_materno',$edad,'$id','$telefono','$email','$contraseña','$rol','$sexo')";
        $result=$db->query($sql);
        if(!$result){
                echo "false";
                return;
        }
        $id = mysqli_insert_id($db); 

        foreach ($departamentos as $item) {

            $response=$db->query("SELECT id FROM departamento  WHERE nombre = '$item' ");
            $id_departamento=(int) $response->fetch_assoc()['id'];
            $db->query("INSERT INTO personal_departamento VALUES ($id,$id_departamento)");
        }

       
        $result=$db->query("SELECT * FROM personal WHERE id= $id");

        if ($result->num_rows > 0) {
            $user=$result->fetch_assoc();
            $id=$user['id'];

            $result=$db->query("SELECT departamento.nombre, departamento.id FROM departamento INNER JOIN personal_departamento ON departamento.id = personal_departamento.id_departamento WHERE personal_departamento.id_personal = $id");
            $departamentos = array();
            while($row = $result->fetch_assoc()) {
                array_push($departamentos,$row);
            }
            $user['departamentos'] = $departamentos;
            echo json_encode($user);
        }
/*
        $config = array(
            "to" => $email ,
            "subject" => "Creacion de cuenta" ,
            "message" => "Estimado usuario, tu cuenta para el sistema de control de entrada y salida de equipo se ha creado
            exitosamente"
        );
        sendmail( $config ); */



        $db->close(); 
    }

    function usuarios($body){
        $data=json_decode($body, true);
        $db=$this->connect();

        foreach ($data as $item) {
            $nombre= $item['nombre'];
            $apellido_paterno= $item['apellido_paterno'];
            $apellido_materno= $item['apellido_materno'];
            $edad= (int) $item['edad'];
            $telefono= $item['telefono'];
            $email= $item['email'];
            $rol= $item['rol'];
            $sexo= $item['sexo'];
            $matricula= $item['matricula'];

            $sql = "INSERT INTO personal (nombre,apellido_paterno,apellido_materno,edad,matricula,telefono,email,rol,sexo) VALUES 
            ('$nombre','$apellido_paterno','$apellido_materno',$edad,'$matricula','$telefono','$email','$rol','$sexo')";
            $result=$db->query($sql);
            if(!$result){
                    echo "false";
                    return;
            }
        }
        echo "true";

    }

    function equipo($body){
        $data = $_POST;
        $db=$this->connect();

        if(isset($_FILES['foto'])){
           $name=$_FILES['foto']['name'];
           copy($_FILES['foto']['tmp_name'], "img/$name"); 
           $url = "http://localhost/cenidet/img/$name";
           $data['foto']= $url; 
        }

        
        $nombre=$data['nombre'];
        $marca=$data['marca'];
        $no_serie= $data['no_serie'];
        $valor=(int) $data['valor'];
        $folio=(int) $data['folio'];
        $modelo=$data['modelo'];
        $no_inventario=$data['no_inventario'];
        $no_sep=(int) $data['no_sep'];
        $etiquetas=$data['etiquetas'];
        $foto=$data['foto'];
        $fecha_alta=$data['fecha_alta'];
        $doc_soporte=$data['doc_soporte'];
        $id_personal=(int) $data['id_personal'];
        $id_tipo=(int) $data['id_tipo'];


        $sql = "INSERT INTO equipo (nombre,marca,no_serie,valor,folio,modelo,no_inventario,no_sep,etiquetas,foto,fecha_alta,doc_soporte,id_personal,id_tipo) VALUES 
        ('$nombre','$marca','$no_serie',$valor,$folio,'$modelo','$no_inventario',$no_sep,'$etiquetas','$foto','$fecha_alta','$doc_soporte',$id_personal,$id_tipo)";
        
        $result=$db->query($sql);
        if(!$result){
                echo "false";
                return;
        }
        echo "true";
        $db->close();

    }

    function equipos($body){
        $data=json_decode($body, true);
        $db=$this->connect();

        foreach ($data as $item) {

            $nombre=$item['nombre'];
            $marca=$item['marca'];
            $no_serie= $item['no_serie'];
            $modelo=$item['modelo'];
            $no_inventario= $item['no_inventario'];
            $valor = (int) $item['valor'];
            $no_sep =(int) $item['no_sep'];
            $resguardante = $item['resguardante'];
            $folio = (int) $item['folio']; 

            $fecha_alta = $item['fecha_alta'];
            $doc_soporte =  $item['doc_soporte'];  

            $result=$db->query("SELECT * FROM personal");

            $id = -1;

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $completo = $row['nombre'] . " " . $row['apellido_paterno'] . " "  . $row['apellido_materno'];
                    if($completo == $resguardante){
                        $id  = (int) $row['id'];
                    }

                }
            }

            if($id != -1){
                $sql = "INSERT INTO equipo (nombre,marca,no_serie,valor,folio,modelo,no_inventario,no_sep,fecha_alta,doc_soporte,id_personal) VALUES 
                ('$nombre','$marca','$no_serie',$valor,$folio,'$modelo','$no_inventario',$no_sep,$fecha_alta,'$doc_soporte',$id)";
                $result=$db->query($sql);
                if(!$result){
                    echo "false";
                    return;
                }  
            }else{
                $sql = "INSERT INTO equipo (nombre,marca,no_serie,valor,folio,modelo,no_inventario,no_sep,fecha_alta,doc_soporte) VALUES 
                ('$nombre','$marca','$no_serie',$valor,$folio,'$modelo','$no_inventario',$no_sep,$fecha_alta,'$doc_soporte')";
                $result=$db->query($sql);
                if(!$result){
                        echo "false";
                        return;
                } 

            }

         
        }
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
        $tipo=$data['tipo'];

        $sql = "INSERT INTO anuncios (mensaje,inicia,expira,destinatario,tipo) VALUES ('$mensaje','$inicia','$expira','$destinatario','$tipo')";
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