<?php
require_once 'Dulces.php';

class Bollo extends Dulces {
    private $relleno; // Relleno del bollo

    public function __construct($nombre, $numero, $precio, $relleno) {
        parent::__construct($nombre, $numero, $precio);
        $this->relleno = $relleno;
    }

    // Sobrescribir muestraResumen
    public function muestraResumen() {
        parent::muestraResumen();
        echo "Relleno: {$this->relleno}\n";
    }

    // Implementación de muestraTipo
    public function muestraTipo() {
        echo "Tipo: Bollo\n";
    }
}
?>
