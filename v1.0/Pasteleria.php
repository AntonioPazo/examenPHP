<?php
require_once 'Dulces.php';
require_once 'Tarta.php';
require_once 'Bollo.php';
require_once 'Chocolate.php';
require_once 'Cliente.php';

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
        if (!isset($this->clientes[$numeroCliente - 1])) {
            echo "Error: Cliente con número $numeroCliente no encontrado.\n";
            return;
        }
        if (!isset($this->productos[$numeroDulce - 1])) {
            echo "Error: Producto con número $numeroDulce no encontrado.\n";
            return;
        }

        $cliente = $this->clientes[$numeroCliente - 1];
        $producto = $this->productos[$numeroDulce - 1];
        $cliente->comprar($producto);

        echo "{$cliente->nombre} ha comprado {$producto->nombre}.\n";
    }
}
?>
