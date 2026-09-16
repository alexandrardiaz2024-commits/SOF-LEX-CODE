<?php
session_start();
$logueado = isset($_SESSION['usuario']);
$usuario  = $logueado ? htmlspecialchars($_SESSION['usuario']) : '';
$rol      = $logueado ? $_SESSION['rol'] : '';

$login_error = '';
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
$reg_ok = '';
if (isset($_SESSION['reg_ok'])) {
    $reg_ok = $_SESSION['reg_ok'];
    unset($_SESSION['reg_ok']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .goog-te-banner-frame,.skiptranslate{display:none!important;}
    body{top:0!important;}
    .translate-box{background:rgba(255,255,255,.75);padding:6px 12px;border-radius:50px;font-size:12px;flex-shrink:0;}
  </style>
  <script>
    function googleTranslateElementInit(){
      new google.translate.TranslateElement(
        {pageLanguage:'es',includedLanguages:'es,en,fr,pt',
         layout:google.translate.TranslateElement.InlineLayout.SIMPLE},'gte');
    }
  </script>
  <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
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

  <nav class="nav-center">
    <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Inicio</a>
    <a href="app/historia.html"><i class="fa-solid fa-book"></i> Historia</a>
    <a href="app/mision.html"><i class="fa-solid fa-bullseye"></i> Misión</a>
    <a href="app/vision.html"><i class="fa-solid fa-eye"></i> Visión</a>
    <a href="app/valores.html"><i class="fa-solid fa-heart"></i> Valores</a>
    <a href="app/colores.html"><i class="fa-solid fa-palette"></i> Colores</a>
    <a href="app/servicios.html"><i class="fa-solid fa-briefcase"></i> Servicios</a>
    <a href="app/solicitar.html"><i class="fa-solid fa-concierge-bell"></i> Solicitar</a>
    <a href="app/quejas.html"><i class="fa-solid fa-comment-dots"></i> Quejas</a>
  </nav>

  <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
    <div class="translate-box"><div id="gte"></div></div>
    <?php if ($logueado): ?>
      <span class="user-badge"><i class="fa-solid fa-user"></i> <?= $usuario ?></span>
      <a href="api/logout.php" style="padding:8px 14px;border-radius:50px;background:rgba(255,255,255,.6);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-size:13px;font-weight:700;">
        <i class="fa-solid fa-right-from-bracket"></i> Salir
      </a>
    <?php else: ?>
      <a href="app/registro.html" style="padding:8px 14px;border-radius:50px;background:rgba(255,255,255,.6);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-size:13px;font-weight:700;">
        <i class="fa-solid fa-user-plus"></i> Registrarse
      </a>
    <?php endif; ?>
  </div>
</header>

<div class="page-wrap">
  <div class="index-layout">

    <div>
      <!-- HERO -->
      <div class="glass-card hero">
        <h1>Elegancia en cada línea de código</h1>
        <p>Creamos experiencias digitales modernas, funcionales y con personalidad.<br>
           Tecnología que inspira, diseño que enamora.</p>
        <a href="app/solicitar.html" class="btn-primary">
          <i class="fa-solid fa-concierge-bell"></i> &nbsp;Solicitar un Servicio
        </a>
      </div>

      <!-- SERVICIOS -->
      <div class="glass-card" style="padding:32px;margin-top:26px;">
        <div class="section-title">Nuestros Servicios</div>
        <div class="services-grid">

          <a href="app/solicitar.html?srv=Desarrollo+de+Software" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-code"></i></div>
            <div class="srv-name">Desarrollo de Software</div>
            <div class="srv-desc">Creamos aplicaciones web y de escritorio a medida, adaptadas a los procesos únicos de tu empresa.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Diseño+Web+Profesional" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-globe"></i></div>
            <div class="srv-name">Diseño Web Profesional</div>
            <div class="srv-desc">Diseñamos sitios modernos, elegantes y responsivos que reflejan la identidad de tu marca.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Automatización+de+Procesos" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-gears"></i></div>
            <div class="srv-name">Automatización de Procesos</div>
            <div class="srv-desc">Automatizamos tareas repetitivas para que tu equipo enfoque su energía en lo que importa.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Mantenimiento+de+Sistemas" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <div class="srv-name">Mantenimiento de Sistemas</div>
            <div class="srv-desc">Mantenemos tus sistemas actualizados, seguros y al máximo rendimiento siempre.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Soporte+Técnico" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-headset"></i></div>
            <div class="srv-name">Soporte Técnico</div>
            <div class="srv-desc">Asistencia técnica rápida y especializada para resolver cualquier problema cuando lo necesitas.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Seguridad+Digital" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="srv-name">Seguridad Digital</div>
            <div class="srv-desc">Protegemos tus datos e infraestructura con soluciones de ciberseguridad profesionales.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Consultoría+Tecnológica" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-lightbulb"></i></div>
            <div class="srv-name">Consultoría Tecnológica</div>
            <div class="srv-desc">Te asesoramos para tomar las mejores decisiones tecnológicas y crecer tu negocio.</div>
            <span class="srv-link">Solicitar</span>
          </a>

          <a href="app/solicitar.html?srv=Bases+de+Datos" class="service-card">
            <div class="srv-icon"><i class="fa-solid fa-database"></i></div>
            <div class="srv-name">Bases de Datos</div>
            <div class="srv-desc">Diseñamos, optimizamos y administramos bases de datos robustas y eficientes.</div>
            <span class="srv-link">Solicitar</span>
          </a>

        </div>
      </div>
    </div>

    <!-- PANEL LOGIN / SESIÓN -->
    <div class="glass-card login-panel">

      <?php if ($logueado): ?>
        <div class="sess-box">
          <div class="sess-avatar"><i class="fa-solid fa-user"></i></div>
          <h3><?= $usuario ?></h3>
          <p>Bienvenida de nuevo</p>

          <?php if ($rol === 'admin'): ?>
            <a href="app/admin.php" class="btn-sess btn-sess-filled">
              <i class="fa-solid fa-gauge"></i> Panel Administrativo
            </a>
          <?php else: ?>
            <a href="app/solicitar.html" class="btn-sess btn-sess-filled">
              <i class="fa-solid fa-concierge-bell"></i> Solicitar Servicio
            </a>
            <a href="app/quejas.html" class="btn-sess btn-sess-filled" style="margin-top:8px;">
              <i class="fa-solid fa-comment-dots"></i> Quejas y Sugerencias
            </a>
            <a href="app/mis_solicitudes.php" class="btn-sess btn-sess-filled" style="margin-top:8px;">
              <i class="fa-solid fa-list-check"></i> Mis Solicitudes
            </a>
          <?php endif; ?>

          <a href="api/logout.php" class="btn-sess btn-sess-ghost" style="margin-top:14px;">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
          </a>
        </div>

      <?php else: ?>
        <div class="panel-title">
          <i class="fa-solid fa-lock" style="color:var(--pink-dark);"></i> &nbsp;Iniciar Sesión
        </div>

        <?php if ($login_error): ?>
          <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($login_error) ?>
          </div>
        <?php endif; ?>
        <?php if ($reg_ok): ?>
          <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($reg_ok) ?>
          </div>
        <?php endif; ?>

        <form action="api/login.php" method="POST">
          <div class="form-group">
            <label><i class="fa-solid fa-user"></i> &nbsp;Usuario</label>
            <input type="text" name="usuario" placeholder="Tu usuario" required autocomplete="username">
          </div>
          <div class="form-group">
            <label><i class="fa-solid fa-key"></i> &nbsp;Contrasena</label>
            <input type="password" name="password" placeholder="Tu contrasena" required autocomplete="current-password">
          </div>
          <button type="submit" name="login" class="btn-submit">
            <i class="fa-solid fa-right-to-bracket"></i> &nbsp;Entrar
          </button>
        </form>

        <div class="login-divider">
          No tienes cuenta? <a href="app/registro.html">Crear cuenta</a>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<footer>
  <div class="footer-brand">SOF-LEX CODE</div>
  <div class="socials">
    <a href="https://wa.me/573001234567" target="_blank" class="soc-wa"><i class="fa-brands fa-whatsapp"></i></a>
    <a href="https://instagram.com/" target="_blank" class="soc-ig"><i class="fa-brands fa-instagram"></i></a>
    <a href="https://facebook.com/" target="_blank" class="soc-fb"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="https://x.com/" target="_blank" class="soc-tw"><i class="fa-brands fa-x-twitter"></i></a>
  </div>
  <p>2025 SOF-LEX CODE · Todos los derechos reservados</p>
</footer>

</body>
</html>
