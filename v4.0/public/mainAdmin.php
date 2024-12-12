<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
    <script defer src="js/warningDelete.js"></script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, Administrador!</h1>
        <p>Panel de administración de la Pastelería.</p>
        <a href="logout.php" class="btn">Cerrar sesión</a>

        <h2>Listado de Clientes</h2>
        <?php $pasteleria->listarClientes(); ?>

        <h2>Listado de Productos</h2>
        <?php $pasteleria->listarProductos(); ?>

        <h2>Acciones</h2>
        <a href="formCreateCliente.php" class="btn">Crear Nuevo Cliente</a>
        <a href="formCreateProducto.php" class="btn">Crear Nuevo Producto</a>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Mi Pastelería. Todos los derechos reservados.</p>
        <p>Contáctanos: info@mipasteleria.com | Teléfono: +34 123 456 789</p>
    </footer>
</body>
</html>

