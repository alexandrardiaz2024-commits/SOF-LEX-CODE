<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); exit;
}
include("conexion.php");

if (!isset($_POST['enviar_msg'])) {
    header("Location: ../app/admin.php?tab=solicitudes"); exit;
}

$para_correo = mysqli_real_escape_string($conn, trim($_POST['para_correo']));
$para_nombre = mysqli_real_escape_string($conn, trim($_POST['para_nombre']));
$asunto      = mysqli_real_escape_string($conn, trim($_POST['asunto']));
$cuerpo      = mysqli_real_escape_string($conn, trim($_POST['cuerpo']));

$sql = "INSERT INTO mensajes (para_correo, para_nombre, asunto, cuerpo, fecha)
        VALUES ('$para_correo','$para_nombre','$asunto','$cuerpo', NOW())";

mysqli_query($conn, $sql);
header("Location: ../app/admin.php?tab=mensajes&ok=1");
exit;
?>
