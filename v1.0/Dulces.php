<?php
class Dulces {
    // Atributos
    public $nombre;
    protected $numero;
    private $precio;

    // Constante para el IVA
    private static $IVA = 0.21;

    // Constructor
    public function __construct($nombre, $numero, $precio) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    // Método para calcular el precio con IVA
    public function getPrecioConIVA() {
        return $this->precio * (1 + self::$IVA);
    }

    // Método para mostrar un resumen del dulce
    public function muestraResumen() {
        echo "Nombre: " . $this->nombre . PHP_EOL;
        echo "Número: " . $this->numero . PHP_EOL;
        echo "Precio sin IVA: " . number_format($this->precio, 2) . "€" . PHP_EOL;
        echo "Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . "€" . PHP_EOL;
    }

    // Getter para obtener el precio
    protected function getPrecio() {
        return $this->precio;
    }
}
?>
