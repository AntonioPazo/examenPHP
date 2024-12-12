<?php
session_start();
// If user is already logged in, redirect to main.php
if (isset($_SESSION['user'])) {
    header("Location: main.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pastelería</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-body">
    <form action="login.php" method="post" class="login-form">
        <h2>Login Pastelería</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>

