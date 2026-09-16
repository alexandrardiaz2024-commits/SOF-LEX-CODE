<?php
session_start();
include("conexion.php");

if (!isset($_POST['usuario'], $_POST['password'])) {
    header("Location: ../index.php");
    exit;
}

// Tabla usuarios: id, usuario, correo, password, rol
$usuario  = mysqli_real_escape_string($conn, trim($_POST['usuario']));
$password = mysqli_real_escape_string($conn, trim($_POST['password']));

$sql = "SELECT * FROM usuarios
        WHERE usuario='$usuario' AND password='$password'
        LIMIT 1";
$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $fila = mysqli_fetch_assoc($res);
    $_SESSION['id']      = $fila['id'];
    $_SESSION['usuario'] = $fila['usuario'];
    $_SESSION['rol']     = $fila['rol'];

    if ($fila['rol'] === 'admin') {
        header("Location: ../app/admin.php");
    } else {
        header("Location: ../index.php");
    }
} else {
    $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
    header("Location: ../index.php");
}
exit;
?>
