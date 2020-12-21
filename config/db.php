<?php

class db{
       
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $charset;

    public function __construct(){
        $this->host = 'localhost';
        $this->port = '3306';
        $this->db = 'cenidet';
        $this->user = 'root';
        $this->pass = '';
    }

    function connect(){
            $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
            $mysqli->set_charset("utf8");
            if ($mysqli->connect_errno) {
                echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                http_response_code(405);
            }
            return $mysqli;
    }
}

?>