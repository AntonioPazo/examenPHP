<?php
session_start();
require_once '../src/Pasteleria.php';

// Configurar el reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Función para registrar errores
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . "Error en procesar_pago.php: " . $message . "\n", 3, "error_log.txt");
}

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    logError("Usuario no autorizado");
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$pasteleria = new Pasteleria("Mi Pastelería");
$clienteId = $_SESSION['cliente_id'];

// Obtener los datos del carrito enviados desde el cliente
$inputJSON = file_get_contents('php://input');
$carrito = json_decode($inputJSON, true);

logError("Datos recibidos: " . print_r($carrito, true));

if (empty($carrito)) {
    logError("Carrito vacío");
    echo json_encode(['success' => false, 'error' => 'Carrito vacío']);
    exit();
}

try {
    // Iniciar una transacción
    $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
    $conexion->beginTransaction();

    // Insertar cada producto del carrito en la tabla de compras
    $stmt = $conexion->prepare("INSERT INTO compras (cliente_id, producto_id, cantidad) VALUES (?, ?, ?)");
    
    foreach ($carrito as $item) {
        $stmt->execute([$clienteId, $item['id'], $item['cantidad']]);
    }

    // Incrementar el número de pedidos del cliente
    $stmtCliente = $conexion->prepare("UPDATE clientes SET num_pedidos_efectuados = num_pedidos_efectuados + 1 WHERE id = ?");
    $stmtCliente->execute([$clienteId]);

    // Confirmar la transacción
    $conexion->commit();

    // Establecer un mensaje de éxito en la sesión
    $_SESSION['success'] = 'Compra realizada con éxito. ¡Gracias por tu pedido!';

    echo json_encode(['success' => true, 'message' => 'Compra realizada con éxito']);
} catch (Exception $e) {
    // Si algo sale mal, deshacer la transacción
    $conexion->rollBack();
    logError("Excepción: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al procesar la compra: ' . $e->getMessage()]);
}

