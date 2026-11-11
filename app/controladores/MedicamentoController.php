<?php
require_once __DIR__ . '/../modelos/Medicamento.php';

class MedicamentoController
{
    private Medicamento $modelo;
    public function __construct() { $this->modelo = new Medicamento(); }

    // Muestra el listado de medicamentos.
    public function index(): array { return $this->modelo->listar(); }

    // Recibe los datos del formulario y registra el medicamento.
    public function guardar(array $datos): string
    {
        return $this->modelo->registrar($datos);
    }
}
