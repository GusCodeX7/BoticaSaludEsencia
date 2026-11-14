<?php
require_once __DIR__ . '/../modelos/Usuario.php';
class UsuarioController
{
    private Usuario $modelo;
    public function __construct() { $this->modelo = new Usuario(); }
    public function index(): array { return $this->modelo->listar(); }
    public function ver(int $id) { return $this->modelo->buscarPorId($id); }

    public function guardar(string $nombre, string $usuario, string $clave, string $rol): bool
    {
        return $this->modelo->registrar($nombre, $usuario, $clave, $rol);
    }
}
