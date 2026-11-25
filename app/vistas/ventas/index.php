
<?php
require_once __DIR__ . '/../../controladores/VentaController.php';
require_once __DIR__ . '/../../controladores/UsuarioController.php';
require_once __DIR__ . '/../../controladores/MedicamentoController.php';

$controlador = new VentaController();
$controladorUsuario = new UsuarioController();
$controladorMedicamento = new MedicamentoController();

$usuarios = $controladorUsuario->index();
$medicamentos = $controladorMedicamento->index();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idUsuario = filter_var($_POST['id_usuario'] ?? '', FILTER_VALIDATE_INT);
    $idMedicamento = filter_var($_POST['id_medicamento'] ?? '', FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'] ?? '', FILTER_VALIDATE_INT);

    if ($idUsuario <= 0 || $idMedicamento <= 0 || $cantidad <= 0) {
        $mensaje = "Selecciona usuario, medicamento y una cantidad válida.";

    } else {
        try {
            $detalle = [
                [
                    'id_medicamento' => $idMedicamento,
                    'cantidad' => $cantidad
                ]
            ];

            $idVenta = $controlador->guardar($idUsuario, $detalle);

            header('Location: ?modulo=ventas&ok=' . (int)$idVenta);
            exit;

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
        }
    }
}

if (isset($_GET['ok'])) {
    $mensaje = "Venta registrada correctamente. Número: " . (int)$_GET['ok'];
}

$ventas = $controlador->index();
?>

<h2>Ventas</h2>

<p>Registro de una venta con un medicamento. El total se calcula automáticamente y se descuenta del lote vigente.</p>

<?php if ($mensaje != "") { ?>
    <p class="mensaje">
        <?php echo htmlspecialchars($mensaje); ?>
    </p>
<?php } ?>

<form method="post" action="?modulo=ventas">

    <label>
        Usuario
        <select name="id_usuario" required>
            <option value="">Seleccionar</option>

            <?php foreach ($usuarios as $u) { ?>

                <option value="<?php echo (int)$u['id_usuario']; ?>">
                    <?php echo htmlspecialchars($u['nombre']); ?>
                </option>

            <?php } ?>
        </select>
    </label>

    <label>
        Medicamento
        <select name="id_medicamento" required>
            <option value="">Seleccionar</option>

            <?php foreach ($medicamentos as $m) { ?>

                <option value="<?php echo (int)$m['id_medicamento']; ?>">
                    <?php echo htmlspecialchars($m['nombre']); ?>
                    - S/ <?php echo number_format($m['precio'], 2); ?>
                </option>

            <?php } ?>
        </select>
    </label>

    <label>
        Cantidad
        <input name="cantidad" type="number" min="1" required>
    </label>

    <button type="submit">Registrar venta</button>

</form>

<h3>Ventas registradas</h3>

<table>
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Total (S/)</th>
    </tr>

    <?php foreach ($ventas as $v) { ?>

        <tr>
            <td><?php echo (int)$v['id_venta']; ?></td>
            <td><?php echo htmlspecialchars($v['usuario_nombre']); ?></td>
            <td><?php echo htmlspecialchars($v['fecha_venta']); ?></td>
            <td><?php echo number_format($v['total'], 2); ?></td>
        </tr>

    <?php } ?>
</table>
