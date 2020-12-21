<?php
include_once 'config/db.php';

class read extends db{

    function login($body){
        $data=json_decode($body, true);
        $db=$this->connect();
        $email=$data['email'];
        $password=$data['password'];
        $sql="SELECT * FROM personal WHERE email='$email' AND contraseña='$password'";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $user=$result->fetch_assoc();
            echo json_encode($user);
        }else {
            echo "false";
        }
    }

    function salidas(){
        $db=$this->connect();
        $sql="SELECT salida.id,salida.fecha,salida.hora,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
        personal ON salida.id_personal = personal.id";
        $result=$db->query($sql);
        $salidas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }

        $result=$db->query("SELECT salida_prestamo.id_salida, salida_prestamo.nombre_persona, salida_prestamo.rol FROM salida_prestamo INNER JOIN salida ON salida_prestamo.id_salida  = salida.id");
        $prestamos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($prestamos,$row);
            }
        }

        $json=array( "salidas" => $salidas, "prestamos" => $prestamos);
        echo json_encode($json);
    }

    function salidasactivas(){
        $db=$this->connect();
        $sql="SELECT salida.id,salida.fecha,salida.hora,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
        personal ON salida.id_personal = personal.id WHERE salida.estatus = 'activo'";
        $result=$db->query($sql);
        $salidas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }

        $result=$db->query("SELECT salida_prestamo.id_salida, salida_prestamo.nombre_persona, salida_prestamo.rol FROM salida_prestamo INNER JOIN salida ON salida_prestamo.id_salida  = salida.id");
        $prestamos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($prestamos,$row);
            }
        }

        $json=array( "salidas" => $salidas, "prestamos" => $prestamos);
        echo json_encode($json);
    }

    function salidasrecolectadas(){
        $db=$this->connect();
        $sql="SELECT salida.id,salida.fecha,salida.hora,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
        personal ON salida.id_personal = personal.id WHERE salida.estatus = 'recolectado'";
        $result=$db->query($sql);
        $salidas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }

        $result=$db->query("SELECT salida_prestamo.id_salida, salida_prestamo.nombre_persona, salida_prestamo.rol FROM salida_prestamo INNER JOIN salida ON salida_prestamo.id_salida  = salida.id");
        $prestamos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($prestamos,$row);
            }
        }

        $json=array( "salidas" => $salidas, "prestamos" => $prestamos);
        echo json_encode($json);
    }

    function salida($id){
        $db=$this->connect();
        $sql="SELECT * FROM salida where id=$id";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $json = $result->fetch_assoc();
            echo json_encode($json);
        }else {
            echo "0";
        }  
    }

    function equipos(){
        $db=$this->connect();
        $sql="SELECT equipo.id, equipo.nombre, equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.disponibilidad, equipo.foto, equipo.etiquetas, personal.id AS id_propietario ,personal.nombre AS propietario,
        tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id INNER JOIN tipo ON equipo.id_tipo = tipo.id";
        $result=$db->query($sql);
        $json=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($json,$row);
            }
            echo json_encode($json);
        }else {
            echo "0";
        }
    }

    function equiposidsalida($id_salida){
        $db=$this->connect();
        $sql="SELECT equipo.id, equipo.nombre, equipo.marca,equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.disponibilidad, equipo.foto, equipo.etiquetas, personal.id AS id_propietario ,
        personal.nombre AS propietario, tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id 
        INNER JOIN tipo ON equipo.id_tipo = tipo.id WHERE equipo.id 
        IN ( SELECT salida_equipo.id_equipo FROM salida_equipo WHERE salida_equipo.id_salida = $id_salida )";
        $result=$db->query($sql);
        $equipos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipos,$row);
            }
            $json=array( "id_salida" => $id_salida, "equipos" => $equipos);
            echo json_encode($json);
        }else {
            echo "0";
        }
    }

    //Pendiente
    function equiposnoserie($no_serie){
        $db=$this->connect();
        $sql="SELECT id_salida FROM salida_equipo WHERE id_equipo IN  (SELECT id FROM equipo WHERE no_serie = $no_serie ) ";
        $result=$db->query($sql);
        $id_salida=$result->fetch_assoc()['id_salida'];
        $sql="SELECT equipo.id, equipo.nombre,equipo.marca, equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.disponibilidad, equipo.foto, equipo.etiquetas,personal.id 
        AS id_propietario, personal.nombre AS propietario, tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id 
        INNER JOIN tipo ON equipo.id_tipo = tipo.id WHERE equipo.id in (SELECT id_equipo FROM salida_equipo WHERE id_salida = $id_salida)";
        $result=$db->query($sql);
        $equipos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipos,$row);
            }
        }

        $json=array( "id_salida" => $id_salida, "equipos" => $equipos);
        echo json_encode($json);
    }

    function equipo($no_serie){
        $db=$this->connect();
        $sql="SELECT equipo.id, equipo.nombre,equipo.marca, equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.disponibilidad, equipo.foto, equipo.etiquetas,personal.id AS id_propietario, personal.nombre AS propietario,
        tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id INNER JOIN tipo ON equipo.id_tipo = tipo.id WHERE equipo.no_serie=$no_serie";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $json = $result->fetch_assoc();
            echo json_encode($json);
        }else {
            echo "0";
        }
    }


    function departamentos(){
        $db=$this->connect();
        $sql="SELECT * FROM departamento";
        $result=$db->query($sql);
        $json=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($json,$row);
            }
            echo json_encode($json);
        }else {
            echo "0";
        }

    }

    function departamento($id){
        $db=$this->connect();
        $sql="SELECT * FROM departamento where id=$id";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $json = $result->fetch_assoc();
            echo json_encode($json);
        }else {
            echo "0";
        }
        
    }

    function personales(){
        $db=$this->connect();
        $sql="SELECT * FROM personal";
        $result=$db->query($sql);
        $json=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($json,$row);
            }
            echo json_encode($json);
        }else {
            echo "0";
        }
        
    }

    function personal($id){
        $db=$this->connect();
        $sql="SELECT * FROM personal where id=$id";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $json = $result->fetch_assoc();
            echo json_encode($json);
        }else {
            echo "0";
        }
        
    }

    function entradas(){
        $db=$this->connect();
        $sql="SELECT * FROM entrada";
        $result=$db->query($sql);
        $json=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($json,$row);
            }
            echo json_encode($json);
        }else {
            echo "0";
        }
        
    }

    function entrada($id){
        $db=$this->connect();
        $sql="SELECT * FROM entrada where id=$id";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {
            $json = $result->fetch_assoc();
            echo json_encode($json);
        }else {
            echo "0";
        }  
    }

    function datos(){
        $db=$this->connect();

        $datos=array();
        $sql="SELECT * FROM departamento";
        $result=$db->query($sql);
        $departamento=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($departamento,$row);
        }}

        $sql="SELECT * FROM personal";
        $result=$db->query($sql);
        $personal=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($personal,$row);
        }}

        $sql="SELECT equipo.id, equipo.nombre,equipo.marca, equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.disponibilidad, equipo.foto, equipo.etiquetas, personal.id AS id_propietario ,personal.nombre AS propietario,
        tipo.id AS id_tipo, tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id INNER JOIN tipo ON equipo.id_tipo = tipo.id";
        $result=$db->query($sql);
        $equipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipo,$row);
        }}

        $sql="SELECT * FROM tipo";
        $result=$db->query($sql);
        $tipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($tipo,$row);
        }}

        $sql="SELECT * FROM salida_equipo";
        $result=$db->query($sql);
        $salida_equipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salida_equipo,$row);
        }}

        

        $json=array( "departamento" => $departamento, "personal" => $personal,"equipo" => $equipo, "salida_equipo" => $salida_equipo,"tipo" => $tipo);
        echo json_encode($json);

   

    }

    function anuncios(){
        $db=$this->connect();
        $fecha = date("Y-m-d");
        $sql="SELECT * FROM anuncios WHERE expira >= '$fecha'";
        $result=$db->query($sql);
        $json=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($json,$row);
            }
            echo json_encode($json);
        }else {
            echo "0";
        }

    }

}

?>