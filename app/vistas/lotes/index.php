<?php
require_once __DIR__ . '/../../controladores/LoteController.php';
require_once __DIR__ . '/../../controladores/MedicamentoController.php';

$controlador = new LoteController();
$controladorMedicamento = new MedicamentoController();

$medicamentos = $controladorMedicamento->index();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = filter_var($_POST['id_medicamento'], FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $codigo = trim($_POST['codigo_lote']);
    $fecha = $_POST['fecha_vencimiento'];

    $fechaValida = DateTime::createFromFormat('!Y-m-d', $fecha);

    if ($id <= 0 || $cantidad <= 0 || $codigo == "" || $fecha == "") {
        $mensaje = "Completa correctamente los datos del lote.";

    } elseif (!$fechaValida || $fechaValida->format('Y-m-d') != $fecha) {
        $mensaje = "Ingresa una fecha válida.";

    } else {
        try {
            $resultado = $controlador->registrar($id, $codigo, $cantidad, $fecha);

            if ($resultado) {
                header('Location: ?modulo=lotes&ok=1');
                exit;
            } else {
                $mensaje = "No se pudo registrar el lote.";
            }

        } catch (PDOException $e) {
            $mensaje = "No se pudo registrar el lote. Verifica el medicamento seleccionado.";
        }
    }
}

if (isset($_GET['ok'])) {
    $mensaje = "Lote registrado correctamente.";
}

$lotes = $controlador->listar();
?>

<h2>Lotes</h2>

<p>Registro de lotes y control de cantidades por fecha de vencimiento.</p>

<?php if ($mensaje != "") { ?>
    <p class="mensaje">
        <?php echo htmlspecialchars($mensaje); ?>
    </p>
<?php } ?>

<form method="post" action="?modulo=lotes">

    <label>
        Medicamento
        <select name="id_medicamento" required>
            <option value="">Seleccionar</option>

            <?php foreach ($medicamentos as $m) { ?>

                <option value="<?php echo (int)$m['id_medicamento']; ?>">
                    <?php echo htmlspecialchars($m['nombre']); ?>
                </option>

            <?php } ?>
        </select>
    </label>

    <label>
        Código de lote
        <input name="codigo_lote" required maxlength="50">
    </label>

    <label>
        Cantidad
        <input name="cantidad" type="number" min="1" required>
    </label>

    <label>
        Fecha de vencimiento
        <input name="fecha_vencimiento" type="date" required>
    </label>

    <button type="submit">Guardar lote</button>

</form>

<h3>Lotes registrados</h3>

<table>
    <tr>
        <th>ID</th>
        <th>Medicamento</th>
        <th>Código</th>
        <th>Cantidad</th>
        <th>Vencimiento</th>
    </tr>

    <?php foreach ($lotes as $l) { ?>

        <tr>
            <td><?php echo (int)$l['id_lote']; ?></td>
            <td><?php echo htmlspecialchars($l['medicamento']); ?></td>
            <td><?php echo htmlspecialchars($l['codigo_lote']); ?></td>
            <td><?php echo (int)$l['cantidad']; ?></td>
            <td><?php echo htmlspecialchars($l['fecha_vencimiento']); ?></td>
        </tr>

    <?php } ?>
    
</table>
