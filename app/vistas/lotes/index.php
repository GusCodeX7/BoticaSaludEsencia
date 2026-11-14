<?php
require_once __DIR__ . '/../../controladores/LoteController.php';
require_once __DIR__ . '/../../controladores/MedicamentoController.php';
$controlador = new LoteController();
$medicamentos = (new MedicamentoController())->index();
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_lote') {
    $id = filter_var($_POST['id_medicamento'] ?? '', FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'] ?? '', FILTER_VALIDATE_INT);
    $codigo = trim($_POST['codigo_lote'] ?? '');
    $fecha = $_POST['fecha_vencimiento'] ?? '';
    $fechaValida = DateTime::createFromFormat('!Y-m-d', $fecha);
    if (!$id || !$cantidad || $cantidad <= 0 || $codigo === '' || !$fechaValida || $fechaValida->format('Y-m-d') !== $fecha) {
        $mensaje = 'Completa correctamente los datos del lote.';
    } else {
        try {
            if ($controlador->registrar($id, $codigo, $cantidad, $fecha)) {
                header('Location: ?modulo=lotes&ok=1');
                exit;
            }
            $mensaje = 'No se pudo registrar el lote.';
        } catch (PDOException $e) { $mensaje = 'No se pudo registrar el lote. Verifica el medicamento seleccionado.'; }
    }
}
if (isset($_GET['ok'])) $mensaje = 'Lote registrado correctamente.';
$lotes = $controlador->listar();
?>
<h2>Lotes</h2>
<p>Registro de lotes y control de cantidades por fecha de vencimiento.</p>
<?php if ($mensaje): ?><p class="mensaje"><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>
<form method="post" action="?modulo=lotes">
<input type="hidden" name="accion" value="guardar_lote">
<label>Medicamento<select name="id_medicamento" required><option value="">Seleccionar</option>
<?php foreach ($medicamentos as $m): ?><option value="<?= (int)$m['id_medicamento'] ?>"><?= htmlspecialchars($m['nombre']) ?></option><?php endforeach; ?>
</select></label>
<label>Código de lote<input name="codigo_lote" required maxlength="50"></label>
<label>Cantidad<input name="cantidad" type="number" min="1" required></label>
<label>Fecha de vencimiento<input name="fecha_vencimiento" type="date" required></label>
<button type="submit">Guardar lote</button>
</form>
<h3>Lotes registrados</h3>
<table><tr><th>ID</th><th>Medicamento</th><th>Código</th><th>Cantidad</th><th>Vencimiento</th></tr>
<?php foreach ($lotes as $l): ?>
<tr><td><?= (int)$l['id_lote'] ?></td><td><?= htmlspecialchars($l['medicamento']) ?></td><td><?= htmlspecialchars($l['codigo_lote']) ?></td><td><?= (int)$l['cantidad'] ?></td><td><?= htmlspecialchars($l['fecha_vencimiento']) ?></td></tr>
<?php endforeach; ?>
</table>
