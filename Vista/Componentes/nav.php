<nav class="menu">
    <?php if (isset($_SESSION['usuario'])) : ?>
        <p><strong>Usuario:</strong><?php echo $_SESSION['usuario']['UserName'] ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL . 'Modelo/Login/CerrarSesion.php'; ?>" class="logout-button">Cerrar sesión</a>

    <?php if (isset($_SESSION['opcionesRol'])) : ?>
        <?php foreach ($_SESSION['opcionesRol'] as $opcion) : ?>
            <a href="<?php echo htmlspecialchars(BASE_URL . $opcion['Enlace'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($opcion['NombreOpcion'], ENT_QUOTES, 'UTF-8'); ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
</nav>