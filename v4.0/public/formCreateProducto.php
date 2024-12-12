<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    // Handle image upload
    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    try {
        switch ($tipo) {
            case 'Tarta':
                $numPisos = $_POST['num_pisos'] ?? '';
                $rellenos = $_POST['rellenos'] ?? '';
                $minComensales = $_POST['min_comensales'] ?? '';
                $maxComensales = $_POST['max_comensales'] ?? '';
                $pasteleria->incluirTarta($nombre, $precio, $descripcion, $imagen, $numPisos, explode(',', $rellenos), $minComensales, $maxComensales);
                break;
            case 'Bollo':
                $relleno = $_POST['relleno'] ?? '';
                $pasteleria->incluirBollo($nombre, $precio, $descripcion, $imagen, $relleno);
                break;
            case 'Chocolate':
                $porcentajeCacao = $_POST['porcentaje_cacao'] ?? '';
                $peso = $_POST['peso'] ?? '';
                $pasteleria->incluirChocolate($nombre, $precio, $descripcion, $imagen, $porcentajeCacao, $peso);
                break;
            default:
                throw new Exception("Tipo de producto no válido");
        }
        $_SESSION['success'] = "Producto creado con éxito.";
        header("Location: mainAdmin.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al crear el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Producto</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            // Check if the success message is related to product creation
            if ($_SESSION['success'] === "Producto creado con éxito.") {
            }
            unset($_SESSION['success']);
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="imagen">Imagen del producto:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
            <img id="image-preview" class="image-preview" src="" alt="Vista previa de la imagen" style="display: none;">

            <label for="tipo">Tipo de Producto:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="Tarta">Tarta</option>
                <option value="Bollo">Bollo</option>
                <option value="Chocolate">Chocolate</option>
            </select>

            <div id="campos-adicionales"></div>

            <button type="submit">Crear Producto</button>
        </form>
        <p class="volver"><a href="mainAdmin.php">Volver al Panel de Administración</a></p>

    </div>

    <script>
        document.getElementById('tipo').addEventListener('change', function() {
            const camposAdicionales = document.getElementById('campos-adicionales');
            camposAdicionales.innerHTML = '';

            switch(this.value) {
                case 'Tarta':
                    camposAdicionales.innerHTML = `
                        <label for="num_pisos">Número de Pisos:</label>
                        <input type="number" id="num_pisos" name="num_pisos" required>
                        <br/>
                        <label for="rellenos">Rellenos (separados por coma):</label>
                        <input type="text" id="rellenos" name="rellenos" required>
                        <br/>
                        <label for="min_comensales">Mínimo de Comensales:</label>
                        <input type="number" id="min_comensales" name="min_comensales" required>
                        <br/>
                        <label for="max_comensales">Máximo de Comensales:</label>
                        <input type="number" id="max_comensales" name="max_comensales" required>
                    `;
                    break;
                case 'Bollo':
                    camposAdicionales.innerHTML = `
                        <label for="relleno">Relleno:</label>
                        <input type="text" id="relleno" name="relleno" required>
                    `;
                    break;
                case 'Chocolate':
                    camposAdicionales.innerHTML = `
                        <label for="porcentaje_cacao">Porcentaje de Cacao:</label>
                        <input type="number" id="porcentaje_cacao" name="porcentaje_cacao" required>
                        <br/>
                        <label for="peso">Peso (en gramos):</label>
                        <input type="number" id="peso" name="peso" required>
                    `;
                    break;
            }
        });

        // Image preview
        document.getElementById('imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>

