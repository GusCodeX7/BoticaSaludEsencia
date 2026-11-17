<?php
require_once __DIR__ . '/../../controladores/VentaController.php';
require_once __DIR__ . '/../../controladores/UsuarioController.php';
require_once __DIR__ . '/../../controladores/MedicamentoController.php';
$controlador = new VentaController();
$usuarios = (new UsuarioController())->index();
$medicamentos = (new MedicamentoController())->index();
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_venta') {
    $idUsuario = filter_var($_POST['id_usuario'] ?? '', FILTER_VALIDATE_INT);
    $idMedicamento = filter_var($_POST['id_medicamento'] ?? '', FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'] ?? '', FILTER_VALIDATE_INT);
    if (!$idUsuario || !$idMedicamento || !$cantidad || $cantidad <= 0) {
        $mensaje = 'Selecciona usuario, medicamento y una cantidad válida.';
    } else {
        try {
            $idVenta = $controlador->guardar($idUsuario, [['id_medicamento'=>$idMedicamento, 'cantidad'=>$cantidad]]);
            header('Location: ?modulo=ventas&ok=' . urlencode($idVenta));
            exit;
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
        }
    }
}
if (isset($_GET['ok'])) $mensaje = 'Venta registrada correctamente. Número: ' . (int)$_GET['ok'];
$ventas = $controlador->index();
?>
<h2>Ventas</h2>
<p>Registro de una venta con un medicamento. El total se calcula automáticamente y se descuenta del lote vigente.</p>
<?php if ($mensaje): ?><p class="mensaje"><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>
<form method="post" action="?modulo=ventas">
<input type="hidden" name="accion" value="guardar_venta">
<label>Usuario<select name="id_usuario" required><option value="">Seleccionar</option>
<?php foreach ($usuarios as $u): ?><option value="<?= (int)$u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre']) ?></option><?php endforeach; ?>
</select></label>
<label>Medicamento<select name="id_medicamento" required><option value="">Seleccionar</option>
<?php foreach ($medicamentos as $m): ?><option value="<?= (int)$m['id_medicamento'] ?>"><?= htmlspecialchars($m['nombre']) ?> - S/ <?= number_format((float)$m['precio'], 2) ?></option><?php endforeach; ?>
</select></label>
<label>Cantidad<input name="cantidad" type="number" min="1" required></label>
<button type="submit">Registrar venta</button>
</form>
<h3>Ventas registradas</h3>
<table><tr><th>ID</th><th>Usuario</th><th>Fecha</th><th>Total (S/)</th></tr>
<?php foreach ($ventas as $v): ?>
<tr><td><?= (int)$v['id_venta'] ?></td><td><?= htmlspecialchars($v['usuario_nombre']) ?></td><td><?= htmlspecialchars($v['fecha_venta']) ?></td><td><?= number_format((float)$v['total'],2) ?></td></tr>
<?php endforeach; ?>
</table>
