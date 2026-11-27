<?php if (!defined('ENTRADA')) exit; ?>

<section class="login-panel">

    <div class="login-marca">
        <span class="cruz">+</span>
        <h2>SaludEsencia</h2>
        <p>Gestión de inventario y ventas</p>
    </div>

    <div class="login-form">

        <h2>Bienvenido</h2>
        <p>Inicia sesión para continuar.</p>

        <?php if ($error) { ?>
            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php } ?>

        <form method="post" action="?modulo=login">

            <input type="hidden" name="accion" value="entrar">

            <label>
                Usuario
                <input name="usuario" autocomplete="username" required>
            </label>

            <label>
                Contraseña
                <input type="password" name="clave" autocomplete="current-password" required>
            </label>

            <button>Iniciar sesión</button>

        </form>

        <small>Acceso para personal autorizado</small>
    </div>
</section>