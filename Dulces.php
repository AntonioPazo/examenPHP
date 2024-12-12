<?php
require_once 'Resumible.php';

// Clase Dulces ahora implementa el interfaz Resumible
abstract class Dulces implements Resumible {
    public $nombre; // Nombre del dulce
    protected $numero; // Número de identificación
    private $precio; // Precio del dulce
    private static $IVA = 0.21; // IVA constante

    // Constructor
    public function __construct($nombre, $numero, $precio) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    // Getter para el precio con IVA
    public function getPrecioConIVA() {
        return $this->precio * (1 + self::$IVA);
    }

    // Método abstracto de muestraResumen (implementación obligatoria por el interfaz)
    public abstract function muestraResumen();

    // Método abstracto adicional
    public abstract function muestraTipo();

    // Getter para el número
    public function getNumero() {
        return $this->numero;
    }
}
?>

