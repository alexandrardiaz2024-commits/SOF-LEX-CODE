<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store');

if (!isset($_SESSION['pago_solicitud_id'])) {
    echo json_encode(['valido' => false]);
    exit;
}

echo json_encode([
    'valido'   => true,
    'nombre'   => $_SESSION['pago_nombre'] ?? '',
    'correo'   => $_SESSION['pago_correo'] ?? '',
    'servicio' => $_SESSION['pago_servicio'] ?? '',
    'id'       => $_SESSION['pago_solicitud_id'] ?? 0,
    'monto'    => $_SESSION['pago_monto'] ?? 0
]);
?>
