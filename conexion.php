<?php
$conn = mysqli_connect("localhost", "root", "", "soflex");
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
