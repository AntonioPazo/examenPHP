<?php
require_once 'Dulces.php';

class Tarta extends Dulces {
    // Atributos privados
    private $rellenos; // Array que contiene los rellenos de cada piso
    private $numPisos;
    private $minNumComensales;
    private $maxNumComensales;

    // Constructor
    public function __construct(
        $nombre,
        $numero,
        $precio,
        $rellenos,
        $numPisos,
        $minNumComensales = 2,
        $maxNumComensales = null
    ) {
        parent::__construct($nombre, $numero, $precio);
        $this->rellenos = $rellenos;
        $this->numPisos = $numPisos;
        $this->minNumComensales = $minNumComensales;
        $this->maxNumComensales = $maxNumComensales;
    }

    // Método para mostrar los comensales posibles
    public function muestraComensalesPosibles() {
        if ($this->minNumComensales == $this->maxNumComensales || $this->maxNumComensales === null) {
            echo "Para " . $this->minNumComensales . " comensales" . PHP_EOL;
        } else {
            echo "De " . $this->minNumComensales . " a " . $this->maxNumComensales . " comensales" . PHP_EOL;
        }
    }

    // Sobrescritura del método muestraResumen
    public function muestraResumen() {
        echo "Nombre de la Tarta: " . $this->nombre . PHP_EOL;
        echo "Número de Tartas: " . $this->numero . PHP_EOL;
        echo "Número de Pisos: " . $this->numPisos . PHP_EOL;
        echo "Rellenos: " . implode(", ", $this->rellenos) . PHP_EOL;
        echo "Precio sin IVA: " . number_format($this->getPrecio(), 2) . "€" . PHP_EOL;
        echo "Precio con IVA: " . number_format($this->getPrecioConIVA(), 2) . "€" . PHP_EOL;
        $this->muestraComensalesPosibles();
    }
}
?>
