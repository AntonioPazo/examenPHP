<?php
session_start();
require_once '../src/ConexionDB.php';
require_once '../src/Cliente.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: mainAdmin.php");
        exit();
    } else {
        // Check database for user credentials
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $stmt = $conexion->prepare("SELECT * FROM clientes WHERE usuario = ?");
        $stmt->execute([$username]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente && password_verify($password, $cliente['password'])) {
            // La contraseña es válida, iniciamos sesión
            $_SESSION['user'] = $username;
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['role'] = 'user';
            header("Location: main.php");
            exit();
        } else {
            // Error de autenticación
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            header("Location: index.php");
            exit();
        }
    }
}
?>
