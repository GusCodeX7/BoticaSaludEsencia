
<?php
require_once __DIR__ . '/../config/Conexion.php';

class Venta
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    // Listar ventas
    public function listar(): array
    {
        $sql = "SELECT v.*, u.nombre AS usuario_nombre
                FROM ventas v
                INNER JOIN usuarios u
                ON v.id_usuario = u.id_usuario
                ORDER BY v.fecha_venta DESC";

        return $this->db->query($sql)->fetchAll();
    }

    // Registrar venta, detalles y descontar stock
    public function registrarCompleta(int $idUsuario, array $productos): string
    {
        $this->db->beginTransaction();

        try {
            if (empty($productos)) {
                throw new Exception("Debe seleccionar medicamentos.");
            }

            // Crear la venta inicialmente con total cero
            $sql = "INSERT INTO ventas (id_usuario, total)
                    VALUES (?, 0)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idUsuario]);

            $idVenta = $this->db->lastInsertId();
            $total = 0;

            foreach ($productos as $producto) {
                $idMedicamento = (int) $producto['id_medicamento'];
                $cantidad = (int) $producto['cantidad'];

                if ($idMedicamento <= 0 || $cantidad <= 0) {
                    throw new Exception("Medicamento o cantidad incorrecta.");
                }

                // Obtener precio desde la base de datos
                $sql = "SELECT precio FROM medicamentos
                        WHERE id_medicamento = ?
                        FOR UPDATE";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$idMedicamento]);
                $medicamento = $stmt->fetch();

                if (!$medicamento) {
                    throw new Exception("El medicamento no existe.");
                }

                $precio = (float) $medicamento['precio'];
                $subtotal = round($precio * $cantidad, 2);

                // Buscar lotes disponibles y no vencidos
                $sql = "SELECT id_lote, cantidad
                        FROM lotes
                        WHERE id_medicamento = ?
                        AND cantidad > 0
                        AND fecha_vencimiento >= CURDATE()
                        ORDER BY fecha_vencimiento ASC, id_lote ASC
                        FOR UPDATE";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([$idMedicamento]);
                $lotes = $stmt->fetchAll();

                $stockDisponible = array_sum(
                    array_column($lotes, 'cantidad')
                );

                if ($stockDisponible < $cantidad) {
                    throw new Exception("Stock insuficiente para el medicamento.");
                }

                // Descontar primero los lotes próximos a vencer
                $restante = $cantidad;

                foreach ($lotes as $lote) {
                    if ($restante <= 0) {
                        break;
                    }

                    $descuento = min($restante, (int) $lote['cantidad']);

                    $sql = "UPDATE lotes
                            SET cantidad = cantidad - ?
                            WHERE id_lote = ?";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        $descuento,
                        $lote['id_lote']
                    ]);

                    $restante -= $descuento;
                }

                // Guardar detalle de venta
                $sql = "INSERT INTO detalle_ventas
                        (id_venta, id_medicamento, cantidad,
                         precio_unitario, subtotal)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $idVenta,
                    $idMedicamento,
                    $cantidad,
                    $precio,
                    $subtotal
                ]);

                $total += $subtotal;
            }

            // Actualizar total de la venta
            $sql = "UPDATE ventas SET total = ?
                    WHERE id_venta = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([round($total, 2), $idVenta]);

            // Confirmar todos los cambios
            $this->db->commit();

            return $idVenta;

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }
}
