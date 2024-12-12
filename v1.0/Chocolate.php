<?php
require_once 'Dulces.php';

class Chocolate extends Dulces {
    // Nuevos atributos
    private $porcentajeCacao;
    private $peso;

    // Constructor
    public function __construct($nombre, $numero, $precio, $porcentajeCacao, $peso) {
        parent::__construct($nombre, $numero, $precio);
        $this->porcentajeCacao = $porcentajeCacao;
        $this->peso = $peso;
    }

    // Sobrescritura del método muestraResumen
    public function muestraResumen() {
        echo "Nombre del Chocolate: " . $this->nombre . PHP_EOL;
        echo "Número de Unidades: " . $this->numero . PHP_EOL;
        echo "Porcentaje de Cacao: " . $this->porcentajeCacao . "%" . PHP_EOL;
        echo "Peso: " . $this->peso . "g" . PHP_EOL;
        echo "Precio sin IVA: " . number_format($this->getPrecio(), 2) . "€" . PHP_EOL;
        echo "Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . "€" . PHP_EOL;
    }
}
?>