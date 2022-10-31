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
            $id=$user['id'];

            $result=$db->query("SELECT departamento.nombre, departamento.id FROM departamento INNER JOIN personal_departamento ON departamento.id = personal_departamento.id_departamento WHERE personal_departamento.id_personal = $id");
            $departamentos = array();
            while($row = $result->fetch_assoc()) {
                array_push($departamentos,$row);
            }
            $user['departamentos'] = $departamentos;
            echo json_encode($user);
        }else {
            echo "false";
        }
    }

    function salidas(){
        $db=$this->connect();
        $sql="SELECT salida.id,salida.fecha,salida.hora,salida.estatus,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario, personal.sexo FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
        personal ON salida.id_personal = personal.id";
        $result=$db->query($sql);
        $salidas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }

        $result=$db->query("SELECT salida_prestamo.id_salida, salida_prestamo.nombre_persona, salida_prestamo.rol FROM salida_prestamo INNER JOIN salida ON salida_prestamo.id_salida  = salida.id AND salida.estatus != 'recolectado'");
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
        $sql="SELECT salida.id,salida.fecha,salida.hora,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario, personal.sexo FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
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
        $sql="SELECT salida.id,salida.fecha,salida.hora,departamento.nombre AS departamento,personal.id AS id_propietario ,personal.nombre AS propietario, personal.sexo FROM salida INNER JOIN departamento ON salida.id_departamento = departamento.id INNER JOIN
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

    function admin(){
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

        $sql="SELECT equipo.id, equipo.nombre,equipo.marca, equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.foto, equipo.etiquetas, personal.id AS id_propietario ,personal.nombre AS propietario,
        tipo.id AS id_tipo, tipo.nombre AS tipo FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id INNER JOIN tipo ON equipo.id_tipo = tipo.id";
        $result=$db->query($sql);
        $equipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipo,$row);
        }}



        $sql="SELECT * FROM salida_equipo";
        $result=$db->query($sql);
        $salida_equipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salida_equipo,$row);
        }}

        $result=$db->query("SELECT salida_prestamo.id_salida,salida_equipo.id_equipo,salida_prestamo.nombre_persona, salida_prestamo.rol FROM salida_prestamo 
        INNER JOIN salida_equipo ON salida_prestamo.id_salida  = salida_equipo.id_salida");
        $prestamos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($prestamos,$row);
            }
        }

        $sql="SELECT * FROM tipo";
        $result=$db->query($sql);
        $tipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($tipo,$row);
        }}


        $sql = "SELECT * FROM salida";
        $result = $db->query($sql);
        $salidas = array();
        if ($result -> num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }


        $json=array( 
        "departamento" => $departamento,
        "personal" => $personal,
        "equipo" => $equipo,
        "salida_equipo" => $salida_equipo,
        "prestamos" => $prestamos,
        "tipo" => $tipo,
        "salidas" => $salidas);

        echo json_encode($json);
    }

    function anuncios() {
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

    function recoverpass($body){

        $data=json_decode($body, true);
        $db=$this->connect();
        $email=$data['email'];
        $sql="SELECT * FROM personal WHERE email='$email'";
        $result=$db->query($sql);
        if ($result->num_rows > 0) {

            $user=$result->fetch_assoc();
            return $user;
            
        }else {
            echo false;
        }

    }

    function usuario($id){
        $db=$this->connect();
        $result=$db->query("SELECT salida.id,salida.fecha,salida.hora,salida.estatus,departamento.nombre AS departamento FROM salida 
        INNER JOIN departamento ON salida.id_departamento = departamento.id  WHERE salida.id_personal = $id");
        $salidas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas,$row);
            }
        }

        $result=$db->query("SELECT salida.id,salida.fecha,salida.hora,salida.estatus,departamento.nombre AS departamento,
        salida_prestamo.nombre_persona AS responsable, salida_prestamo.rol AS cargo FROM salida 
        INNER JOIN departamento ON salida.id_departamento = departamento.id 
        INNER JOIN salida_prestamo on salida_prestamo.id_salida = salida.id 
        WHERE salida.id_personal = $id");
        $prestamos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($prestamos,$row);
            }
        }

        $result=$db->query("SELECT equipo.id, equipo.nombre, equipo.marca ,equipo.no_serie, equipo.modelo, equipo.no_inventario, equipo.foto, equipo.etiquetas, tipo.nombre AS tipo FROM equipo     
        INNER JOIN tipo ON equipo.id_tipo = tipo.id 
        WHERE equipo.id_personal = $id");
        $equipo=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipo,$row);
            }
        }

        $result=$db->query("SELECT * FROM actividad WHERE id_personal = $id ORDER BY hora DESC");
        $actividad=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($actividad,$row);
            }
        }

        $result=$db->query("SELECT salida_equipo.id_salida, equipo.id AS id_equipo FROM equipo 
        INNER JOIN salida_equipo ON equipo.id = salida_equipo.id_equipo
        INNER JOIN tipo ON equipo.id_tipo = tipo.id
        WHERE salida_equipo.id_salida IN ( SELECT id FROM salida WHERE id_personal = $id )");
        $salidas_activas=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($salidas_activas,$row);
            }
        }

        $json=array( "salidas" => $salidas, 
        "prestamos" => $prestamos, 
        "equipo" => $equipo,
        "actividad" => $actividad,
        "salidas_activas" => $salidas_activas );
        echo json_encode($json);
    }

    function buscarequipo($body){
        $db=$this->connect();
        $data=json_decode($body, true);
        $valor=$data['value'];

        $result=$db->query("SELECT equipo.id, equipo.nombre, equipo.marca, equipo.no_serie, equipo.modelo, equipo.foto, CONCAT(personal.nombre,' ', personal.apellido_paterno,' ', personal.apellido_materno) as propietario 
        FROM equipo INNER JOIN personal ON equipo.id_personal = personal.id
        WHERE equipo.nombre REGEXP '$valor' 
        OR equipo.marca REGEXP '$valor' 
        OR equipo.modelo REGEXP '$valor'");
        $equipos=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($equipos,$row);
            }
        }
        $result=$db->query("SELECT id_equipo FROM salida_equipo");
        $ocupados=array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($ocupados,$row['id_equipo']);
            }
        }
    
        $json=array( "equipos" => $equipos, "ocupados" => $ocupados);
        echo json_encode($json);

    }

}

?>

