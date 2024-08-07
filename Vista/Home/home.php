<?php
include '../Componentes/layout.php';
?>

<main class="contenidoPrincipal">
    <h1>Bienvenido</h1>

    <?php if (isset($usuario)) : ?>
        <p><strong>Usuario:</strong><?php echo $usuario['UserName'] ?></p>
        <p><strong>Correo:</strong><?php echo $usuario['Correo'] ?></p>
        <p><strong>Nombres:</strong><?php echo $usuario['Nombres'] ?></p>
        <p><strong>Apellidos:</strong><?php echo $usuario['Apellidos'] ?></p>
        <p><strong>Identificación:</strong><?php echo $usuario['Identificacion'] ?></p>
    <?php endif; ?>
    
    <?php if (isset($ultimaSesion)) : ?>
        <h2>Última Sesión</h2>
        <p><strong>Fecha de Ingreso:</strong><?php echo $ultimaSesion['FechaIngreso'] ?></p>
        <p><strong>Fecha de Cierre:</strong><?php echo $ultimaSesion['FechaCierre'] ?></p>
    <?php endif; ?>
</main>

</body>

</html>