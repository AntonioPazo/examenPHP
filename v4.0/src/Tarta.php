<?php
require_once 'Dulces.php';

class Tarta extends Dulces {
    private $rellenos;
    private $numPisos;
    private $minComensales;
    private $maxComensales;

    public function __construct($nombre, $precio, $descripcion, $imagen, $rellenos, $numPisos, $minComensales, $maxComensales) {
        parent::__construct($nombre, $precio, $descripcion, $imagen);
        $this->rellenos = $rellenos;
        $this->numPisos = $numPisos;
        $this->minComensales = $minComensales;
        $this->maxComensales = $maxComensales;
    }

    public function getRellenos() {
        return $this->rellenos;
    }

    public function getNumPisos() {
        return $this->numPisos;
    }

    public function getMinComensales() {
        return $this->minComensales;
    }

    public function getMaxComensales() {
        return $this->maxComensales;
    }

    public function getCategoria() {
        return "Tarta";
    }

    public function getId() { // Added getId method
        return $this->numero;
    }
}
?>

