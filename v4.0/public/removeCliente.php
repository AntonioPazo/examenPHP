<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $clienteId = $_GET['id'];
    $pasteleria = new Pasteleria("Mi Pastelería");

    try {
        $pasteleria->eliminarCliente($clienteId);
        $_SESSION['success'] = "Cliente eliminado exitosamente.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al eliminar el cliente: " . $e->getMessage();
    }
}

header("Location: mainAdmin.php");
exit();

