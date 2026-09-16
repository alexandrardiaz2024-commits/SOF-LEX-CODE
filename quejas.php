<?php
session_start();
$ok    = '';
$error = '';
if (isset($_SESSION['queja_ok']))    { $ok    = $_SESSION['queja_ok'];    unset($_SESSION['queja_ok']); }
if (isset($_SESSION['queja_error'])) { $error = $_SESSION['queja_error']; unset($_SESSION['queja_error']); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quejas y Sugerencias · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .q-wrap { max-width: 680px; margin: 40px auto; padding: 0 20px; }
    .q-header { text-align: center; margin-bottom: 30px; }
    .q-header h1 { font-family:'Playfair Display',serif; font-size:40px; color:var(--plum); }
    .q-header p  { color:var(--text-light); margin-top:8px; font-size:16px; }
    .tipo-tabs {
      display: flex; gap: 12px; margin-bottom: 24px;
    }
    .tipo-tab {
      flex: 1; padding: 13px;
      border-radius: var(--radius-sm);
      border: 2px solid rgba(199,116,149,.25);
      background: rgba(255,255,255,.6);
      cursor: pointer;
      text-align: center;
      font-weight: 700;
      color: var(--plum);
      transition: .2s;
    }
    .tipo-tab.active, .tipo-tab:hover {
      background: linear-gradient(135deg,var(--pink-dark),var(--plum));
      color: #fff;
      border-color: transparent;
    }
  </style>
</head>
<body>

<header>
  <div class="logo-wrap">
    <img src="logo.png" alt="SOF-LEX CODE">
    <div>
      <div class="brand-name">SOF-LEX CODE</div>
      <div class="brand-tagline">Elegancia en cada línea de código</div>
    </div>
  </div>
  <nav class="nav-links">
    <a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a>
    <a href="solicitar.php"><i class="fa-solid fa-concierge-bell"></i> Solicitar</a>
    <?php if (isset($_SESSION['usuario'])): ?>
      <span style="color:var(--plum);font-weight:700;padding:9px 14px;">
        <i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['usuario']) ?>
      </span>
      <a href="php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
    <?php else: ?>
      <a href="index.php" class="btn-login"><i class="fa-solid fa-lock"></i> Iniciar Sesión</a>
    <?php endif; ?>
  </nav>
</header>

<div class="q-wrap">

  <div class="q-header">
    <h1>💬 Quejas y Sugerencias</h1>
    <p>Tu opinión nos ayuda a mejorar. ¡Escríbenos con confianza!</p>
  </div>

  <?php if ($ok): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($ok) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="glass-card">
    <form action="php/guardar_queja.php" method="POST" id="quejaForm">

      <div class="form-group">
        <label><i class="fa-solid fa-user"></i> &nbsp;Nombre Completo</label>
        <input type="text" name="nombre"
          value="<?= isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : '' ?>"
          placeholder="Tu nombre" required>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-envelope"></i> &nbsp;Correo Electrónico</label>
        <input type="email" name="correo" placeholder="tucorreo@email.com" required>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-tag"></i> &nbsp;Tipo de mensaje</label>
        <div class="tipo-tabs">
          <div class="tipo-tab active" onclick="setTipo('queja', this)">
            <i class="fa-solid fa-face-frown"></i> &nbsp;Queja
          </div>
          <div class="tipo-tab" onclick="setTipo('sugerencia', this)">
            <i class="fa-solid fa-lightbulb"></i> &nbsp;Sugerencia
          </div>
        </div>
        <input type="hidden" name="tipo" id="tipo_hidden" value="queja">
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-message"></i> &nbsp;Tu mensaje</label>
        <textarea name="mensaje" placeholder="Escribe aquí tu queja o sugerencia..." required></textarea>
      </div>

      <button type="submit" name="enviar" class="btn-submit">
        <i class="fa-solid fa-paper-plane"></i> &nbsp;Enviar Mensaje
      </button>

    </form>
  </div>

  <a href="index.php" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Volver al Inicio
  </a>

</div>

<footer>
  <div class="brand-footer">SOF-LEX CODE 💗</div>
  <div class="socials">
    <a href="https://wa.me/573001234567" target="_blank" class="soc-wa"><i class="fa-brands fa-whatsapp"></i></a>
    <a href="https://instagram.com/" target="_blank" class="soc-ig"><i class="fa-brands fa-instagram"></i></a>
    <a href="https://facebook.com/" target="_blank" class="soc-fb"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="https://x.com/" target="_blank" class="soc-tw"><i class="fa-brands fa-x-twitter"></i></a>
  </div>
  <p>© 2025 SOF-LEX CODE · Todos los derechos reservados</p>
</footer>

<script>
function setTipo(val, el) {
  document.getElementById('tipo_hidden').value = val;
  document.querySelectorAll('.tipo-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}
</script>

</body>
</html>
