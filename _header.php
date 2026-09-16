<?php
// Incluir en cada página de app/ con: include('_header.php');
// $page_active = nombre del link activo en nav (ej: 'historia', 'mision', etc.)
// $sidebar_active = nombre del link activo en sidebar
session_start();
$_logueado = isset($_SESSION['usuario']);
$_usuario  = $_logueado ? htmlspecialchars($_SESSION['usuario']) : '';
$_rol      = $_logueado ? $_SESSION['rol'] : '';
$_page     = $page_active ?? '';
$_side     = $sidebar_active ?? '';

function nav_active($key) {
    global $_page;
    return $key === $_page ? 'active' : '';
}
function side_active($key) {
    global $_side;
    return $key === $_side ? 'active' : '';
}
?>
<header>
  <div class="logo-wrap">
    <img src="../logo.png" alt="SOF-LEX CODE">
    <div>
      <div class="brand-name">SOF-LEX CODE</div>
      <div class="brand-tagline">Elegancia en cada línea de código</div>
    </div>
  </div>
  <nav class="nav-center">
    <a href="../index.php"><i class="fa-solid fa-house"></i> Inicio</a>
    <a href="servicios.html"  class="<?= nav_active('servicios') ?>"><i class="fa-solid fa-briefcase"></i> Servicios</a>
    <a href="solicitar.html"  class="<?= nav_active('solicitar') ?>"><i class="fa-solid fa-concierge-bell"></i> Solicitar</a>
    <a href="quejas.html"     class="<?= nav_active('quejas') ?>"><i class="fa-solid fa-comment-dots"></i> Quejas</a>
  </nav>
  <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
    <?php if ($_logueado): ?>
      <span class="user-badge"><i class="fa-solid fa-user"></i> <?= $_usuario ?></span>
      <a href="../api/logout.php" style="padding:8px 14px;border-radius:50px;background:rgba(255,255,255,.6);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-size:13px;font-weight:700;">
        <i class="fa-solid fa-right-from-bracket"></i> Salir
      </a>
    <?php else: ?>
      <a href="../index.php" style="padding:8px 14px;border-radius:50px;background:linear-gradient(135deg,var(--pink-dark),var(--plum));color:#fff;text-decoration:none;font-size:13px;font-weight:700;">
        <i class="fa-solid fa-lock"></i> Iniciar Sesión
      </a>
    <?php endif; ?>
  </div>
</header>
