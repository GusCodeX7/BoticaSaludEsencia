<?php
require_once __DIR__ . '/../config/Conexion.php';

class DetalleVenta
{
    private PDO $db;
    public function __construct() { $this->db = Conexion::conectar(); }

    // Lista los detalles de una venta. Recibe el ID de venta y retorna un arreglo.
    public function listarPorVenta(int $idVenta): array
    {
        $sql = 'SELECT d.*, m.nombre AS medicamento FROM detalle_ventas d INNER JOIN medicamentos m ON d.id_medicamento = m.id_medicamento WHERE d.id_venta = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll();
    }

    // Registra un detalle de venta. Retorna el ID creado.
    public function registrar(array $datos): string
    {
        $sql = 'INSERT INTO detalle_ventas (id_venta, id_medicamento, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$datos['id_venta'], $datos['id_medicamento'], $datos['cantidad'], $datos['precio_unitario'], $datos['subtotal']]);
        return $this->db->lastInsertId();
    }
}
