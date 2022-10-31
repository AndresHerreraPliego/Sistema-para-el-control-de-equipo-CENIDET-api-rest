<?php

class db {
       
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $charset;

    public function __construct(){
        $data = file_get_contents("config/access.json");
        $db = json_decode($data, true)["db"];

        $this->host = $db['host'];
        $this->port = $db['port'];
        $this->db = $db['db_name'];
        $this->user = $db['user'];
        $this->pass = $db['password'];
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