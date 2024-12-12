<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");
$clienteId = $_SESSION['cliente_id'];

$action = $_POST['action'] ?? '';
$productoId = $_POST['producto_id'] ?? '';
$cantidad = $_POST['cantidad'] ?? 1;

switch ($action) {
    case 'obtener':
        $carrito = $pasteleria->obtenerCarrito($clienteId);
        echo json_encode($carrito);
        break;
    case 'agregar':
        $pasteleria->agregarAlCarrito($clienteId, $productoId, $cantidad);
        echo json_encode(['success' => true]);
        break;
    case 'actualizar':
        $pasteleria->actualizarCarrito($clienteId, $productoId, $cantidad);
        echo json_encode(['success' => true]);
        break;
    case 'eliminar':
        $pasteleria->eliminarDelCarrito($clienteId, $productoId);
        echo json_encode(['success' => true]);
        break;
    case 'vaciar':
        $pasteleria->vaciarCarrito($clienteId);
        echo json_encode(['success' => true]);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
}

