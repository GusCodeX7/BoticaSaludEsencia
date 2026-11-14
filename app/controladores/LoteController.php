<?php

require_once __DIR__ . '/../modelos/Lote.php';

class LoteController
{
    public function listar()
    {
        $lote = new Lote();
        return $lote->listar();
    }

    public function registrar($id_medicamento, $codigo_lote, $cantidad, $fecha_vencimiento)
    {
        if ($id_medicamento <= 0 || $cantidad <= 0 ||
            empty($codigo_lote) || empty($fecha_vencimiento)) {
            return false;
        }

        $lote = new Lote();

        return $lote->registrar(
            $id_medicamento,
            $codigo_lote,
            $cantidad,
            $fecha_vencimiento
        );
    }
}
