<?php
require_once __DIR__ . '/../config/Conexion.php';

class Medicamento
{
    private PDO $db;
    public function __construct() { $this->db = Conexion::conectar(); }

    // Lista los medicamentos registrados. Retorna un arreglo.
    public function listar(): array
    {
        return $this->db->query('SELECT * FROM medicamentos ORDER BY nombre')->fetchAll();
    }

    // Registra un medicamento. Retorna el ID creado.
    public function registrar(array $datos): string
    {
        $sql = 'INSERT INTO medicamentos (nombre, descripcion, precio, stock_minimo) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$datos['nombre'], $datos['descripcion'], $datos['precio'], $datos['stock_minimo']]);
        return $this->db->lastInsertId();
    }
}
