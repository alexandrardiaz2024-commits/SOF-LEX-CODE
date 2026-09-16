<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); exit;
}
include("conexion.php");

if (isset($_POST['actualizar_pago'])) {
    $id     = (int)$_POST['pago_id'];
    $estado = in_array($_POST['estado_pago'], ['pendiente','verificado','rechazado'])
              ? $_POST['estado_pago'] : 'pendiente';
    mysqli_query($conn, "UPDATE pagos SET estado_pago='$estado' WHERE id=$id");
}
header("Location: ../app/admin.php?tab=pagos&ok=1");
exit;
?>
