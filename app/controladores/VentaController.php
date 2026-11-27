
<?php
require_once __DIR__ . '/../modelos/Venta.php';

class VentaController
{
    private Venta $modelo;

    public function __construct()
    {
        $this->modelo = new Venta();
    }

    // Mostrar las ventas registradas
    public function index(): array
    {
        return $this->modelo->listar();
    }

    // Registrar venta con sus productos
    public function guardar(int $idUsuario, array $productos): string
    {
        return $this->modelo->registrarCompleta(
            $idUsuario,
            $productos
        );
    }
}
