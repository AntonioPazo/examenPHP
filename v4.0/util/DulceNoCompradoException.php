<?php
require_once 'PasteleriaException.php';
class DulceNoCompradoException extends PasteleriaException {
    public function __construct($message = "El dulce no ha sido comprado.") {
        parent::__construct($message); // Llamamos al constructor de la clase base con el mensaje.
    }
}
?>