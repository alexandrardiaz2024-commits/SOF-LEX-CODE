<?php
session_start();
include("conexion.php");

if (!isset($_POST['registrar'])) {
    header("Location: ../app/registro.html");
    exit;
}

// Tabla usuarios: id, usuario, correo, password, rol (SIN estado)
$usuario  = mysqli_real_escape_string($conn, trim($_POST['usuario']));
$correo   = mysqli_real_escape_string($conn, trim($_POST['correo']));
$password = mysqli_real_escape_string($conn, trim($_POST['password']));

// Verificar duplicado
$check = mysqli_query($conn,
    "SELECT id FROM usuarios WHERE usuario='$usuario' OR correo='$correo' LIMIT 1");
if (mysqli_num_rows($check) > 0) {
    $_SESSION['reg_error'] = 'El usuario o correo ya existe.';
    header("Location: ../app/registro.html?error=1");
    exit;
}

$sql = "INSERT INTO usuarios (usuario, correo, password, rol)
        VALUES ('$usuario','$correo','$password','cliente')";

if (mysqli_query($conn, $sql)) {
    $_SESSION['reg_ok'] = '¡Cuenta creada! Ya puedes iniciar sesión.';
    header("Location: ../index.php");
} else {
    $_SESSION['reg_error'] = 'Error al crear la cuenta: ' . mysqli_error($conn);
    header("Location: ../app/registro.html?error=1");
}
exit;
?>
