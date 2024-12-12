<?php
require_once 'Dulces.php';

class Chocolate extends Dulces {
    private $porcentajeCacao;
    private $peso;

    public function __construct($nombre, $numero, $precio, $porcentajeCacao, $peso) {
        parent::__construct($nombre, $numero, $precio);
        $this->porcentajeCacao = $porcentajeCacao;
        $this->peso = $peso;
    }

    // Sobrescribir muestraResumen
    public function muestraResumen() {
        parent::muestraResumen();
        echo "Porcentaje de Cacao: {$this->porcentajeCacao}%\n";
        echo "Peso: {$this->peso}g\n";
    }

    // Implementación de muestraTipo
    public function muestraTipo() {
        echo "Tipo: Chocolate\n";
    }
}
?>
