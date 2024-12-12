<?php
require_once 'PasteleriaException.php';
class ClienteNoEncontradoException extends PasteleriaException {
    public function __construct($message = "El cliente no fue encontrado.") {
        parent::__construct($message);
    }
}
?>