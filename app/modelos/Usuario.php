<?php
require_once __DIR__ . '/../config/Conexion.php';

class Usuario
{
    private PDO $db;
    public function __construct() { $this->db = Conexion::conectar(); }

    // Lista todos los usuarios. Retorna un arreglo.
    public function listar(): array
    {
        return $this->db->query('SELECT id_usuario, nombre, usuario, rol FROM usuarios ORDER BY id_usuario')->fetchAll();
    }

    // Busca un usuario por su ID. Retorna un arreglo o false.
    public function buscarPorId(int $id)
    {
        $sql = 'SELECT id_usuario, nombre, usuario, rol FROM usuarios WHERE id_usuario = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function registrar(string $nombre, string $usuario, string $clave, string $rol): bool
    {
        $sql = 'INSERT INTO usuarios (nombre, usuario, clave, rol) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $usuario, password_hash($clave, PASSWORD_DEFAULT), $rol]);
    }
}
