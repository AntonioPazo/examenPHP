<?php
require_once 'Dulces.php';
require_once 'Tarta.php';
require_once 'Bollo.php';
require_once 'Chocolate.php';
require_once 'Cliente.php';
require_once 'ClienteNoEncontradoException.php';
require_once 'DulceNoCompradoException.php';
require_once 'DulceNoEncontradoException.php';
require_once 'ConexionDB.php';

class Pasteleria {
    // Atributos
    private $nombre;
    private $conexion;

    // Constructor
    public function __construct($nombre) {
        $this->nombre = $nombre;
        $this->conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
    }

    // Método para incluir un producto en la base de datos
    private function incluirProducto(Dulces $producto) {
        $stmt = $this->conexion->prepare("
            INSERT INTO productos (nombre, numero, precio, tipo, num_pisos, rellenos, min_comensales, max_comensales, relleno, porcentaje_cacao, peso)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $producto->nombre,
            $producto->getNumero(),
            $producto->getPrecioConIVA(),
            get_class($producto),
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

    // Métodos públicos para añadir productos específicos
    public function incluirTarta($nombre, $numero, $precio, $numPisos, $rellenos, $minComensales, $maxComensales) {
        $tarta = new Tarta($nombre, $numero, $precio, $rellenos, $numPisos, $minComensales, $maxComensales);
        $this->incluirProducto($tarta);
    }

    public function incluirBollo($nombre, $numero, $precio, $relleno) {
        $bollo = new Bollo($nombre, $numero, $precio, $relleno);
        $this->incluirProducto($bollo);
    }

    public function incluirChocolate($nombre, $numero, $precio, $porcentajeCacao, $peso) {
        $chocolate = new Chocolate($nombre, $numero, $precio, $porcentajeCacao, $peso);
        $this->incluirProducto($chocolate);
    }

    // Método para incluir un cliente
    public function incluirCliente($nombre) {
        $cliente = new Cliente($nombre);
        $cliente->guardar();
        echo "Cliente incluido: $nombre\n";
    }

    // Método para listar productos
    public function listarProductos() {
        $stmt = $this->conexion->query("SELECT nombre, tipo FROM productos");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Productos disponibles en {$this->nombre}:\n";
        foreach ($productos as $producto) {
            echo "- {$producto['nombre']} ({$producto['tipo']})\n";
        }
    }

    // Método para listar clientes
    public function listarClientes() {
        $stmt = $this->conexion->query("SELECT nombre FROM clientes");
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Clientes registrados en {$this->nombre}:\n";
        foreach ($clientes as $cliente) {
            echo "- {$cliente['nombre']}\n";
        }
    }

    // Método para que un cliente compre un producto
    public function comprarClienteProducto($clienteId, $productoId) {
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

            $stmtCompra = $this->conexion->prepare("INSERT INTO compras (cliente_id, producto_id) VALUES (?, ?)");
            $stmtCompra->execute([$clienteId, $productoId]);

            $cliente->incrementarPedidos();
            $cliente->guardar();

            echo "{$cliente->nombre} ha comprado {$producto->nombre}.\n";
        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage() . "\n";
        } catch (PasteleriaException $e) {
            echo "Error en la pastelería: " . $e->getMessage() . "\n";
        }
    }

    // Método para que un cliente valore un producto
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

            $cliente->valorar($producto, $comentario);

        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoCompradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PasteleriaException $e) {
            echo "Error en la pastelería: " . $e->getMessage() . "\n";
        }
    }

    // Método para listar los pedidos de un cliente
    public function listarPedidosCliente($clienteId) {
        try {
            $stmtCliente = $this->conexion->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmtCliente->execute([$clienteId]);
            $clienteData = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if (!$clienteData) {
                throw new ClienteNoEncontradoException("Cliente con ID $clienteId no encontrado.");
            }

            $cliente = new Cliente($clienteData['nombre'], $clienteData['id'], $clienteData['num_pedidos_efectuados']);
            $cliente->listarPedidos();

        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoCompradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PasteleriaException $e) {
            echo "Error en la pastelería: " . $e->getMessage() . "\n";
        }
    }

    // Método auxiliar para crear objetos de producto desde datos de la base de datos
    private function crearProductoDesdeDB($productoData) {
        switch ($productoData['tipo']) {
            case 'Tarta':
                return new Tarta($productoData['nombre'], $productoData['numero'], $productoData['precio'], 
                                 explode(',', $productoData['rellenos']), $productoData['num_pisos'], 
                                 $productoData['min_comensales'], $productoData['max_comensales']);
            case 'Bollo':
                return new Bollo($productoData['nombre'], $productoData['numero'], $productoData['precio'], 
                                 $productoData['relleno']);
            case 'Chocolate':
                return new Chocolate($productoData['nombre'], $productoData['numero'], $productoData['precio'], 
                                     $productoData['porcentaje_cacao'], $productoData['peso']);
            default:
                throw new Exception("Tipo de producto desconocido");
        }
    }
}
?>

