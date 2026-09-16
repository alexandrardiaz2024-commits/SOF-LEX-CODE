<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403); exit;
}
include("conexion.php");
header('Content-Type: application/json');
$res  = mysqli_query($conn, "SELECT * FROM mensajes ORDER BY fecha DESC LIMIT 100");
$data = [];
while ($r = mysqli_fetch_assoc($res)) $data[] = $r;
echo json_encode($data);
?>
