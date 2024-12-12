<?php
require_once 'PasteleriaException.php';
class DulceNoEncontradoException extends PasteleriaException {
    public function __construct($message = "El dulce no fue encontrado.") {
        parent::__construct($message);
    }
}
?>