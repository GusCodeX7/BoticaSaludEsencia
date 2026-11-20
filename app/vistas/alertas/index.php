<?php
require_once __DIR__ . '/../../modelos/Inventario.php';
$modelo = new Inventario();
$medicamentos = $modelo->listar();
$vencimientos = $modelo->vencimientos();
?>
<h2>Alertas</h2>
<p>Revisa los medicamentos con stock bajo y los lotes próximos a vencer.</p>
<h3>Alertas de reposición</h3>
<table>
<tr><th>Medicamento</th><th>Stock</th><th>Mínimo</th><th>Acción</th></tr>
<?php $hayAlertas = false; foreach ($medicamentos as $m):
    if ((int)$m['stock_disponible'] > (int)$m['stock_minimo']) continue;
    $hayAlertas = true;
?>
<tr>
<td><?= htmlspecialchars($m['nombre']) ?></td>
<td><?= (int)$m['stock_disponible'] ?></td>
<td><?= (int)$m['stock_minimo'] ?></td>
<td><a class="boton" href="?modulo=lotes&id_medicamento=<?= (int)$m['id_medicamento'] ?>">Reponer</a></td>
</tr>
<?php endforeach; ?>
<?php if (!$hayAlertas): ?><tr><td colspan="4">No hay medicamentos por reponer.</td></tr><?php endif; ?>
</table>
<h3>Vencimientos (próximos 30 días)</h3>
<table>
<tr><th>Medicamento</th><th>Lote</th><th>Cantidad</th><th>Fecha</th><th>Estado</th></tr>
<?php foreach ($vencimientos as $l): ?>
<tr>
<td><?= htmlspecialchars($l['medicamento']) ?></td>
<td><?= htmlspecialchars($l['codigo_lote']) ?></td>
<td><?= (int)$l['cantidad'] ?></td>
<td><?= htmlspecialchars($l['fecha_vencimiento']) ?></td>
<td><?= $l['fecha_vencimiento'] < date('Y-m-d') ? 'Vencido' : 'Próximo a vencer' ?></td>
</tr>
<?php endforeach; ?>
<?php if (!$vencimientos): ?><tr><td colspan="5">Sin alertas de vencimiento.</td></tr><?php endif; ?>
</table>
