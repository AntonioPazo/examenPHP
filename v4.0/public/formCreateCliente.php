<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Cliente - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Cliente</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="createCliente.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Crear Cliente</button>
        </form>
        <p class="volver"><a href="mainAdmin.php">Volver al Panel de Administración</a></p>
    </div>
</body>
</html>

