<?php

require_once __DIR__ . '/../config/Conexion.php';

class Lote
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function listar()
    {
        $sql = "SELECT lotes.*, medicamentos.nombre AS medicamento
                FROM lotes
                INNER JOIN medicamentos
                ON lotes.id_medicamento = medicamentos.id_medicamento
                ORDER BY lotes.id_lote DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($id_medicamento, $codigo_lote, $cantidad, $fecha_vencimiento)
    {
        $sql = "INSERT INTO lotes
                (id_medicamento, codigo_lote, cantidad, fecha_vencimiento)
                VALUES (?, ?, ?, ?)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            $id_medicamento,
            $codigo_lote,
            $cantidad,
            $fecha_vencimiento
        ]);
    }
}