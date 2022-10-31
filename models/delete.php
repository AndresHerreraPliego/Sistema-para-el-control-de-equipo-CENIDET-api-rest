<?php
include_once 'config/db.php';

class delete extends db{ 

    function departamento($id){
        $db=$this->connect();
        $sql = "DELETE FROM departamento WHERE id=$id";
        $result = $db->query($sql);
        if($result){
            echo "true";
        }else{
            echo "false";
        }
        $db->close(); 
    }

    function personal($id){
        $db=$this->connect();
        $sql = "DELETE FROM personal WHERE id=$id";
        $result = $db->query($sql);
        if($result){
            echo "true";
        }else{
            echo "false";
        }
        $db->close(); 
        
    }

    function equipo($id){
        $db=$this->connect();
        $sql = "DELETE FROM equipo WHERE id=$id";
        $result = $db->query($sql);
        if($result){
            echo "true";
        }else{
            echo "false";
        }
        $db->close(); 
    }

    function anuncio($id){
        $db=$this->connect();
        $sql = "DELETE FROM anuncios WHERE id=$id";
        
        $result = $db->query($sql);
        if($result){
            echo "true";
        }else{
            echo "false";
        }
        $db->close(); 
    }


}

?>