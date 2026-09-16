<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache');
if (isset($_SESSION['usuario'])) {
    echo json_encode([
        'logueado' => true,
        'usuario'  => $_SESSION['usuario'],
        'rol'      => $_SESSION['rol']
    ]);
} else {
    echo json_encode(['logueado' => false]);
}
?>
