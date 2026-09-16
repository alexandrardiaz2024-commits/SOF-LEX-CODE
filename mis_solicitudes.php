<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
include("../api/conexion.php");

$usuario = htmlspecialchars($_SESSION['usuario']);
// Tabla solicitudes: id, nombre, correo, telefono, servicio, detalle, fecha, estado
// Buscamos por nombre porque el cliente ingresó su nombre al solicitar
$nombre_esc = mysqli_real_escape_string($conn, $_SESSION['usuario']);
$res_sol    = mysqli_query($conn,
    "SELECT * FROM solicitudes WHERE nombre='$nombre_esc' ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Solicitudes · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<span class="petal" style="top:8%;left:2%;">🌸</span>

<header>
  <div class="logo-wrap">
    <img src="../logo.png" alt="SOF-LEX CODE">
    <div><div class="brand-name">SOF-LEX CODE</div><div class="brand-tagline">Elegancia en cada línea de código</div></div>
  </div>
  <nav class="nav-center">
    <a href="../index.php"><i class="fa-solid fa-house"></i> Inicio</a>
    <a href="solicitar.html"><i class="fa-solid fa-concierge-bell"></i> Nueva Solicitud</a>
    <a href="quejas.html"><i class="fa-solid fa-comment-dots"></i> Quejas</a>
  </nav>
  <div style="display:flex;align-items:center;gap:10px;">
    <span class="user-badge"><i class="fa-solid fa-user"></i> <?= $usuario ?></span>
    <a href="../api/logout.php" style="padding:8px 14px;border-radius:50px;background:rgba(255,255,255,.6);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-size:13px;font-weight:700;">
      <i class="fa-solid fa-right-from-bracket"></i> Salir
    </a>
  </div>
</header>

<div class="page-wrap" style="max-width:900px;">
  <h1 style="font-family:'Playfair Display',serif;font-size:36px;color:var(--plum);margin-bottom:22px;">
    <i class="fa-solid fa-list-check" style="color:var(--pink-dark);"></i> &nbsp;Mis Solicitudes
  </h1>
  <div class="glass-card" style="padding:26px;">
    <div class="table-wrap">
      <table>
        <thead><tr><th>#</th><th>Servicio</th><th>Detalle</th><th>Fecha</th><th>Estado</th></tr></thead>
        <tbody>
          <?php if ($res_sol && mysqli_num_rows($res_sol) > 0): while ($s = mysqli_fetch_assoc($res_sol)):
            $est = $s['estado'] ?? 'pendiente';
            $cls = $est==='pendiente'?'b-pending':($est==='en proceso'?'b-progress':'b-done');
            $fecha = $s['fecha'] ? substr($s['fecha'],0,10) : '—';
            $det = mb_strlen($s['detalle']??'') > 60 ? mb_substr($s['detalle'],0,60).'…' : ($s['detalle']??'—');
          ?>
          <tr>
            <td><?= $s['id'] ?></td>
            <td><strong><?= htmlspecialchars($s['servicio']) ?></strong></td>
            <td><?= htmlspecialchars($det) ?></td>
            <td><?= $fecha ?></td>
            <td><span class="badge <?= $cls ?>"><?= $est ?></span></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-light);">
            <i class="fa-solid fa-inbox" style="font-size:28px;display:block;margin-bottom:10px;"></i>
            Aún no tienes solicitudes.
            <br><br><a href="solicitar.html" style="color:var(--pink-dark);font-weight:700;text-decoration:none;">Solicitar un servicio &rarr;</a>
          </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <a href="../index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Volver al Inicio</a>
</div>

<footer><div class="footer-brand">SOF-LEX CODE 💗</div><p>© 2025 SOF-LEX CODE · Todos los derechos reservados</p></footer>
</body>
</html>
