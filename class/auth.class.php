<?php
require_once 'connection/connection.php';
require_once 'respuestas.class.php';
class auth extends connection{

    public function login($json){
        $_repuestas = new respuestas;
        $datos = json_decode($json, true);
        if(!isset($datos["usuario"]) || !isset($datos["password"]) ){
            // error en lo campos
            return $_repuestas->error_400();
        }else{
            // todo bien
            $usuario = $datos["usuario"];
            $password = $datos["password"];
            $password = parent::encriptar($password);
            $datos = $this-> obtenerDatosUsuario($usuario);
            if($datos){
                if($password == $datos[0]['Password']){
                    if($datos[0]['Estado'] == 'Activo'){
                        //crear token
                        $verifica = $this->insertarToken($datos[0]['UsuarioId']);
                        if($verifica){
                            $result = $_repuestas->response;
                            $result['result'] = array('token' => $verifica);
                            return $result;
                        }else{
                            return $_repuestas->error_500("Error interno, no se ha podido guardar");
                        }
                    }else{
                        return $_repuestas->status_200('Usuario inactivo');
                    }
                }else{
                    return $_repuestas->status_200("Password invalido");
                }
            }else{
                return $_repuestas->status_200("Usuario no existe");
            }
        }
    }

    private function obtenerDatosUsuario($correo){
        $query = "SELECT UsuarioId, Password, Estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($usuarioId){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16 ,$val));
        $date = date('Y-m-d H:i');
        $estado = 'Activo';
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES ('$usuarioId','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }
}

?>