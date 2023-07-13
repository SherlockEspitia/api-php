<?php

class connection {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $connection;
    
    function __construct(){
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            # code...
            $this->server = $value["server"];
            $this->user = $value["user"];
            $this->password = $value["password"];
            $this->database = $value["database"];
            $this->port = $value["port"];
        }
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database,$this->port);
        if($this->connection->connect_errno){
            echo "algo va mal";
            die();            
        }
    }
    
    private function datosConexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion. "/"."config");
        return json_decode($jsondata,true);        
    }

    private function convertirUTF8($array){
        array_walk_recursive($array, function(&$item,$key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }            
        });
        return $array;
    }

    public function obtenerDatos($query){
        $results  = $this->connection->query($query);
        $resultArray = array();
        foreach($results as $key){
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);        
    }

    public function nonQuery($query){
        $results = $this->connection->query($query);
        return $this->connection->affected_rows;
    }
    // Insertar datos
    public function nonQueryId($query){
        //ToDo: Implementar un sistema de errores basado en la respuesta de la base de datos
        $results = $this->connection->query($query);
        $filas =  $this->connection->affected_rows;
        //echo '<pre> C '.$results.'</pre>';
        
        if($filas>=1){
            echo '<pre> C si '.print_r($this->connection,true).'</pre>';
            return $this->connection->insert_id;
        }else{
            echo '<pre> C si no '.print_r($this->connection,true).'</pre>';
            return 0;
        }        
    }

    protected function encriptar($string){
        return md5($string);
    }
}
?>