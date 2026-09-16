<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["autorizado" => false, "mensaje" => "Acceso denegado"]);
    exit;
}

echo json_encode([
    "autorizado" => true,
    "usuario"    => $_SESSION['usuario'],
    "rol"        => $_SESSION['rol']
]);
?>
