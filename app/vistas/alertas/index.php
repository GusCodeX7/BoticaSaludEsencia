
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
    <tr>
        <th>Medicamento</th>
        <th>Stock</th>
        <th>Mínimo</th>
        <th>Acción</th>
    </tr>

    <?php
    $hayAlertas = false;

    foreach ($medicamentos as $m) {
        if ($m['stock_disponible'] <= $m['stock_minimo']) {
            $hayAlertas = true;
    ?>
        <tr>
            <td><?php echo htmlspecialchars($m['nombre']); ?></td>
            <td><?php echo $m['stock_disponible']; ?></td>
            <td><?php echo $m['stock_minimo']; ?></td>
            <td>
                <a class="boton" href="?modulo=lotes&id_medicamento=<?php echo (int)$m['id_medicamento']; ?>">
                    Reponer
                </a>
            </td>
        </tr>
    <?php
        }
    }

    if ($hayAlertas == false) {
    ?>
        <tr>
            <td colspan="4">No hay medicamentos por reponer.</td>
        </tr>
    <?php } ?>
</table>

<h3>Vencimientos (próximos 30 días)</h3>

<table>
    <tr>
        <th>Medicamento</th>
        <th>Lote</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Estado</th>
    </tr>

    <?php foreach ($vencimientos as $l) { ?>
        <tr>
            <td><?php echo htmlspecialchars($l['medicamento']); ?></td>
            <td><?php echo htmlspecialchars($l['codigo_lote']); ?></td>
            <td><?php echo $l['cantidad']; ?></td>
            <td><?php echo htmlspecialchars($l['fecha_vencimiento']); ?></td>
            <td>
                <?php
                if ($l['fecha_vencimiento'] < date('Y-m-d')) {
                    echo "Vencido";
                } else {
                    echo "Próximo a vencer";
                }
                ?>
            </td>
        </tr>
    <?php } ?>

    <?php if (empty($vencimientos)) { ?>
        <tr>
            <td colspan="5">Sin alertas de vencimiento.</td>
        </tr>
    <?php } ?>
</table>
