<?php
    require_once '../class/token.class.php';
    $_token = new token;
    $fecha = date('Y-m-d H:i');
    echo $_token->actualizarToken($fecha);
?>