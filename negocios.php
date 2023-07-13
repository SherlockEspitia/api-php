<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header ('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods:POST, GET, PUT, DELETE, OPTIONS');
    require_once 'class/respuestas.class.php';
    require_once 'class/negocios.class.php';

    $_respuestas = new respuestas;
    $_negocios = new negocios;
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $getBody = json_encode($_GET,true);
        echo "<pre>$getBody</pre>";
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(empty($_POST)){
            $postbody = file_get_contents('php://input');
            //echo '<pre> 1', print_r($postbody, true), '</pre>';
            $datosArray = $_negocios->post($postbody);
            echo '<pre> 2', print_r($datosArray, true),'</pre>';
        }
        if(isset($_POST)){
            $jotbody = json_encode($_POST,true);
            file_put_contents('jotform.txt',$jotbody,FILE_APPEND);
            $datosArray = $_negocios->post($jotbody);
            echo '<pre> 3', print_r($jotbody, true) , '</pre>';
        }
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode); 
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);

    }
?>