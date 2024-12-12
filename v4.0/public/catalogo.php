<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user'])) {
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
  <title>Catálogo de Productos - Pastelería</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body data-user-id="<?php echo $_SESSION['cliente_id']; ?>">
  <?php include 'carrito_flotante.php'; ?>
  <div class="container">
      <h1>Catálogo de Productos</h1>
      <div class="product-grid">
          <?php
          $productos = $pasteleria->obtenerTodosLosProductos();
          foreach ($productos as $producto) {
              echo "<div class='product-card'>";
              if (!empty($producto['imagen'])) {
                  $imagenBase64 = base64_encode($producto['imagen']);
                  echo "<img src='data:image/jpeg;base64,{$imagenBase64}' alt='" . htmlspecialchars($producto['nombre']) . "'>";
              } else {
                  echo "<img src='placeholder.jpg' alt='Imagen no disponible'>";
              }
              echo "<h3><a href='producto.php?id=" . $producto['id'] . "'>" . htmlspecialchars($producto['nombre']) . "</a></h3>";
              echo "<p>Tipo: " . htmlspecialchars($producto['tipo']) . "</p>";
              echo "<p>Precio: €" . number_format($producto['precio'], 2) . "</p>";
              echo "<p>" . htmlspecialchars(substr($producto['descripcion'], 0, 100)) . "...</p>";
              echo "<button class='btn add-to-cart' data-id='" . $producto['id'] . "' data-nombre='" . htmlspecialchars($producto['nombre']) . "' data-precio='" . $producto['precio'] . "'>Añadir al carrito</button>";
              echo "</div>";
          }
          ?>
          <p class="volver"><a href="main.php">Volver al Panel de Administración</a></p>
      </div>
  </div>
  <footer>
      <p>&copy; <?php echo date("Y"); ?> Mi Pastelería. Todos los derechos reservados.</p>
      <p>Contáctanos: info@mipasteleria.com | Teléfono: +34 123 456 789</p>
  </footer>
  <script src="js/carrito.js"></script>
</body>
</html>

