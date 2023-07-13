<?php

require_once 'class/respuestas.class.php';
require_once 'class/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        $listaPacientes = $_pacientes->listaPacientes($page);
        header('Content-Type: application/json');
        echo json_encode($listaPacientes);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $pacientesid = $_GET['id'];
        $datosPaciente = $_pacientes->obtenerPaciente($pacientesid);
        header('Content-Type: application/json');
        echo json_encode($datosPaciente);
        http_response_code(200);
    }
}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //enviar pacientes
    $postbody = file_get_contents("php://input");
    $datosArray = $_pacientes->post($postbody);
    //devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode); 
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $putbody = file_get_contents("php://input");
    // enviar datps al manejador
    $datosArray = $_pacientes->put($putbody);
    //devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode); 
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
}else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $headers = getallheaders();
    if(isset($headers["token"]) && $headers["pacienteId"]){
        $send = [$headers["token"], $headers["pacienteId"]];
        $deletebody = json_encode($send);
    }else{
        $deletebody = file_get_contents("php://input");
    }
    // enviar datps al manejador
    $datosArray = $_pacientes->delete($deletebody);
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
    header(('Content-Type: application/json'));
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}




?>