<?php
require_once '../util/ClienteNoEncontradoException.php';
require_once '../util/DulceNoCompradoException.php';
require_once '../util/DulceNoEncontradoException.php';
require_once 'ConexionDB.php';

class Cliente {
    // Atributos
    public $id;
    public $nombre;
    private $numPedidosEfectuados;
    private $usuario;
    private $password;
    private $conexion;
    private $dulcesComprados; // Nuevo atributo

    // Constructor
    public function __construct($nombre, $id = null, $numPedidosEfectuados = 0, $usuario = null, $password = null) {
        $this->nombre = $nombre;
        $this->id = $id;
        $this->numPedidosEfectuados = $numPedidosEfectuados;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $this->dulcesComprados = []; // Inicializar el array
    }

    // Método para guardar o actualizar el cliente en la base de datos
    public function guardar() {
        if ($this->id === null) {
            // Nuevo cliente
            $stmt = $this->conexion->prepare("INSERT INTO clientes (nombre, num_pedidos_efectuados, usuario, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->nombre, $this->numPedidosEfectuados, $this->usuario, $this->password]);
            $this->id = $this->conexion->lastInsertId();
        } else {
            // Actualizar cliente existente
            $stmt = $this->conexion->prepare("UPDATE clientes SET nombre = ?, num_pedidos_efectuados = ?, usuario = ?, password = ? WHERE id = ?");
            $stmt->execute([$this->nombre, $this->numPedidosEfectuados, $this->usuario, $this->password, $this->id]);
        }
    }
    
    

    // Método para comprar un dulce
    public function comprar(Dulces $d) {
        $stmt = $this->conexion->prepare("INSERT INTO compras (cliente_id, producto_id) VALUES (?, ?)");
        $stmt->execute([$this->id, $d->getNumero()]);
        $this->numPedidosEfectuados++;
        $this->dulcesComprados[] = $d; // Añadir el dulce al array
        $this->guardar(); // Actualizar el número de pedidos en la base de datos
        return $this;
    }

    // Método para valorar un dulce
    public function valorar(Dulces $d, $comentario) {
        $stmt = $this->conexion->prepare("INSERT INTO valoraciones (cliente_id, producto_id, comentario) VALUES (?, ?, ?)");
        $stmt->execute([$this->id, $d->getId(), $comentario]);
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

    // Getters y setters

    public function getId() {
        return $this->id;
    }

    public function getNumPedidosEfectuados() {
        return $this->numPedidosEfectuados;
    }

    public function incrementarPedidos() {
        $this->numPedidosEfectuados++;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setPassword($password) {
        // Aquí podrías añadir lógica para hashear la contraseña antes de guardarla
        $this->password = $password;
    }

    // Nuevo método para obtener los dulces comprados
    public function getDulcesComprados() {
        return $this->dulcesComprados;
    }
}
?>

