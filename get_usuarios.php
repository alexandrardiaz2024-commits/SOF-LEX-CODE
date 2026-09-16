<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
include("conexion.php");
header('Content-Type: application/json');

// Tabla usuarios: id, usuario, correo, password, rol (SIN estado)
$res  = mysqli_query($conn, "SELECT id, usuario, correo, rol FROM usuarios ORDER BY id DESC");
$data = [];
while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
echo json_encode($data);
?>
