<?php
require_once __DIR__ . '/../modelos/Usuario.php';
class UsuarioController
{
    private Usuario $modelo;
    public function __construct() { $this->modelo = new Usuario(); }
    public function index(): array { return $this->modelo->listar(); }
    public function ver(int $id) { return $this->modelo->buscarPorId($id); }
}
