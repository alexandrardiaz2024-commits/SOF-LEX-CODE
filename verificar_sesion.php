<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache');

if (isset($_SESSION['usuario'])) {
    echo json_encode([
        "logueado" => true,
        "usuario"  => $_SESSION['usuario'],
        "correo"   => $_SESSION['correo']  ?? '',
        "rol"      => $_SESSION['rol']     ?? 'cliente',
        "id"       => $_SESSION['id']      ?? null
    ]);
} else {
    echo json_encode([
        "logueado" => false,
        "usuario"  => "",
        "rol"      => "",
        "id"       => null
    ]);
}
?>
