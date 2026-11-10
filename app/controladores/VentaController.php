<?php
require_once __DIR__ . '/../modelos/Venta.php';
class VentaController
{
    private Venta $modelo;
    public function __construct() { $this->modelo = new Venta(); }
    public function index(): array { return $this->modelo->listar(); }
    public function guardar(int $idUsuario, float $total): string { return $this->modelo->registrar($idUsuario, $total); }
}
