<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");
$clienteId = $_SESSION['cliente_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body data-user-id="<?php echo $_SESSION['cliente_id']; ?>">
    <?php include 'carrito_flotante.php'; ?>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>{$_SESSION['success']}</p>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
        <p>Has iniciado sesión correctamente en la Pastelería.</p>
        
        <h2>Tus Compras</h2>
        <?php $pasteleria->listarPedidosCliente($clienteId); ?>
        
        <div class="actions">
            <a href="formUpdateCliente.php?id=<?php echo $clienteId; ?>">Editar mis datos</a>
            <a href="catalogo.php">Ver catálogo</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Mi Pastelería. Todos los derechos reservados.</p>
        <p>Contáctanos: info@mipasteleria.com | Teléfono: +34 123 456 789</p>
    </footer>
    <script src="js/carrito.js"></script>
</body>
</html>

