<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");

if (!isset($_GET['id'])) {
    header("Location: catalogo.php");
    exit();
}

try {
    $producto = $pasteleria->obtenerDetallesProducto($_GET['id']);
} catch (DulceNoEncontradoException $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: catalogo.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body data-user-id="<?php echo $_SESSION['cliente_id']; ?>">
    <?php include 'carrito_flotante.php'; ?>
    <div class="container">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <div class="product-details">
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($producto['tipo']); ?></p>
            <p><strong>Precio:</strong> €<?php echo number_format($producto['precio'], 2); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <?php if ($producto['tipo'] === 'Tarta'): ?>
                <p><strong>Número de pisos:</strong> <?php echo htmlspecialchars($producto['num_pisos']); ?></p>
                <p><strong>Rellenos:</strong> <?php echo htmlspecialchars($producto['rellenos']); ?></p>
                <p><strong>Comensales:</strong> <?php echo htmlspecialchars($producto['min_comensales']);
                 ?> - <?php echo htmlspecialchars($producto['max_comensales']); ?></p>
            <?php elseif ($producto['tipo'] === 'Bollo'): ?>
                <p><strong>Relleno:</strong> <?php echo htmlspecialchars($producto['relleno']); ?></p>
            <?php elseif ($producto['tipo'] === 'Chocolate'): ?>
                <p><strong>Porcentaje de cacao:</strong> <?php echo htmlspecialchars($producto['porcentaje_cacao']); ?>%</p>
                <p><strong>Peso:</strong> <?php echo htmlspecialchars($producto['peso']); ?>g</p>
            <?php endif; ?>
        </div>
        <button class="btn add-to-cart" data-id="<?php echo $producto['id']; ?>" data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>" data-precio="<?php echo $producto['precio']; ?>">Añadir al carrito</button>
        <p><a href="catalogo.php">Volver al catálogo</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Mi Pastelería. Todos los derechos reservados.</p>
        <p>Contáctanos: info@mipasteleria.com | Teléfono: +34 123 456 789</p>
    </footer>
    <script src="js/carrito.js"></script>
</body>
</html>

