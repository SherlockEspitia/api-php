<?php
require_once 'connection/connection.php';

class token extends connection{
    public function actualizarToken($fecha){
        $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Fecha< '$fecha' AND Estado = 'Activo'";
        $verificar = parent::nonQuery($query);
        if($verificar>0){
            return 1;
        }else{
            return 0;
        }
    }
}
?>