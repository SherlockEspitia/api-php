<?php

require_once 'class/auth.class.php';
require_once 'class/respuestas.class.php';

$_auth = new auth;
$_respuestas = new respuestas;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // recibir datos
    $postbody = file_get_contents("php://input");
    // enviamos datos al manejador
    $datosArray = $_auth->login($postbody);
    //devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode); 
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray); 
}


?>