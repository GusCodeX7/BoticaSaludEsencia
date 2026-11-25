<?php
require_once __DIR__ . '/../config/Conexion.php';

class Inventario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    // Suma solamente los lotes que no han vencido.
    public function listar(): array
    {
        $sql = "SELECT m.id_medicamento, m.nombre, m.stock_minimo,
                COALESCE(SUM(CASE WHEN l.fecha_vencimiento >= CURDATE()
                THEN l.cantidad ELSE 0 END), 0) AS stock_disponible
                FROM medicamentos m
                LEFT JOIN lotes l ON m.id_medicamento = l.id_medicamento
                GROUP BY m.id_medicamento, m.nombre, m.stock_minimo
                ORDER BY m.nombre";
        return $this->db->query($sql)->fetchAll();
    }

    // Lotes vencidos y próximos a vencer (30 días).
    public function vencimientos(): array
    {
        $sql = "SELECT l.codigo_lote, l.cantidad, l.fecha_vencimiento,
                m.nombre AS medicamento
                FROM lotes l
                INNER JOIN medicamentos m ON m.id_medicamento = l.id_medicamento
                WHERE l.cantidad > 0
                AND l.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                ORDER BY l.fecha_vencimiento, m.nombre";
        return $this->db->query($sql)->fetchAll();
    }
}
