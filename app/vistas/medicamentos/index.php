<?php
require_once __DIR__ . '/../../controladores/MedicamentoController.php';
$controlador = new MedicamentoController();
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_medicamento') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock_minimo'] ?? '', FILTER_VALIDATE_INT);
    if ($nombre === '' || $precio === false || $precio < 0 || $stock === false || $stock < 0) {
        $mensaje = 'Revisa el nombre, el precio y el stock mínimo.';
    } else {
        try {
            $controlador->guardar(['nombre'=>$nombre,'descripcion'=>$descripcion,'precio'=>$precio,'stock_minimo'=>$stock]);
            header('Location: ?modulo=medicamentos&ok=1');
            exit;
        } catch (PDOException $e) { $mensaje = 'No se pudo registrar el medicamento.'; }
    }
}
if (isset($_GET['ok'])) $mensaje = 'Medicamento registrado correctamente.';
$medicamentos = $controlador->index();
?>
<h2>Medicamentos</h2>
<p>Registro y consulta de medicamentos.</p>
<?php if ($mensaje): ?><p class="mensaje"><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>
<form method="post" action="?modulo=medicamentos">
<input type="hidden" name="accion" value="guardar_medicamento">
<label>Nombre<input name="nombre" required maxlength="120"></label>
<label>Descripción<input name="descripcion" placeholder="Presentación o detalle"></label>
<label>Precio (S/)<input type="number" name="precio" min="0" step="0.01" required></label>
<label>Stock mínimo<input type="number" name="stock_minimo" min="0" required></label>
<button type="submit">Guardar medicamento</button>
</form>
<h3>Medicamentos registrados</h3>
<table><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio (S/)</th><th>Stock mínimo</th></tr>
<?php foreach ($medicamentos as $m): ?>
<tr><td><?= (int)$m['id_medicamento'] ?></td><td><?= htmlspecialchars($m['nombre']) ?></td><td><?= htmlspecialchars($m['descripcion'] ?? '') ?></td><td><?= number_format((float)$m['precio'],2) ?></td><td><?= (int)$m['stock_minimo'] ?></td></tr>
<?php endforeach; ?>
</table>
