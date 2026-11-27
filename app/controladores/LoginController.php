<?php
require_once __DIR__ . '/../modelos/Usuario.php';

class LoginController
{
    public function entrar($usuario, $clave)
    {
        $modelo = new Usuario();
        $datos = $modelo->buscarPorUsuario($usuario);

        if ($datos && $datos['estado'] === 'Activo' && password_verify($clave, $datos['clave'])) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $datos['id_usuario'];
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['rol'] = $datos['rol'];
            return true;
        }
        return false;
    }
}