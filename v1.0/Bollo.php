<?php
require_once 'Dulces.php';

class Bollo extends Dulces {
    // Nuevo atributo
    private $relleno;

    // Constructor
    public function __construct($nombre, $numero, $precio, $relleno) {
        parent::__construct($nombre, $numero, $precio);
        $this->relleno = $relleno;
    }

    // Sobrescritura del método muestraResumen
    public function muestraResumen() {
        echo "Nombre del Bollo: " . $this->nombre . PHP_EOL;
        echo "Número de Unidades: " . $this->numero . PHP_EOL;
        echo "Relleno: " . $this->relleno . PHP_EOL;
        echo "Precio sin IVA: " . number_format($this->getPrecio(), 2) . "€" . PHP_EOL;
        echo "Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . "€" . PHP_EOL;
    }
}
?>
