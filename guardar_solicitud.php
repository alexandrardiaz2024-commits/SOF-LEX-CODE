<?php
session_start();
include("conexion.php");

if (!isset($_POST['enviar'])) {
    header("Location: ../app/solicitar.html");
    exit;
}

// Tabla solicitudes: id, nombre, correo, telefono, servicio, detalle, fecha, estado
$nombre   = mysqli_real_escape_string($conn, trim($_POST['nombre']));
$correo   = mysqli_real_escape_string($conn, trim($_POST['correo']));
$telefono = mysqli_real_escape_string($conn, trim($_POST['telefono'] ?? ''));
$servicio = mysqli_real_escape_string($conn, trim($_POST['servicio']));
$detalle  = mysqli_real_escape_string($conn, trim($_POST['detalle'] ?? ''));

$sql = "INSERT INTO solicitudes (nombre, correo, telefono, servicio, detalle, fecha, estado)
        VALUES ('$nombre','$correo','$telefono','$servicio','$detalle', NOW(), 'pendiente')";

if (mysqli_query($conn, $sql)) {
    $id = mysqli_insert_id($conn);
    // Guardar datos en sesión para el formulario de pago
    $_SESSION['pago_solicitud_id'] = $id;
    $_SESSION['pago_nombre']       = $nombre;
    $_SESSION['pago_correo']       = $correo;
    $_SESSION['pago_servicio']     = $servicio;
    // Guardar monto si viene en el POST o GET
    $monto_ref = floatval($_POST['monto_ref'] ?? 0);
    $_SESSION['pago_monto']        = $monto_ref;
    // Redirigir a formulario de pago
    header("Location: ../app/pago.html");
} else {
    header("Location: ../app/solicitar.html?error=1");
}
exit;
?>
