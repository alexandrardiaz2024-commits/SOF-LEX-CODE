<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
include("conexion.php");
header('Content-Type: application/json');

// Tabla usuarios: sin columna estado
$usuarios    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM usuarios"))[0] ?? 0;
$solicitudes = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM solicitudes"))[0] ?? 0;
$quejas      = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM quejas"))[0] ?? 0;
$pendientes  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM solicitudes WHERE estado='pendiente'"))[0] ?? 0;

echo json_encode(compact('usuarios', 'solicitudes', 'quejas', 'pendientes'));
?>
