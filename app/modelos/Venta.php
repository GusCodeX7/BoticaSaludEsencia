
<?php

require_once __DIR__ . '/../config/Conexion.php';

class Venta
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // Listar ventas
    public function listar()
    {
        $sql = "SELECT v.*, u.nombre AS usuario_nombre
                FROM ventas v
                INNER JOIN usuarios u ON u.id_usuario = v.id_usuario
                ORDER BY v.id_venta DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    // Registrar venta completa
    public function registrarCompleta($idUsuario, $productos)
    {
        $this->conexion->beginTransaction();

        try {
            if (empty($productos)) {
                throw new Exception("Selecciona un medicamento.");
            }

            $sql = "INSERT INTO ventas (id_usuario, total) VALUES (?, 0)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$idUsuario]);
            $idVenta = $this->conexion->lastInsertId();
            $total = 0;

            foreach ($productos as $producto) {
                $idMedicamento = (int)$producto['id_medicamento'];
                $cantidad = (int)$producto['cantidad'];
                $presentacion = $producto['presentacion'] ?? 'unidad';

                if ($idMedicamento < 1 || $cantidad < 1) {
                    throw new Exception("Datos de venta incorrectos.");
                }

                // Buscar medicamento
                $sql = "SELECT * FROM medicamentos
                        WHERE id_medicamento = ? FOR UPDATE";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([$idMedicamento]);
                $medicamento = $consulta->fetch();

                if (!$medicamento) {
                    throw new Exception("Medicamento no encontrado.");
                }

                // Precio y equivalencia de la presentación
                if ($presentacion == 'caja') {
                    $precio = $medicamento['precio_caja'];
                    $factor = $medicamento['unidades_por_blister']
                            * $medicamento['blisteres_por_caja'];
                } elseif ($presentacion == 'blister') {
                    $precio = $medicamento['precio_blister'];
                    $factor = $medicamento['unidades_por_blister'];
                } elseif ($presentacion == 'unidad') {
                    $precio = $medicamento['precio'];
                    $factor = 1;
                } else {
                    throw new Exception("Presentación incorrecta.");
                }

                if ($precio <= 0 || $factor < 1) {
                    throw new Exception("Presentación no disponible.");
                }

                $unidades = $cantidad * $factor;
                $subtotal = round($precio * $cantidad, 2);

                // Buscar lotes disponibles y no vencidos
                $sql = "SELECT id_lote, cantidad FROM lotes
                        WHERE id_medicamento = ? AND cantidad > 0
                        AND fecha_vencimiento > CURDATE()
                        ORDER BY fecha_vencimiento, id_lote FOR UPDATE";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([$idMedicamento]);
                $lotes = $consulta->fetchAll();

                // Registrar detalle
                $sql = "INSERT INTO detalle_ventas
                        (id_venta, id_medicamento, cantidad, precio_unitario,
                        subtotal, presentacion, unidades_descontadas)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([
                    $idVenta, $idMedicamento, $cantidad,
                    $precio, $subtotal, $presentacion, $unidades
                ]);
                $idDetalle = $this->conexion->lastInsertId();

                // Descontar stock de los lotes
                $pendiente = $unidades;

                foreach ($lotes as $lote) {
                    if ($pendiente <= 0) {
                        break;
                    }

                    $disponible = $lote['cantidad'];

                    if ($presentacion != 'unidad') {
                        $disponible = floor($disponible / $factor) * $factor;
                    }

                    $descuento = min($pendiente, $disponible);

                    if ($descuento > 0) {
                        $sql = "UPDATE lotes SET cantidad = cantidad - ?
                                WHERE id_lote = ?";
                        $consulta = $this->conexion->prepare($sql);
                        $consulta->execute([$descuento, $lote['id_lote']]);

                        $sql = "INSERT INTO venta_lotes
                                (id_detalle, id_lote, unidades)
                                VALUES (?, ?, ?)";
                        $consulta = $this->conexion->prepare($sql);
                        $consulta->execute([
                            $idDetalle, $lote['id_lote'], $descuento
                        ]);

                        $pendiente = $pendiente - $descuento;
                    }
                }

                if ($pendiente > 0) {
                    throw new Exception("Stock insuficiente para la presentación.");
                }

                $total = $total + $subtotal;
            }

            // Actualizar total
            $sql = "UPDATE ventas SET total = ? WHERE id_venta = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([round($total, 2), $idVenta]);

            $this->conexion->commit();
            return (string)$idVenta;

        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $error;
        }
    }

    // Anular venta y recuperar stock
    public function anular($idVenta, $motivo)
    {
        if (trim($motivo) == '') {
            throw new Exception("Escribe el motivo de anulación.");
        }

        $this->conexion->beginTransaction();

        try {
            $sql = "SELECT estado FROM ventas
                    WHERE id_venta = ? FOR UPDATE";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$idVenta]);
            $venta = $consulta->fetch();

            if (!$venta || $venta['estado'] != 'Realizada') {
                throw new Exception("Venta inexistente o ya anulada.");
            }

            $sql = "SELECT vl.id_lote, vl.unidades FROM venta_lotes vl
                    INNER JOIN detalle_ventas d ON d.id_detalle = vl.id_detalle
                    WHERE d.id_venta = ? ORDER BY vl.id_lote FOR UPDATE";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$idVenta]);
            $movimientos = $consulta->fetchAll();

            if (empty($movimientos)) {
                throw new Exception("No se encontraron los lotes originales.");
            }

            foreach ($movimientos as $movimiento) {
                $sql = "UPDATE lotes SET cantidad = cantidad + ?
                        WHERE id_lote = ?";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([
                    $movimiento['unidades'], $movimiento['id_lote']
                ]);
            }

            $sql = "UPDATE ventas SET estado = 'Anulada',
                    fecha_anulacion = NOW(), motivo_anulacion = ?
                    WHERE id_venta = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$motivo, $idVenta]);

            $this->conexion->commit();

        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $error;
        }
    }
}