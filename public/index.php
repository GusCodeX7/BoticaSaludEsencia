<?php
// Página de inicio y rutas sencillas para mostrar las vistas del Hito 1.
$modulo = $_GET['modulo'] ?? 'inicio';
$permitidos = ['inicio', 'medicamentos', 'usuarios', 'lotes', 'ventas', 'inventario'];
if (!in_array($modulo, $permitidos, true)) { $modulo = 'inicio'; }
$titulo = ucfirst($modulo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botica SaludEsencia</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header><h1>Botica SaludEsencia</h1><p>Sistema de inventario y ventas</p></header>
<nav>
    <a href="?modulo=inicio">Inicio</a>
    <a href="?modulo=medicamentos">Medicamentos</a>
    <a href="?modulo=lotes">Lotes</a>
    <a href="?modulo=inventario">Inventario</a>
    <a href="?modulo=ventas">Ventas</a>
    <a href="?modulo=usuarios">Usuarios</a>
</nav>
<main>
<?php
if ($modulo === 'inicio') {
    echo '<h2>Panel principal</h2><p>Bienvenido al sistema de la Botica SaludEsencia.</p><p>Selecciona un módulo del menú para revisar sus formularios y tablas.</p>';
} else {
    $archivo = __DIR__ . '/../app/vistas/' . $modulo . '/index.php';
    if (file_exists($archivo)) { require $archivo; }
    else { echo '<h2>' . htmlspecialchars($titulo) . '</h2><p>Vista en preparación.</p>'; }
}
?>
</main>
<footer>Proyecto académico · Lenguaje de Programación II</footer>
</body>
</html>
