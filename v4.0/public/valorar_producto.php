<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");
$clienteId = $_SESSION['cliente_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $compraId = $_POST['compra_id'];
    $productoId = $_POST['producto_id'];
    $comentario = $_POST['comentario'];

    try {
        $pasteleria->valorarClienteProducto($clienteId, $productoId, $comentario);
        $_SESSION['success'] = "Valoración enviada correctamente.";
        header("Location: main.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$compraId = $_GET['compra_id'] ?? null;
$productoId = $_GET['producto_id'] ?? null;

if (!$compraId || !$productoId) {
    header("Location: main.php");
    exit();
}

try {
    $producto = $pasteleria->obtenerDetallesProducto($productoId);
} catch (DulceNoEncontradoException $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: main.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valorar Producto - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Valorar Producto</h1>
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form method="POST">
            <input type="hidden" name="compra_id" value="<?php echo htmlspecialchars($compraId); ?>">
            <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($productoId); ?>">
            <p>Producto: <?php echo htmlspecialchars($producto['nombre']); ?></p>
            <label for="comentario">Tu valoración:</label>
            <textarea name="comentario" id="comentario" rows="4" cols="50" required></textarea>
            <input type="submit" value="Enviar Valoración">
        </form>
        <p class="volver"><a href="mainAdmin.php">Volver al Panel de Administración</a>
    </p>
    </div>
</body>
</html>

