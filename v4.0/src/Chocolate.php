<?php
require_once 'Dulces.php';

class Chocolate extends Dulces {
    private $porcentajeCacao;
    private $peso;

    public function __construct($nombre, $precio, $descripcion, $imagen, $porcentajeCacao, $peso) {
        parent::__construct($nombre, $precio, $descripcion, $imagen);
        $this->porcentajeCacao = $porcentajeCacao;
        $this->peso = $peso;
    }

    public function getPorcentajeCacao() {
        return $this->porcentajeCacao;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function getCategoria() {
        return "Chocolate";
    }

    public function getId() { // Added getId method
        return $this->numero;
    }
}
?>

