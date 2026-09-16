<?php
session_start();
include("conexion.php");

if (!isset($_POST['enviar'])) {
    header("Location: ../app/quejas.html");
    exit;
}

// Tabla quejas: id, nombre, correo, tipo, mensaje, fecha, leido
$nombre  = mysqli_real_escape_string($conn, trim($_POST['nombre']));
$correo  = mysqli_real_escape_string($conn, trim($_POST['correo']));
$tipo    = $_POST['tipo'] === 'sugerencia' ? 'sugerencia' : 'queja';
$mensaje = mysqli_real_escape_string($conn, trim($_POST['mensaje']));

$sql = "INSERT INTO quejas (nombre, correo, tipo, mensaje, fecha, leido)
        VALUES ('$nombre','$correo','$tipo','$mensaje', NOW(), 0)";

if (mysqli_query($conn, $sql)) {
    header("Location: ../app/quejas.html?ok=1");
} else {
    header("Location: ../app/quejas.html?error=1");
}
exit;
?>
