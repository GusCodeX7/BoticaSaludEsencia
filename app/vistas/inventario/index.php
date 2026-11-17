<?php
require_once __DIR__ . '/../../modelos/Inventario.php';
$inventario = (new Inventario())->listar();
?>
<h2>Inventario</h2>
<p>Consulta de stock disponible. Los lotes vencidos no se cuentan.</p>
<p>Para ingresar más unidades, registra un lote nuevo desde Alertas.</p>
<table>
<tr><th>Medicamento</th><th>Stock disponible</th><th>Stock mínimo</th><th>Estado</th></tr>
<?php foreach ($inventario as $m):
    $stock = (int)$m['stock_disponible'];
    $minimo = (int)$m['stock_minimo'];
    $estado = $stock === 0 ? 'Agotado' : ($stock <= $minimo ? 'Stock bajo' : 'Disponible');
?>
<tr>
<td><?= htmlspecialchars($m['nombre']) ?></td>
<td><?= $stock ?></td>
<td><?= $minimo ?></td>
<td><?= htmlspecialchars($estado) ?></td>
</tr>
<?php endforeach; ?>
</table>
