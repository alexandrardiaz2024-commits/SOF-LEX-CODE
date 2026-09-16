<?php
// ARCHIVO TEMPORAL - BORRAR DESPUÉS DE USARLO
include("api/conexion.php");

echo "<style>body{font-family:Arial;padding:30px;background:#fff0f6;} h2{color:#b16d86;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:10px;} th{background:#c77495;color:white;} .ok{color:green;font-weight:bold;} .err{color:red;font-weight:bold;}</style>";
echo "<h2>SOF-LEX CODE — Diagnostico y Fix Admin</h2>";

// 1. Ver todos los usuarios actuales
echo "<h3>Usuarios en la BD ahora mismo:</h3>";
$r = mysqli_query($conn, "SELECT id, usuario, correo, password, rol FROM usuarios");
echo "<table><tr><th>id</th><th>usuario</th><th>correo</th><th>password</th><th>rol</th></tr>";
while ($u = mysqli_fetch_assoc($r)) {
    $cls = $u['rol'] === 'admin' ? 'ok' : 'err';
    echo "<tr>
            <td>{$u['id']}</td>
            <td><b>{$u['usuario']}</b></td>
            <td>{$u['correo']}</td>
            <td>{$u['password']}</td>
            <td class='$cls'>{$u['rol']}</td>
          </tr>";
}
echo "</table><br>";

// 2. Verificar si soflex_admin existe
$check = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario='soflex_admin' LIMIT 1");

if (mysqli_num_rows($check) > 0) {
    $u = mysqli_fetch_assoc($check);
    echo "<p class='ok'>El usuario soflex_admin EXISTE con rol: <b>{$u['rol']}</b></p>";

    // Forzar rol admin
    mysqli_query($conn, "UPDATE usuarios SET rol='admin' WHERE usuario='soflex_admin'");
    echo "<p class='ok'>Rol forzado a ADMIN correctamente.</p>";
} else {
    // Crear el usuario admin
    $sql = "INSERT INTO usuarios (usuario, correo, password, rol)
            VALUES ('soflex_admin', 'admin@soflex.com', 'Soflex2025*', 'admin')";
    if (mysqli_query($conn, $sql)) {
        echo "<p class='ok'>Usuario soflex_admin CREADO con rol admin.</p>";
    } else {
        echo "<p class='err'>Error al crear: " . mysqli_error($conn) . "</p>";
    }
}

// 3. Verificar resultado final
echo "<h3>Estado final del usuario admin:</h3>";
$final = mysqli_query($conn, "SELECT id, usuario, correo, password, rol FROM usuarios WHERE usuario='soflex_admin' LIMIT 1");
if ($r2 = mysqli_fetch_assoc($final)) {
    echo "<table><tr><th>id</th><th>usuario</th><th>correo</th><th>password</th><th>rol</th></tr>";
    $cls = $r2['rol'] === 'admin' ? 'ok' : 'err';
    echo "<tr><td>{$r2['id']}</td><td><b>{$r2['usuario']}</b></td><td>{$r2['correo']}</td><td>{$r2['password']}</td><td class='$cls'>{$r2['rol']}</td></tr>";
    echo "</table><br>";

    if ($r2['rol'] === 'admin') {
        echo "<div style='background:#f0faf0;border:2px solid green;padding:20px;border-radius:12px;margin-top:10px;'>
                <b style='color:green;font-size:18px;'>LISTO. Admin configurado correctamente.</b><br><br>
                <b>Usuario:</b> soflex_admin<br>
                <b>Password:</b> Soflex2025*<br><br>
                <a href='index.php' style='background:#c77495;color:white;padding:12px 24px;border-radius:50px;text-decoration:none;font-weight:bold;'>
                  Ir al inicio e iniciar sesion
                </a><br><br>
                <span style='color:red;font-size:12px;'>IMPORTANTE: Borra este archivo fix_admin.php despues de usarlo.</span>
              </div>";
    }
}

mysqli_close($conn);
?>
