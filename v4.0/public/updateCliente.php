<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar si el usuario tiene permiso para editar este cliente
    if ($_SESSION['role'] !== 'admin' && $clienteId != $_SESSION['cliente_id']) {
        $_SESSION['error'] = "No tienes permiso para editar este cliente.";
        header("Location: main.php");
        exit();
    }

    if (empty($clienteId) || empty($nombre) || empty($usuario) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios excepto la contraseña.";
        header("Location: formUpdateCliente.php?id=$clienteId");
        exit();
    }

    // Cifrar la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $pasteleria = new Pasteleria("Mi Pastelería");

    try {
        $pasteleria->actualizarCliente($clienteId, $nombre, $usuario, $hashedPassword);
        $_SESSION['success'] = "Datos actualizados exitosamente.";
        if ($_SESSION['role'] === 'admin') {
            header("Location: mainAdmin.php");
        } else {
            $_SESSION['user'] = $usuario; // Actualizar el nombre de usuario en la sesión
            header("Location: main.php");
        }
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al actualizar los datos: " . $e->getMessage();
        header("Location: formUpdateCliente.php?id=$clienteId");
        exit();
    }
} else {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "mainAdmin.php" : "main.php"));
    exit();
}
?>

