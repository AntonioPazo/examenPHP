<?php
require_once 'Dulces.php';
require_once 'Tarta.php';
require_once 'Bollo.php';
require_once 'Chocolate.php';
require_once 'Cliente.php';
require_once 'ConexionDB.php';
require_once '../util/ClienteNoEncontradoException.php';
require_once '../util/DulceNoCompradoException.php';
require_once '../util/DulceNoEncontradoException.php';

class Pasteleria {
    private $nombre;
    private $conexion;

    public function __construct($nombre) {
        $this->nombre = $nombre;
        $this->conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
    }

    private function redimensionarImagen($imagenOriginal, $ancho = 200, $alto = 150) {
        // Obtener las dimensiones y tipo de la imagen original
        $imagenInfo = getimagesizefromstring($imagenOriginal);
        if ($imagenInfo === false) {
            throw new Exception("No se pudo obtener información de la imagen");
        }
    
        $anchoOriginal = $imagenInfo[0];
        $altoOriginal = $imagenInfo[1];
        $tipoOriginal = $imagenInfo[2];
    
        // Calcular las nuevas dimensiones manteniendo la proporción
        $ratio = $anchoOriginal / $altoOriginal;
        if ($ancho / $alto > $ratio) {
            $ancho = $alto * $ratio;
        } else {
            $alto = $ancho / $ratio;
        }
    
        // Crear una nueva imagen con las dimensiones deseadas
        $nuevaImagen = imagecreatetruecolor($ancho, $alto);
    
        // Manejar la transparencia para imágenes PNG
        if ($tipoOriginal == IMAGETYPE_PNG) {
            imagealphablending($nuevaImagen, false);
            imagesavealpha($nuevaImagen, true);
            $transparent = imagecolorallocatealpha($nuevaImagen, 255, 255, 255, 127);
            imagefilledrectangle($nuevaImagen, 0, 0, $ancho, $alto, $transparent);
        }
    
        // Crear una imagen desde la cadena original
        $imagenFuente = imagecreatefromstring($imagenOriginal);
    
        // Redimensionar la imagen
        imagecopyresampled($nuevaImagen, $imagenFuente, 0, 0, 0, 0, $ancho, $alto, $anchoOriginal, $altoOriginal);
    
        // Iniciar el buffer de salida
        ob_start();
    
        // Guardar la imagen redimensionada en el buffer
        switch ($tipoOriginal) {
            case IMAGETYPE_JPEG:
                imagejpeg($nuevaImagen, null, 100);
                break;
            case IMAGETYPE_PNG:
                imagepng($nuevaImagen);
                break;
            case IMAGETYPE_GIF:
                imagegif($nuevaImagen);
                break;
            default:
                throw new Exception("Tipo de imagen no soportado");
        }
    
        // Obtener el contenido del buffer
        $imagenRedimensionada = ob_get_contents();
    
        // Limpiar el buffer
        ob_end_clean();
    
        // Liberar memoria
        imagedestroy($nuevaImagen);
        imagedestroy($imagenFuente);
    
        return $imagenRedimensionada;
    }
    
    

    private function incluirProducto(Dulces $producto) {
        $imagenOriginal = $producto->getImagen();
        $imagenRedimensionada = $this->redimensionarImagen($imagenOriginal);

        $stmt = $this->conexion->prepare("
            INSERT INTO productos (nombre, precio, tipo, descripcion, imagen, num_pisos, rellenos, min_comensales, max_comensales, relleno, porcentaje_cacao, peso)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $producto->nombre,
            $producto->getPrecioConIVA(),
            $producto->getCategoria(),
            $producto->getDescripcion(),
            $imagenRedimensionada,
            $producto instanceof Tarta ? $producto->getNumPisos() : null,
            $producto instanceof Tarta ? implode(',', $producto->getRellenos()) : null,
            $producto instanceof Tarta ? $producto->getMinComensales() : null,
            $producto instanceof Tarta ? $producto->getMaxComensales() : null,
            $producto instanceof Bollo ? $producto->getRelleno() : null,
            $producto instanceof Chocolate ? $producto->getPorcentajeCacao() : null,
            $producto instanceof Chocolate ? $producto->getPeso() : null
        ]);
        echo "{$producto->nombre} incluido en la base de datos.\n";
    }

    public function incluirTarta($nombre, $precio, $descripcion, $imagen, $numPisos, $rellenos, $minComensales, $maxComensales) {
        $tarta = new Tarta($nombre, $precio, $descripcion, $imagen, $rellenos, $numPisos, $minComensales, $maxComensales);
        $this->incluirProducto($tarta);
    }

    public function incluirBollo($nombre, $precio, $descripcion, $imagen, $relleno) {
        $bollo = new Bollo($nombre, $precio, $descripcion, $imagen, $relleno);
        $this->incluirProducto($bollo);
    }

    public function incluirChocolate($nombre, $precio, $descripcion, $imagen, $porcentajeCacao, $peso) {
        $chocolate = new Chocolate($nombre, $precio, $descripcion, $imagen, $porcentajeCacao, $peso);
        $this->incluirProducto($chocolate);
    }

    public function incluirCliente($nombre, $usuario = null, $password = null) {
        try {
            // Verificar si el usuario ya existe
            $stmt = $this->conexion->prepare("SELECT id FROM clientes WHERE usuario = ?");
            $stmt->execute([$usuario]);
            if ($stmt->fetch()) {
                throw new Exception("El nombre de usuario ya está en uso.");
            }

            $cliente = new Cliente($nombre, null, 0, $usuario, $password);
            $cliente->guardar();
            return "Cliente incluido: $nombre" . ($usuario ? " (Usuario: $usuario)" : "");
        } catch (PDOException $e) {
            throw new Exception("Error al incluir el cliente en la base de datos.");
        }
    }

    public function listarProductos() {
        $stmt = $this->conexion->query("SELECT nombre, tipo, descripcion FROM productos");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Productos disponibles en {$this->nombre}:<br><br>";
        foreach ($productos as $producto) {
            echo "- {$producto['nombre']} ({$producto['tipo']}): {$producto['descripcion']}<br>";
        }
    }

    public function obtenerTodosLosProductos() {
        $stmt = $this->conexion->query("SELECT id, nombre, tipo, precio, descripcion, imagen FROM productos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarClientes() {
        $stmt = $this->conexion->query("SELECT id, nombre, num_pedidos_efectuados, usuario FROM clientes");
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table>";
        echo "<tr><th>Nombre</th><th>Usuario</th><th>Pedidos Efectuados</th><th>Rol</th><th>Acciones</th></tr>";
        foreach ($clientes as $cliente) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($cliente['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['num_pedidos_efectuados']) . "</td>";
            echo "<td>" . ($cliente['usuario'] === 'admin' ? 'Administrador' : 'Usuario') . "</td>";
            echo "<td>";
            if ($cliente['usuario'] !== 'admin') {
                echo "<a href='formUpdateCliente.php?id=" . $cliente['id'] . "'>Editar</a> | ";
                echo "<a href='#' onclick='confirmarEliminacion(" . $cliente['id'] . ")'>Eliminar</a>";
            } else {
                echo "N/A";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public function comprarClienteProducto($clienteId, $productoId, $cantidad) {
        try {
            $stmtCliente = $this->conexion->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmtCliente->execute([$clienteId]);
            $clienteData = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            $stmtProducto = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $stmtProducto->execute([$productoId]);
            $productoData = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if (!$clienteData) {
                throw new ClienteNoEncontradoException("Cliente con ID $clienteId no encontrado.");
            }
            if (!$productoData) {
                throw new DulceNoEncontradoException("Producto con ID $productoId no encontrado.");
            }

            $cliente = new Cliente($clienteData['nombre'], $clienteData['id'], $clienteData['num_pedidos_efectuados']);
            $producto = $this->crearProductoDesdeDB($productoData);

            $stmtCompra = $this->conexion->prepare("INSERT INTO compras (cliente_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmtCompra->execute([$clienteId, $productoId, $cantidad]);

            $cliente->incrementarPedidos();
            $cliente->guardar();

            echo "{$cliente->nombre} ha comprado {$cantidad} unidad(es) de {$producto->nombre}.\n";
        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage() . "\n";
        }
    }

    public function valorarClienteProducto($clienteId, $productoId, $comentario) {
        try {
            $stmtCliente = $this->conexion->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmtCliente->execute([$clienteId]);
            $clienteData = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            $stmtProducto = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $stmtProducto->execute([$productoId]);
            $productoData = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if (!$clienteData) {
                throw new ClienteNoEncontradoException("Cliente con ID $clienteId no encontrado.");
            }
            if (!$productoData) {
                throw new DulceNoEncontradoException("Producto con ID $productoId no encontrado.");
            }

            $cliente = new Cliente($clienteData['nombre'], $clienteData['id'], $clienteData['num_pedidos_efectuados']);
            $producto = $this->crearProductoDesdeDB($productoData);

            // Insertar la valoración en la base de datos
            $stmtValoracion = $this->conexion->prepare("INSERT INTO valoraciones (cliente_id, producto_id, comentario) VALUES (?, ?, ?)");
            $stmtValoracion->execute([$clienteId, $productoId, $comentario]);

            echo "Valoración registrada correctamente para el producto {$producto->nombre} por el cliente {$cliente->nombre}.\n";

        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage() . "\n";
        }
    }

    public function listarPedidosCliente($clienteId) {
        try {
            $stmtCliente = $this->conexion->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmtCliente->execute([$clienteId]);
            $clienteData = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if (!$clienteData) {
                throw new ClienteNoEncontradoException("Cliente con ID $clienteId no encontrado.");
            }

            $stmtPedidos = $this->conexion->prepare("
                SELECT c.id as compra_id, p.id as producto_id, p.nombre, p.tipo, c.fecha_compra 
                FROM compras c 
                JOIN productos p ON c.producto_id = p.id 
                WHERE c.cliente_id = ?
                ORDER BY c.fecha_compra DESC
            ");
            $stmtPedidos->execute([$clienteId]);
            $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

            if (count($pedidos) == 0) {
                echo "<p>No has realizado ninguna compra aún.</p>";
            } else {
                echo "<table>";
                echo "<tr><th>Producto</th><th>Tipo</th><th>Fecha de Compra</th><th>Acciones</th></tr>";
                foreach ($pedidos as $pedido) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($pedido['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($pedido['tipo']) . "</td>";
                    echo "<td>" . htmlspecialchars($pedido['fecha_compra']) . "</td>";
                    echo "<td><a href='valorar_producto.php?compra_id=" . $pedido['compra_id'] . "&producto_id=" . $pedido['producto_id'] . "'>Valorar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

        } catch (ClienteNoEncontradoException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        } catch (PDOException $e) {
            echo "<p>Error en la base de datos: " . $e->getMessage() . "</p>";
        }
    }

    private function crearProductoDesdeDB($productoData) {
        switch ($productoData['tipo']) {
            case 'Tarta':
    return new Tarta(
        $productoData['nombre'],
        $productoData['id'], // Añade este argumento si falta
        $productoData['precio'],
        $productoData['descripcion'],
        $productoData['imagen'],
        explode(',', $productoData['rellenos']),
        $productoData['num_pisos'],
        $productoData['min_comensales'],
        $productoData['max_comensales']
    );
            case 'Bollo':
                return new Bollo($productoData['nombre'],$productoData['id'], $productoData['precio'],
                                 $productoData['descripcion'], $productoData['imagen'], $productoData['relleno']);
            case 'Chocolate':
                return new Chocolate($productoData['nombre'],$productoData['id'], $productoData['precio'],
                                     $productoData['descripcion'], $productoData['imagen'], $productoData['porcentaje_cacao'],
                                     $productoData['peso']);
            default:
                throw new Exception("Tipo de producto desconocido");
        }
    }

    public function obtenerCliente($clienteId) {
        $stmt = $this->conexion->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$clienteId]);
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$clienteData) {
            return null;
        }

        return new Cliente($clienteData['nombre'], $clienteData['id'], $clienteData['num_pedidos_efectuados'], $clienteData['usuario']);
    }

    public function actualizarCliente($clienteId, $nombre, $usuario, $password = null) {
        $cliente = $this->obtenerCliente($clienteId);
        if (!$cliente) {
            throw new Exception("Cliente no encontrado.");
        }

        // Verificar si el nuevo nombre de usuario ya está en uso por otro cliente
        $stmt = $this->conexion->prepare("SELECT id FROM clientes WHERE usuario = ? AND id != ?");
        $stmt->execute([$usuario, $clienteId]);
        if ($stmt->fetch()) {
            throw new Exception("El nombre de usuario ya está en uso.");
        }

        $cliente->nombre = $nombre;
        $cliente->setUsuario($usuario);
        if (!empty($password)) {
            $cliente->setPassword($password);
        }

        $cliente->guardar();
    }

    public function eliminarCliente($clienteId) {
    try {
        $this->conexion->beginTransaction();

        // Actualizar valoraciones asociadas (establecer cliente_id a NULL)
        $stmtValoraciones = $this->conexion->prepare("UPDATE valoraciones SET cliente_id = NULL WHERE cliente_id = ?");
        $stmtValoraciones->execute([$clienteId]);

        // Actualizar compras asociadas (establecer cliente_id a NULL)
        $stmtCompras = $this->conexion->prepare("UPDATE compras SET cliente_id = NULL WHERE cliente_id = ?");
        $stmtCompras->execute([$clienteId]);

        // Eliminar el cliente
        $stmtCliente = $this->conexion->prepare("DELETE FROM clientes WHERE id = ? AND usuario != 'admin'");
        $stmtCliente->execute([$clienteId]);

        if ($stmtCliente->rowCount() === 0) {
            throw new Exception("No se pudo eliminar el cliente. Puede que no exista o sea un administrador.");
        }

        $this->conexion->commit();
    } catch (Exception $e) {
        $this->conexion->rollBack();
        throw new Exception("Error al eliminar el cliente: " . $e->getMessage());
    }
}

    public function obtenerDetallesProducto($productoId) {
        $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$productoId]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new DulceNoEncontradoException("Producto con ID $productoId no encontrado.");
        }

        return $producto;
    }

    
}
?>

