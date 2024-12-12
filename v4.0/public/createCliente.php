<?php
session_start();
require_once '../src/Pasteleria.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($usuario) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: formCreateCliente.php");
        exit();
    }

    // Cifrar la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $pasteleria = new Pasteleria("Mi Pastelería");

    try {
        $pasteleria->incluirCliente($nombre, $usuario, $hashedPassword); // Pasar la contraseña cifrada
        $_SESSION['success'] = "Cliente creado exitosamente.";
        header("Location: mainAdmin.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al crear el cliente: " . $e->getMessage();
        header("Location: formCreateCliente.php");
        exit();
    }
} else {
    header("Location: mainAdmin.php");
    exit();
}
?>
