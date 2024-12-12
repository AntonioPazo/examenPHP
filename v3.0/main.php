<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Pastelería</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f0f0f0; }
        .container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        a { color: #4CAF50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <p>Has iniciado sesión correctamente en la Pastelería.</p>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>

