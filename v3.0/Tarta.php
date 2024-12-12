<?php
require_once 'Dulces.php';

class Tarta extends Dulces {
    private $rellenos;
    private $numPisos;
    private $minNumComensales;
    private $maxNumComensales;

    public function __construct($nombre, $numero, $precio, $rellenos, $numPisos, $minNumComensales = 2, $maxNumComensales) {
        parent::__construct($nombre, $numero, $precio);
        $this->rellenos = $rellenos;
        $this->numPisos = $numPisos;
        $this->minNumComensales = $minNumComensales;
        $this->maxNumComensales = $maxNumComensales;
    }

    // Sobrescribir muestraResumen
    public function muestraResumen() {
        parent::muestraResumen();
        echo "Número de Pisos: {$this->numPisos}\n";
        echo "Rellenos: " . implode(", ", $this->rellenos) . "\n";
        echo "Comensales posibles: ";
        $this->muestraComensalesPosibles();
    }

    // Implementación de muestraTipo
    public function muestraTipo() {
        echo "Tipo: Tarta\n";
    }

    // Método adicional
    public function muestraComensalesPosibles() {
        if ($this->minNumComensales === $this->maxNumComensales) {
            echo "Para {$this->minNumComensales} comensales.\n";
        } else {
            echo "De {$this->minNumComensales} a {$this->maxNumComensales} comensales.\n";
        }
    }

    // Getters adicionales
    public function getRellenos() {
        return $this->rellenos;
    }

    public function getNumPisos() {
        return $this->numPisos;
    }

    public function getMinComensales() {
        return $this->minNumComensales;
    }

    public function getMaxComensales() {
        return $this->maxNumComensales;
    }
}
?>

