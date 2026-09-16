<?php
session_start();
require_once("php/verificar_admin.php");
require_once("php/conexion.php");

// Datos para el dashboard
$total_usuarios   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM usuarios"))[0] ?? 0;
$total_solicitudes= mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM solicitudes"))[0] ?? 0;
$total_quejas     = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM quejas"))[0] ?? 0;
$pendientes       = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM solicitudes WHERE estado='pendiente'"))[0] ?? 0;

// Últimas solicitudes
$res_sol  = mysqli_query($conn, "SELECT * FROM solicitudes ORDER BY fecha DESC LIMIT 10");
// Últimas quejas
$res_q    = mysqli_query($conn, "SELECT * FROM quejas ORDER BY fecha DESC LIMIT 10");
// Usuarios
$res_usu  = mysqli_query($conn, "SELECT id, usuario, correo, rol, estado FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .admin-layout { display:grid; grid-template-columns:240px 1fr; min-height:calc(100vh - 110px); }

    .admin-sidebar {
      background: var(--white-glass);
      backdrop-filter: blur(14px);
      border-right: 1px solid rgba(255,255,255,.4);
      padding: 28px 18px;
      position: sticky; top: 110px; height: fit-content;
    }
    .admin-sidebar .sidebar-title { margin-bottom: 16px; }

    .admin-main { padding: 34px; display:flex; flex-direction:column; gap:28px; }

    /* Stats */
    .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:20px; }
    .stat-card {
      background: var(--white-glass);
      backdrop-filter: blur(12px);
      border-radius: var(--radius-md);
      padding: 26px 22px;
      text-align: center;
      border: 1px solid rgba(255,255,255,.5);
      box-shadow: var(--shadow);
      transition: .3s;
    }
    .stat-card:hover { transform:translateY(-5px); }
    .stat-card i { font-size:36px; color:var(--pink-dark); margin-bottom:12px; display:block; }
    .stat-card .num {
      font-family:'Playfair Display',serif;
      font-size:40px;
      color:var(--plum);
      font-weight:700;
    }
    .stat-card .lbl { color:var(--text-light); font-size:14px; margin-top:4px; }

    /* Sections */
    .section-title {
      font-family:'Playfair Display',serif;
      font-size:24px;
      color:var(--plum);
      margin-bottom:18px;
      display:flex;
      align-items:center;
      gap:10px;
    }

    /* Tabs */
    .tabs { display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
    .tab-btn {
      padding:10px 22px;
      border-radius:50px;
      border:2px solid rgba(199,116,149,.25);
      background:rgba(255,255,255,.6);
      color:var(--plum);
      font-weight:700;
      cursor:pointer;
      transition:.2s;
      font-family:'Nunito',sans-serif;
      font-size:14px;
    }
    .tab-btn.active, .tab-btn:hover {
      background:linear-gradient(135deg,var(--pink-dark),var(--plum));
      color:#fff; border-color:transparent;
    }
    .tab-panel { display:none; }
    .tab-panel.active { display:block; }

    .btn-sm {
      padding:7px 14px;
      border:none;
      border-radius:50px;
      background:linear-gradient(135deg,var(--pink-dark),var(--plum));
      color:#fff;
      font-size:12px;
      font-weight:700;
      cursor:pointer;
      transition:.2s;
    }
    .btn-sm:hover { opacity:.85; }
    .btn-sm-danger {
      background:linear-gradient(135deg,#e57373,#c62828);
    }

    @media(max-width:900px){
      .admin-layout { grid-template-columns:1fr; }
      .admin-sidebar { position:static; }
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo-wrap">
    <img src="logo.png" alt="SOF-LEX CODE">
    <div>
      <div class="brand-name">SOF-LEX CODE</div>
      <div class="brand-tagline">Panel Administrativo</div>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:16px;">
    <span style="
      background:linear-gradient(135deg,var(--pink-mid),#fde8f2);
      padding:10px 18px;
      border-radius:50px;
      font-weight:700;
      color:var(--plum);
      font-size:14px;
    ">
      <i class="fa-solid fa-user-shield"></i> &nbsp;<?= htmlspecialchars($_SESSION['usuario']) ?>
    </span>
    <a href="php/logout.php" style="
      padding:10px 18px; border-radius:50px;
      background:linear-gradient(135deg,var(--pink-dark),var(--plum));
      color:#fff; text-decoration:none; font-weight:700; font-size:14px;
    ">
      <i class="fa-solid fa-right-from-bracket"></i> Salir
    </a>
  </div>
</header>

<div class="admin-layout">

  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="sidebar-title">Administración</div>
    <a href="#" onclick="showTab('dashboard')" class="sidebar" style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:linear-gradient(135deg,#fde8f2,#f9d0e4);text-decoration:none;color:var(--text-main);font-weight:600;font-size:14px;margin-bottom:10px;transition:.25s;">
      <i class="fa-solid fa-gauge" style="width:18px;text-align:center;"></i> Dashboard
    </a>
    <a href="#" onclick="showTab('solicitudes')" style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:linear-gradient(135deg,#fde8f2,#f9d0e4);text-decoration:none;color:var(--text-main);font-weight:600;font-size:14px;margin-bottom:10px;transition:.25s;">
      <i class="fa-solid fa-concierge-bell" style="width:18px;text-align:center;"></i> Solicitudes
    </a>
    <a href="#" onclick="showTab('quejas')" style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:linear-gradient(135deg,#fde8f2,#f9d0e4);text-decoration:none;color:var(--text-main);font-weight:600;font-size:14px;margin-bottom:10px;transition:.25s;">
      <i class="fa-solid fa-comment-dots" style="width:18px;text-align:center;"></i> Quejas
    </a>
    <a href="#" onclick="showTab('usuarios')" style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:linear-gradient(135deg,#fde8f2,#f9d0e4);text-decoration:none;color:var(--text-main);font-weight:600;font-size:14px;margin-bottom:10px;transition:.25s;">
      <i class="fa-solid fa-users" style="width:18px;text-align:center;"></i> Usuarios
    </a>
    <a href="index.php" style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:linear-gradient(135deg,#fde8f2,#f9d0e4);text-decoration:none;color:var(--text-main);font-weight:600;font-size:14px;margin-bottom:10px;transition:.25s;">
      <i class="fa-solid fa-house" style="width:18px;text-align:center;"></i> Ver Sitio
    </a>
  </aside>

  <!-- MAIN -->
  <main class="admin-main">

    <!-- TAB: DASHBOARD -->
    <div id="tab-dashboard" class="tab-panel active">

      <div class="section-title">
        <i class="fa-solid fa-gauge" style="color:var(--pink-dark);"></i>
        Dashboard
      </div>

      <div class="stats-row">
        <div class="stat-card">
          <i class="fa-solid fa-users"></i>
          <div class="num"><?= $total_usuarios ?></div>
          <div class="lbl">Usuarios Registrados</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-concierge-bell"></i>
          <div class="num"><?= $total_solicitudes ?></div>
          <div class="lbl">Solicitudes de Servicio</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-comment-dots"></i>
          <div class="num"><?= $total_quejas ?></div>
          <div class="lbl">Quejas / Sugerencias</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-clock"></i>
          <div class="num"><?= $pendientes ?></div>
          <div class="lbl">Solicitudes Pendientes</div>
        </div>
      </div>

      <!-- Bienvenida -->
      <div class="glass-card" style="margin-top:4px;">
        <h2 style="font-family:'Playfair Display',serif;color:var(--plum);margin-bottom:12px;font-size:28px;">
          Bienvenida, <?= htmlspecialchars($_SESSION['usuario']) ?> 💗
        </h2>
        <p style="color:var(--text-light);line-height:1.8;">
          Desde este panel puedes gestionar todas las solicitudes de servicio, revisar quejas y sugerencias,
          y administrar los usuarios del sistema. Usa el menú lateral para navegar entre secciones.
        </p>
      </div>

    </div>

    <!-- TAB: SOLICITUDES -->
    <div id="tab-solicitudes" class="tab-panel">
      <div class="section-title">
        <i class="fa-solid fa-concierge-bell" style="color:var(--pink-dark);"></i>
        Solicitudes de Servicio
      </div>
      <div class="glass-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Servicio</th>
                <th>Detalle</th>
                <th>Fecha</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res_sol && mysqli_num_rows($res_sol) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res_sol)): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td><?= htmlspecialchars($row['telefono'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['servicio']) ?></td>
                    <td style="max-width:200px;word-break:break-word;"><?= htmlspecialchars(substr($row['detalle'] ?? '', 0, 80)) ?>...</td>
                    <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                    <td>
                      <?php
                        $est = $row['estado'] ?? 'pendiente';
                        $cls = match($est){
                          'pendiente'  => 'badge-pending',
                          'en proceso' => 'badge-progress',
                          'resuelto'   => 'badge-done',
                          default      => 'badge-pending'
                        };
                      ?>
                      <span class="badge <?= $cls ?>"><?= ucfirst($est) ?></span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="8" style="text-align:center;color:var(--text-light);padding:30px;">
                  No hay solicitudes registradas aún.
                </td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB: QUEJAS -->
    <div id="tab-quejas" class="tab-panel">
      <div class="section-title">
        <i class="fa-solid fa-comment-dots" style="color:var(--pink-dark);"></i>
        Quejas y Sugerencias
      </div>
      <div class="glass-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Tipo</th>
                <th>Mensaje</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res_q && mysqli_num_rows($res_q) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res_q)): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td>
                      <span class="badge <?= $row['tipo']==='queja' ? 'badge-pending' : 'badge-progress' ?>">
                        <?= ucfirst($row['tipo']) ?>
                      </span>
                    </td>
                    <td style="max-width:260px;word-break:break-word;">
                      <?= htmlspecialchars(substr($row['mensaje'], 0, 100)) ?>...
                    </td>
                    <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-light);padding:30px;">
                  No hay quejas ni sugerencias registradas.
                </td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB: USUARIOS -->
    <div id="tab-usuarios" class="tab-panel">
      <div class="section-title">
        <i class="fa-solid fa-users" style="color:var(--pink-dark);"></i>
        Usuarios Registrados
      </div>
      <div class="glass-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res_usu && mysqli_num_rows($res_usu) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res_usu)): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><i class="fa-solid fa-user" style="color:var(--pink-dark);margin-right:6px;"></i><?= htmlspecialchars($row['usuario']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td>
                      <span class="badge <?= $row['rol']==='admin' ? 'badge-progress' : 'badge-done' ?>">
                        <?= ucfirst($row['rol']) ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge <?= $row['estado']==='Activo' ? 'badge-done' : 'badge-pending' ?>">
                        <?= htmlspecialchars($row['estado'] ?? 'Activo') ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" style="text-align:center;color:var(--text-light);padding:30px;">
                  No hay usuarios registrados.
                </td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </main>
</div>

<footer>
  <p>© 2025 SOF-LEX CODE · Panel Administrativo</p>
</footer>

<script>
function showTab(name) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
}
</script>

</body>
</html>
