<?php
abstract class Dulces {
    public $nombre;
    protected $precio;
    protected $descripcion;
    protected $imagen;

    public function __construct($nombre,  $precio, $descripcion, $imagen) {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }

    public function getPrecioConIVA() {
        return $this->precio * 1.21; // 21% IVA
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getImagen() {
        return $this->imagen;
    }

    abstract public function getCategoria();
}
?>

