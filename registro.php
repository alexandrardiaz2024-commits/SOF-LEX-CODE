<?php
session_start();
$error = '';
$ok    = '';
if (isset($_SESSION['reg_error'])) { $error = $_SESSION['reg_error']; unset($_SESSION['reg_error']); }
if (isset($_SESSION['reg_ok']))    { $ok    = $_SESSION['reg_ok'];    unset($_SESSION['reg_ok']); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Cuenta · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .register-wrap {
      min-height: calc(100vh - 100px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .register-box {
      width: 100%;
      max-width: 480px;
    }
    .register-box h1 {
      font-family: 'Playfair Display', serif;
      font-size: 34px;
      color: var(--plum);
      text-align: center;
      margin-bottom: 6px;
    }
    .register-box .sub {
      text-align: center;
      color: var(--text-light);
      font-size: 15px;
      margin-bottom: 26px;
    }
    .back-link {
      text-align: center;
      margin-top: 18px;
      font-size: 14px;
      color: var(--text-light);
    }
    .back-link a { color: var(--pink-dark); font-weight: 700; text-decoration: none; }
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
  </nav>
</header>

<div class="register-wrap">
  <div class="glass-card register-box">

    <h1>✨ Crear Cuenta</h1>
    <p class="sub">Únete a SOF-LEX CODE y accede a todos nuestros servicios</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($ok): ?>
      <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($ok) ?></div>
    <?php endif; ?>

    <form action="php/registrar.php" method="POST">
      <div class="form-group">
        <label><i class="fa-solid fa-user"></i> &nbsp;Usuario</label>
        <input type="text" name="usuario" placeholder="Elige un nombre de usuario" required>
      </div>
      <div class="form-group">
        <label><i class="fa-solid fa-envelope"></i> &nbsp;Correo Electrónico</label>
        <input type="email" name="correo" placeholder="tucorreo@email.com" required>
      </div>
      <div class="form-group">
        <label><i class="fa-solid fa-lock"></i> &nbsp;Contraseña</label>
        <input type="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6" required>
      </div>
      <button type="submit" name="registrar" class="btn-submit">
        <i class="fa-solid fa-user-plus"></i> &nbsp;Crear Cuenta
      </button>
    </form>

    <div class="back-link">
      ¿Ya tienes cuenta? <a href="index.php">Iniciar sesión</a>
    </div>

  </div>
</div>

<footer>
  <p>© 2025 SOF-LEX CODE · Todos los derechos reservados</p>
</footer>

</body>
</html>
