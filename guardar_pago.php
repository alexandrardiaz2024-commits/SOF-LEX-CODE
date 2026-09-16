<?php
session_start();
include("conexion.php");

if (!isset($_POST['pagar'])) {
    header("Location: ../app/pago.html");
    exit;
}

// Datos de sesión (guardados al crear la solicitud)
$solicitud_id = (int)($_SESSION['pago_solicitud_id'] ?? 0);
$nombre       = mysqli_real_escape_string($conn, $_SESSION['pago_nombre'] ?? '');
$correo       = mysqli_real_escape_string($conn, $_SESSION['pago_correo'] ?? '');
$servicio     = mysqli_real_escape_string($conn, $_SESSION['pago_servicio'] ?? '');

// Datos del formulario de pago
$metodo    = mysqli_real_escape_string($conn, trim($_POST['metodo_pago']));
$referencia= mysqli_real_escape_string($conn, trim($_POST['referencia'] ?? ''));
$monto     = floatval($_POST['monto'] ?? 0);

// Validar método permitido
$metodos_validos = ['Nequi','Daviplata','Tarjeta de Crédito','Tarjeta de Débito','PSE','Efecty','Otro'];
if (!in_array($metodo, $metodos_validos)) {
    header("Location: ../app/pago.html?error=metodo");
    exit;
}

$sql = "INSERT INTO pagos (solicitud_id, nombre, correo, servicio, metodo_pago, referencia, monto, estado_pago, fecha)
        VALUES ($solicitud_id,'$nombre','$correo','$servicio','$metodo','$referencia',$monto,'pendiente',NOW())";

if (mysqli_query($conn, $sql)) {
    // Limpiar sesión de pago
    unset($_SESSION['pago_solicitud_id'],
          $_SESSION['pago_nombre'],
          $_SESSION['pago_correo'],
          $_SESSION['pago_servicio']);
    header("Location: ../app/pago_confirmado.html");
} else {
    header("Location: ../app/pago.html?error=db");
}
exit;
?>
