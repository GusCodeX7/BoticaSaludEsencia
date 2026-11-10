<?php
require_once __DIR__ . '/../../controladores/LoteController.php';

$controlador = new LoteController();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_medicamento = $_POST["id_medicamento"] ?? 0;
    $codigo_lote = trim($_POST["codigo_lote"] ?? "");
    $cantidad = $_POST["cantidad"] ?? 0;
    $fecha_vencimiento = $_POST["fecha_vencimiento"] ?? "";

    if ($controlador->registrar(
        $id_medicamento,
        $codigo_lote,
        $cantidad,
        $fecha_vencimiento
    )) {
        $mensaje = "Lote registrado correctamente";
    } else {
        $mensaje = "Error al registrar el lote";
    }
}

$lotes = $controlador->listar();
?>

<h2>Registro de Lotes</h2>

<p><?php echo htmlspecialchars($mensaje); ?></p>

<form method="POST">
    <label>ID del medicamento:</label>
    <input type="number" name="id_medicamento" min="1" required>

    <br><br>

    <label>Codigo del lote:</label>
    <input type="text" name="codigo_lote" required>

    <br><br>

    <label>Cantidad:</label>
    <input type="number" name="cantidad" min="1" required>

    <br><br>

    <label>Fecha de vencimiento:</label>
    <input type="date" name="fecha_vencimiento" required>

    <br><br>

    <button type="submit">Guardar lote</button>
</form>

<h2>Lista de Lotes</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Medicamento</th>
        <th>Codigo de lote</th>
        <th>Cantidad</th>
        <th>Vencimiento</th>
    </tr>

    <?php foreach ($lotes as $lote) { ?>
    <tr>
        <td><?php echo (int)$lote["id_lote"]; ?></td>
        <td><?php echo htmlspecialchars($lote["medicamento"]); ?></td>
        <td><?php echo htmlspecialchars($lote["codigo_lote"]); ?></td>
        <td><?php echo (int)$lote["cantidad"]; ?></td>
        <td><?php echo htmlspecialchars($lote["fecha_vencimiento"]); ?></td>
    </tr>
    <?php } ?>
</table>