<?php
require_once 'Dulces.php';

class Bollo extends Dulces {
    private $relleno;

    public function __construct($nombre, $precio, $descripcion, $imagen, $relleno) {
        parent::__construct($nombre, $precio, $descripcion, $imagen);
        $this->relleno = $relleno;
    }

    public function getRelleno() {
        return $this->relleno;
    }

    public function getCategoria() {
        return "Bollo";
    }

    public function getId() { // Added getId method
        return $this->numero;
    }
}
?>

