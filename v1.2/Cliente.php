<?php
require_once 'ClienteNoEncontradoException.php';
require_once 'DulceNoCompradoException.php';
require_once 'DulceNoEncontradoException.php';
class Cliente {
    // Atributos
    public $nombre; // Nombre del cliente
    private $numero; // Número de identificación
    private $dulcesComprados = []; // Array de dulces comprados
    private $numDulcesComprados = 0; // Contador de dulces comprados
    private $numPedidosEfectuados = 0; // Contador de pedidos efectuados

    // Constructor
    public function __construct($nombre, $numero, $numPedidosEfectuados = 0) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->numPedidosEfectuados = $numPedidosEfectuados;
    }

    // Getters y Setters
    public function getNumero() {
        return $this->numero;
    }

    public function getNumPedidosEfectuados() {
        return $this->numPedidosEfectuados;
    }

    public function getNumDulcesComprados() {
        return $this->numDulcesComprados;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    // Método para mostrar el resumen del cliente
    public function muestraResumen() {
        echo "Nombre del Cliente: " . $this->nombre . PHP_EOL;
        echo "Número de Pedidos Efectuados: " . $this->numPedidosEfectuados . PHP_EOL;
    }

    // Método para comprobar si un dulce está en la lista de dulces comprados
    public function listaDeDulces(Dulces $d) {
        foreach ($this->dulcesComprados as $dulce) {
            if ($dulce === $d) {
                return true;
            }
        }
        throw new DulceNoCompradoException("El dulce {$d->nombre} no ha sido comprado por este cliente.");
    }

    // Método para comprar un dulce
    public function comprar(Dulces $d) {
        $this->dulcesComprados[] = $d;
        $this->numDulcesComprados++;
        $this->numPedidosEfectuados++;
        return $this;
    }

    // Método para valorar un dulce
    public function valorar(Dulces $d, $comentario) {
        if ($this->listaDeDulces($d)) {
            echo "Comentario sobre el dulce '{$d->nombre}': " . $comentario . PHP_EOL;
        }
        return $this;
    }

    // Método para listar todos los pedidos
    public function listarPedidos() {
        if ($this->numPedidosEfectuados == 0) {
            throw new DulceNoCompradoException("Este cliente no ha realizado ningún pedido.");
        }
        echo "Número total de pedidos: " . $this->numPedidosEfectuados . PHP_EOL;
        echo "Lista de dulces comprados:" . PHP_EOL;
        foreach ($this->dulcesComprados as $dulce) {
            echo "- " . $dulce->nombre . PHP_EOL;
        }
        return $this;
    }
}
?>

