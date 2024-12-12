<?php
session_start();
require_once 'Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Crear una instancia de Pasteleria
$pasteleria = new Pasteleria("Mi Pastelería");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Pastelería</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f0f0f0; }
        .container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        a { color: #4CAF50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, Administrador!</h1>
        <p>Panel de administración de la Pastelería.</p>
        <a href="logout.php">Cerrar sesión</a>

        <h2>Listado de Clientes</h2>
        <?php $pasteleria->listarClientes(); ?>

        <h2>Listado de Productos</h2>
        <?php $pasteleria->listarProductos(); ?>
    </div>
</body>
</html>

