
<?php

if (!defined('ENTRADA')) {
    http_response_code(403);
    exit;
}

require_once __DIR__ . '/../../modelos/Venta.php';
require_once __DIR__ . '/../../modelos/Medicamento.php';
require_once __DIR__ . '/../../modelos/Presentacion.php';

$modelo = new Venta();
$modeloMedicamento = new Medicamento();

$medicamentos = $modeloMedicamento->listar();
$mensaje = '';
$detalles = [];

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar las acciones de venta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $accion = $_POST['accion'];

        if ($accion == 'anular') {

            $idVenta = (int)$_POST['id_venta'];
            $motivo = trim($_POST['motivo']);

            $modelo->anular($idVenta, $motivo);
            $mensaje = 'Venta anulada correctamente.';

        } elseif ($accion == 'agregar') {

            $partes = explode(':', $_POST['producto']);
            $idMedicamento = (int)$partes[0];
            $tipo = $partes[1] ?? '';
            $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
            $encontrado = false;

            foreach ($medicamentos as $medicamento) {

                $presentaciones = presentaciones($medicamento);

                if ($medicamento['id_medicamento'] == $idMedicamento &&
                    isset($presentaciones[$tipo]) && $cantidad > 0) {

                    $_SESSION['carrito'][] = [
                        'id_medicamento' => $idMedicamento,
                        'presentacion' => $tipo,
                        'cantidad' => $cantidad,
                        'nombre' => $medicamento['nombre'],
                        'etiqueta' => $presentaciones[$tipo],
                        'precio' => precioPresentacion($medicamento, $tipo)
                    ];

                    $encontrado = true;
                    break;
                }
            }

            if (!$encontrado) {
                $mensaje = 'Selecciona un producto y una cantidad válida.';
            }

        } elseif ($accion == 'quitar') {

            $fila = (int)$_POST['fila'];
            unset($_SESSION['carrito'][$fila]);

        } elseif ($accion == 'guardar') {

            $idUsuario = (int)$_SESSION['id_usuario'];

            $idVenta = $modelo->registrarCompleta(
                $idUsuario,
                $_SESSION['carrito']
            );

            $_SESSION['carrito'] = [];

            header('Location: ?modulo=ventas&ok=' . $idVenta);
            exit;
        }

    } catch (Exception $error) {
        $mensaje = $error->getMessage();
    }
}

if (isset($_GET['ok'])) {
    $mensaje = 'Venta registrada correctamente. Número: '
               . (int)$_GET['ok'];
}

$ventas = $modelo->listar();

// Consultar el detalle de una venta
if (isset($_GET['ver']) && (int)$_GET['ver'] > 0) {

    $idVenta = (int)$_GET['ver'];
    $conexion = Conexion::conectar();

    $sql = "SELECT d.*, m.nombre AS medicamento
            FROM detalle_ventas d
            INNER JOIN medicamentos m
            ON m.id_medicamento = d.id_medicamento
            WHERE d.id_venta = ?";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([$idVenta]);
    $detalles = $consulta->fetchAll();
}

?>

<h2>Ventas</h2>

<p>
    Registra ventas por caja, blíster o unidad.
    El stock se descuenta de los lotes vigentes.
</p>

<?php if ($mensaje != '') { ?>
    <p class="mensaje">
        <?php echo htmlspecialchars($mensaje); ?>
    </p>
<?php } ?>

<!-- Formulario para agregar medicamentos -->

<form method="post">

    <input type="hidden" name="accion" value="agregar">

    <label>Medicamento y presentación</label>

    <select name="producto" required>

        <option value="">Seleccionar medicamento</option>

        <?php foreach ($medicamentos as $medicamento) { ?>

            <?php foreach (presentaciones($medicamento) as $tipo => $nombre) { ?>

                <option value="<?php echo $medicamento['id_medicamento'] . ':' . $tipo; ?>">

                    <?php
                    echo htmlspecialchars(
                        $medicamento['nombre'] . ' - ' .
                        $nombre . ' - S/ ' .
                        precioPresentacion($medicamento, $tipo)
                    );
                    ?>

                </option>

            <?php } ?>

        <?php } ?>

    </select>

    <label>Cantidad</label>

    <input type="number" name="cantidad" min="1" required>

    <button type="submit">Agregar medicamento</button>

</form>

<!-- Carrito de venta -->

<h3>Detalle de la venta actual</h3>

<table>

    <tr>
        <th>Medicamento</th>
        <th>Presentación</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th>Acción</th>
    </tr>

    <?php
    $total = 0;

    foreach ($_SESSION['carrito'] as $fila => $producto) {

        $subtotal = $producto['cantidad'] * $producto['precio'];
        $total = $total + $subtotal;
    ?>

        <tr>
            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
            <td><?php echo htmlspecialchars($producto['etiqueta']); ?></td>
            <td><?php echo $producto['cantidad']; ?></td>
            <td><?php echo number_format($subtotal, 2); ?></td>

            <td>
                <form method="post">

                    <input type="hidden" name="accion" value="quitar">

                    <input type="hidden" name="fila"
                           value="<?php echo $fila; ?>">

                    <button type="submit">Quitar</button>

                </form>
            </td>
        </tr>

    <?php } ?>

</table>

<h3>Total: S/ <?php echo number_format($total, 2); ?></h3>

<?php if (!empty($_SESSION['carrito'])) { ?>

    <form method="post">

        <input type="hidden" name="accion" value="guardar">

        <button type="submit">Registrar venta</button>

    </form>

<?php } ?>

<!-- Historial de ventas -->

<h3>Historial de ventas</h3>

<div class="tabla">

    <table>

        <tr>
            <th>N.º</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total S/</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($ventas as $venta) { ?>

            <tr>
                <td><?php echo $venta['id_venta']; ?></td>
                <td><?php echo htmlspecialchars($venta['usuario_nombre']); ?></td>
                <td><?php echo htmlspecialchars($venta['fecha_venta']); ?></td>
                <td><?php echo number_format($venta['total'], 2); ?></td>
                <td><?php echo htmlspecialchars($venta['estado']); ?></td>

                <td>

                    <a href="?modulo=ventas&amp;ver=<?php echo $venta['id_venta']; ?>">
                        Ver detalle
                    </a>

                    <?php if ($venta['estado'] == 'Realizada') { ?>

                        <form method="post">

                            <input type="hidden" name="accion" value="anular">

                            <input type="hidden" name="id_venta"
                                   value="<?php echo $venta['id_venta']; ?>">

                            <label>Motivo</label>

                            <input type="text" name="motivo"
                                   maxlength="255"
                                   placeholder="Motivo de anulación"
                                   required>

                            <button type="submit">Anular</button>

                        </form>

                    <?php } else { ?>

                        <small>
                            <?php echo htmlspecialchars($venta['motivo_anulacion'] ?? ''); ?>
                        </small>

                    <?php } ?>

                </td>
            </tr>

        <?php } ?>

    </table>

</div>

<!-- Detalle de una venta registrada -->

<?php if (isset($_GET['ver'])) { ?>

    <h3>Detalle de venta N.º <?php echo (int)$_GET['ver']; ?></h3>

    <div class="tabla">

        <table>

            <tr>
                <th>Medicamento</th>
                <th>Cantidad</th>
                <th>Presentación</th>
                <th>Unidades descontadas</th>
                <th>Subtotal S/</th>
            </tr>

            <?php foreach ($detalles as $detalle) { ?>

                <tr>
                    <td><?php echo htmlspecialchars($detalle['medicamento']); ?></td>
                    <td><?php echo $detalle['cantidad']; ?></td>
                    <td><?php echo htmlspecialchars($detalle['presentacion']); ?></td>
                    <td><?php echo $detalle['unidades_descontadas']; ?></td>
                    <td><?php echo number_format($detalle['subtotal'], 2); ?></td>
                </tr>

            <?php } ?>

        </table>

    </div>

<?php } ?>
