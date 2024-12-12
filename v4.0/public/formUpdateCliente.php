<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");

if (isset($_GET['id'])) {
    $clienteId = $_GET['id'];
    // Si es un usuario normal, solo puede editar sus propios datos
    if ($_SESSION['role'] !== 'admin' && $clienteId != $_SESSION['cliente_id']) {
        header("Location: main.php");
        exit();
    }
    $cliente = $pasteleria->obtenerCliente($clienteId);
    if (!$cliente) {
        $_SESSION['error'] = "Cliente no encontrado.";
        header("Location: " . ($_SESSION['role'] === 'admin' ? "mainAdmin.php" : "main.php"));
        exit();
    }
} else {
    $_SESSION['error'] = "ID de cliente no proporcionado.";
    header("Location: " . ($_SESSION['role'] === 'admin' ? "mainAdmin.php" : "main.php"));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cliente - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Actualizar Cliente</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="updateCliente.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $cliente->getId(); ?>">
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($cliente->nombre); ?>" required>
            
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($cliente->getUsuario()); ?>" required>
            
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Actualizar Datos</button>
        </form>
        <p class="volver"><a href="mainAdmin.php">Volver al Panel de Administración</a></p>
    </div>
</body>
</html>

