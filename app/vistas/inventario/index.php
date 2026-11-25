
<?php
require_once __DIR__ . '/../../modelos/Inventario.php';

$modelo = new Inventario();
$inventario = $modelo->listar();
?>

<h2>Inventario</h2>

<p>Consulta de stock disponible. Los lotes vencidos no se cuentan.</p>
<p>Para ingresar más unidades, registra un lote nuevo desde Alertas.</p>

<table>
    <tr>
        <th>Medicamento</th>
        <th>Stock disponible</th>
        <th>Stock mínimo</th>
        <th>Estado</th>
    </tr>

    <?php foreach ($inventario as $m) {

        $stock = (int)$m['stock_disponible'];
        $minimo = (int)$m['stock_minimo'];

        if ($stock == 0) {
            $estado = "Agotado";
        } elseif ($stock <= $minimo) {
            $estado = "Stock bajo";
        } else {
            $estado = "Disponible";
        }
    ?>

        <tr>
            <td><?php echo htmlspecialchars($m['nombre']); ?></td>
            <td><?php echo $stock; ?></td>
            <td><?php echo $minimo; ?></td>
            <td><?php echo $estado; ?></td>
        </tr>

    <?php } ?>
</table>
