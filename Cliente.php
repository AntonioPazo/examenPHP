<?php
require_once 'ClienteNoEncontradoException.php';
require_once 'DulceNoCompradoException.php';
require_once 'DulceNoEncontradoException.php';
require_once 'ConexionDB.php';

class Cliente {
    // Atributos
    public $id;
    public $nombre;
    private $numPedidosEfectuados;
    private $conexion;

    // Constructor
    public function __construct($nombre, $id = null, $numPedidosEfectuados = 0) {
        $this->nombre = $nombre;
        $this->id = $id;
        $this->numPedidosEfectuados = $numPedidosEfectuados;
        $this->conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
    }

    // Método para guardar o actualizar el cliente en la base de datos
    public function guardar() {
        if ($this->id === null) {
            // Nuevo cliente
            $stmt = $this->conexion->prepare("INSERT INTO clientes (nombre, num_pedidos_efectuados) VALUES (?, ?)");
            $stmt->execute([$this->nombre, $this->numPedidosEfectuados]);
            $this->id = $this->conexion->lastInsertId();
        } else {
            // Actualizar cliente existente
            $stmt = $this->conexion->prepare("UPDATE clientes SET nombre = ?, num_pedidos_efectuados = ? WHERE id = ?");
            $stmt->execute([$this->nombre, $this->numPedidosEfectuados, $this->id]);
        }
    }

    // Método para comprar un dulce
    public function comprar(Dulces $d) {
        $stmt = $this->conexion->prepare("INSERT INTO compras (cliente_id, producto_id) VALUES (?, ?)");
        $stmt->execute([$this->id, $d->getNumero()]);
        $this->numPedidosEfectuados++;
        $this->guardar(); // Actualizar el número de pedidos en la base de datos
        return $this;
    }

    // Método para valorar un dulce
    public function valorar(Dulces $d, $comentario) {
        $stmt = $this->conexion->prepare("INSERT INTO valoraciones (cliente_id, producto_id, comentario) VALUES (?, ?, ?)");
        $stmt->execute([$this->id, $d->getNumero(), $comentario]);
        echo "Comentario sobre el dulce '{$d->nombre}': " . $comentario . PHP_EOL;
        return $this;
    }

    // Método para listar todos los pedidos
    public function listarPedidos() {
        $stmt = $this->conexion->prepare("
            SELECT p.nombre, c.fecha_compra 
            FROM compras c 
            JOIN productos p ON c.producto_id = p.id 
            WHERE c.cliente_id = ?
            ORDER BY c.fecha_compra DESC
        ");
        $stmt->execute([$this->id]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($pedidos) == 0) {
            throw new DulceNoCompradoException("Este cliente no ha realizado ningún pedido.");
        }

        echo "Número total de pedidos: " . count($pedidos) . PHP_EOL;
        echo "Lista de dulces comprados:" . PHP_EOL;
        foreach ($pedidos as $pedido) {
            echo "- {$pedido['nombre']} (Comprado el: {$pedido['fecha_compra']})" . PHP_EOL;
        }
        return $this;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNumPedidosEfectuados() {
        return $this->numPedidosEfectuados;
    }

    public function incrementarPedidos() {
        $this->numPedidosEfectuados++;
    }
}
?>

