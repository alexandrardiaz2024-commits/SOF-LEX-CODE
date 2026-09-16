<?php
session_start();
$ok    = '';
$error = '';
if (isset($_SESSION['sol_ok']))    { $ok    = $_SESSION['sol_ok'];    unset($_SESSION['sol_ok']); }
if (isset($_SESSION['sol_error'])) { $error = $_SESSION['sol_error']; unset($_SESSION['sol_error']); }
$srv_param = isset($_GET['srv']) ? htmlspecialchars($_GET['srv']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitar Servicio · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .sol-wrap {
      max-width: 680px;
      margin: 40px auto;
      padding: 0 20px;
    }
    .sol-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .sol-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 40px;
      color: var(--plum);
    }
    .sol-header p { color: var(--text-light); margin-top: 8px; font-size: 16px; }
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
    <a href="quejas.php"><i class="fa-solid fa-comment-dots"></i> Quejas</a>
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

<div class="sol-wrap">

  <div class="sol-header">
    <h1>💼 Solicitar Servicio</h1>
    <p>Cuéntanos qué necesitas y te contactamos a la brevedad.</p>
  </div>

  <?php if ($ok): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($ok) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="glass-card">
    <form action="php/guardar_solicitud.php" method="POST">

      <div class="form-group">
        <label><i class="fa-solid fa-user"></i> &nbsp;Nombre Completo</label>
        <input type="text" name="nombre"
          value="<?= isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : '' ?>"
          placeholder="Tu nombre completo" required>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-envelope"></i> &nbsp;Correo Electrónico</label>
        <input type="email" name="correo" placeholder="tucorreo@email.com" required>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-phone"></i> &nbsp;Teléfono</label>
        <input type="tel" name="telefono" placeholder="Ej: 3001234567">
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-list"></i> &nbsp;Servicio que necesitas</label>
        <select name="servicio" required>
          <option value="">— Selecciona un servicio —</option>
          <?php
          $servicios = [
            'Desarrollo de Software','Diseño Web Profesional',
            'Automatización de Procesos','Mantenimiento de Sistemas',
            'Soporte Técnico','Seguridad Digital',
            'Consultoría Tecnológica','Bases de Datos'
          ];
          foreach ($servicios as $s):
            $selected = ($srv_param === $s) ? 'selected' : '';
          ?>
            <option value="<?= htmlspecialchars($s) ?>" <?= $selected ?>><?= htmlspecialchars($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fa-solid fa-message"></i> &nbsp;Describe tu necesidad</label>
        <textarea name="detalle" placeholder="Cuéntanos los detalles de lo que necesitas..."></textarea>
      </div>

      <button type="submit" name="enviar" class="btn-submit">
        <i class="fa-solid fa-paper-plane"></i> &nbsp;Enviar Solicitud
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

</body>
</html>
