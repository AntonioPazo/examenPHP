<?php
require_once 'Dulces.php';
require_once 'Tarta.php';
require_once 'Bollo.php';
require_once 'Chocolate.php';
require_once 'Cliente.php';
require_once 'ClienteNoEncontradoException.php';
require_once 'DulceNoCompradoException.php';
require_once 'DulceNoEncontradoException.php';

class Pasteleria {
    // Atributos
    private $nombre; // Nombre de la pastelería
    private $productos = []; // Array de dulces disponibles
    private $numProductos = 0; // Número total de productos
    private $clientes = []; // Array de clientes
    private $numClientes = 0; // Número total de clientes

    // Constructor
    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    // Método privado para incluir un producto al array de productos
    private function incluirProducto(Dulces $producto) {
        $this->productos[] = $producto;
        $this->numProductos++;
    }

    // Métodos públicos para añadir productos específicos
    public function incluirTarta($nombre, $numero, $precio, $numPisos, $rellenos, $minComensales, $maxComensales) {
        $tarta = new Tarta($nombre, $numero, $precio, $rellenos, $numPisos, $minComensales, $maxComensales);
        $this->incluirProducto($tarta);
        echo "Tarta incluida: $nombre\n";
    }

    public function incluirBollo($nombre, $numero, $precio, $relleno) {
        $bollo = new Bollo($nombre, $numero, $precio, $relleno);
        $this->incluirProducto($bollo);
        echo "Bollo incluido: $nombre\n";
    }

    public function incluirChocolate($nombre, $numero, $precio, $porcentajeCacao, $peso) {
        $chocolate = new Chocolate($nombre, $numero, $precio, $porcentajeCacao, $peso);
        $this->incluirProducto($chocolate);
        echo "Chocolate incluido: $nombre\n";
    }

    // Método para incluir un cliente
    public function incluirCliente($nombre) {
        $cliente = new Cliente($nombre, $this->numClientes + 1);
        $this->clientes[] = $cliente;
        $this->numClientes++;
        echo "Cliente incluido: $nombre\n";
    }

    // Método para listar productos
    public function listarProductos() {
        echo "Productos disponibles en {$this->nombre}:\n";
        foreach ($this->productos as $producto) {
            echo "- {$producto->nombre}\n";
        }
    }

    // Método para listar clientes
    public function listarClientes() {
        echo "Clientes registrados en {$this->nombre}:\n";
        foreach ($this->clientes as $cliente) {
            echo "- {$cliente->nombre}\n";
        }
    }

    // Método para que un cliente compre un producto
    public function comprarClienteProducto($numeroCliente, $numeroDulce) {
        try {
            if (!isset($this->clientes[$numeroCliente - 1])) {
                throw new ClienteNoEncontradoException("Cliente con número $numeroCliente no encontrado.");
            }
            if (!isset($this->productos[$numeroDulce - 1])) {
                throw new DulceNoEncontradoException("Producto con número $numeroDulce no encontrado.");
            }

            $cliente = $this->clientes[$numeroCliente - 1];
            $producto = $this->productos[$numeroDulce - 1];
            $cliente->comprar($producto);

            echo "{$cliente->nombre} ha comprado {$producto->nombre}.\n";
        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PasteleriaException $e) {
            echo "Error en la pastelería: " . $e->getMessage() . "\n";
        }
    }

    // Nuevo método para que un cliente valore un producto
    public function valorarClienteProducto($numeroCliente, $numeroDulce, $comentario) {
        try {
            if (!isset($this->clientes[$numeroCliente - 1])) {
                throw new ClienteNoEncontradoException("Cliente con número $numeroCliente no encontrado.");
            }
            if (!isset($this->productos[$numeroDulce - 1])) {
                throw new DulceNoEncontradoException("Producto con número $numeroDulce no encontrado.");
            }

            $cliente = $this->clientes[$numeroCliente - 1];
            $producto = $this->productos[$numeroDulce - 1];
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

    // Nuevo método para listar los pedidos de un cliente
    public function listarPedidosCliente($numeroCliente) {
        try {
            if (!isset($this->clientes[$numeroCliente - 1])) {
                throw new ClienteNoEncontradoException("Cliente con número $numeroCliente no encontrado.");
            }

            $cliente = $this->clientes[$numeroCliente - 1];
            $cliente->listarPedidos();

        } catch (ClienteNoEncontradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (DulceNoCompradoException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (PasteleriaException $e) {
            echo "Error en la pastelería: " . $e->getMessage() . "\n";
        }
    }
}
?>