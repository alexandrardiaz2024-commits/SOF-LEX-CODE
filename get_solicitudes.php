<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
include("conexion.php");
header('Content-Type: application/json');

$res  = mysqli_query($conn, "SELECT * FROM solicitudes ORDER BY fecha DESC LIMIT 50");
$data = [];
while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
echo json_encode($data);
?>
