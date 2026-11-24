
<?php
require_once __DIR__ . '/../../controladores/UsuarioController.php';

$controlador = new UsuarioController();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    if ($nombre == "" || $usuario == "" || strlen($clave) < 6) {
        $mensaje = "Completa los datos. La contraseña debe tener al menos 6 caracteres.";

    } elseif ($rol != "Administrador" && $rol != "Vendedor") {
        $mensaje = "Selecciona un rol válido.";

    } else {
        try {
            $controlador->guardar($nombre, $usuario, $clave, $rol);

            header('Location: ?modulo=usuarios&ok=1');
            exit;

        } catch (PDOException $e) {
            $mensaje = "No se pudo registrar el usuario. Revisa si ya existe.";
        }
    }
}

if (isset($_GET['ok'])) {
    $mensaje = "Usuario registrado correctamente.";
}

$usuarios = $controlador->index();
?>

<h2>Usuarios</h2>

<p>Registro y consulta de usuarios del sistema.</p>

<?php if ($mensaje != "") { ?>
    <p class="mensaje">
        <?php echo htmlspecialchars($mensaje); ?>
    </p>
<?php } ?>

<h3>Registrar usuario</h3>

<form method="post" action="?modulo=usuarios">

    <label>
        Nombre
        <input name="nombre" required maxlength="100"
               placeholder="Nombre completo">
    </label>

    <label>
        Usuario
        <input name="usuario" required maxlength="50"
               placeholder="Nombre de usuario">
    </label>

    <label>
        Contraseña
        <input name="clave" type="password" required minlength="6"
               placeholder="Mínimo 6 caracteres">
    </label>

    <label>
        Rol
        <select name="rol">
            <option value="Administrador">Administrador</option>
            <option value="Vendedor">Vendedor</option>
        </select>
    </label>

    <button type="submit">Guardar usuario</button>

</form>

<h3>Usuarios registrados</h3>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Rol</th>
    </tr>

    <?php foreach ($usuarios as $u) { ?>

        <tr>
            <td><?php echo (int)$u['id_usuario']; ?></td>
            <td><?php echo htmlspecialchars($u['nombre']); ?></td>
            <td><?php echo htmlspecialchars($u['usuario']); ?></td>
            <td><?php echo htmlspecialchars($u['rol']); ?></td>
        </tr>

    <?php } ?>
</table>
