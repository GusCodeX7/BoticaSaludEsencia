<?php
require_once __DIR__ . '/../config/Conexion.php';

class Venta
{
    private PDO $db;
    public function __construct() { $this->db = Conexion::conectar(); }

    // Lista ventas junto al nombre del usuario. Retorna un arreglo.
    public function listar(): array
    {
        $sql = 'SELECT v.*, u.nombre AS usuario_nombre FROM ventas v INNER JOIN usuarios u ON v.id_usuario = u.id_usuario ORDER BY v.fecha_venta DESC';
        return $this->db->query($sql)->fetchAll();
    }

    // Registra una venta inicial. Retorna el ID creado.
    public function registrar(int $idUsuario, float $total): string
    {
        $stmt = $this->db->prepare('INSERT INTO ventas (id_usuario, total) VALUES (?, ?)');
        $stmt->execute([$idUsuario, $total]);
        return $this->db->lastInsertId();
    }
}
