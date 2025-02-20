<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../includes/db.php';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)');
    $stmt->execute([$_POST['username'], $_POST['password']]);
    if ($user = $stmt->fetch()) {
        $_SESSION['loggedin'] = true;
        header('Location: admin.php');
    } else {
        $error = "Credenciales incorrectas!";
    }
}
?>
<!-- Formulario HTML con Bootstrap -->