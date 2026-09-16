<?php
session_start();

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); exit;
}

include("../api/conexion.php");

$admin   = htmlspecialchars($_SESSION['usuario']);
$sid     = (int)$_SESSION['id'];
$tab     = $_GET['tab'] ?? 'dashboard';
$msg     = '';
$msg_tipo = 'success';

// ── Cambiar rol usuario ──
if (isset($_POST['cambiar_rol'])) {
    $id_t = (int)$_POST['id_usuario'];
    $rol_n = $_POST['nuevo_rol'] === 'admin' ? 'admin' : 'cliente';
    if ($id_t !== $sid) {
        mysqli_query($conn,"UPDATE usuarios SET rol='$rol_n' WHERE id=$id_t");
        $msg = "Rol actualizado."; $tab = 'usuarios';
    } else { $msg = "No puedes cambiar tu propio rol."; $msg_tipo='error'; $tab='usuarios'; }
}

// ── Eliminar usuario ──
if (isset($_POST['eliminar_usuario'])) {
    $id_t = (int)$_POST['id_usuario'];
    if ($id_t !== $sid) {
        mysqli_query($conn,"DELETE FROM usuarios WHERE id=$id_t");
        $msg = "Usuario eliminado."; $tab = 'usuarios';
    } else { $msg = "No puedes eliminarte."; $msg_tipo='error'; $tab='usuarios'; }
}

// ── Actualizar estado solicitud ──
if (isset($_POST['cambiar_estado_sol'])) {
    $sol_id = (int)$_POST['sol_id'];
    $est    = in_array($_POST['nuevo_estado'],['pendiente','en proceso','resuelto'])
              ? $_POST['nuevo_estado'] : 'pendiente';
    mysqli_query($conn,"UPDATE solicitudes SET estado='$est' WHERE id=$sol_id");
    $msg = "Estado actualizado."; $tab = 'solicitudes';
}

// ── Actualizar estado pago ──
if (isset($_POST['actualizar_pago'])) {
    $pid  = (int)$_POST['pago_id'];
    $pest = in_array($_POST['estado_pago'],['pendiente','verificado','rechazado'])
            ? $_POST['estado_pago'] : 'pendiente';
    mysqli_query($conn,"UPDATE pagos SET estado_pago='$pest' WHERE id=$pid");
    $msg = "Pago actualizado."; $tab = 'pagos';
}

// ── Enviar mensaje a cliente ──
if (isset($_POST['enviar_msg'])) {
    $pcorreo = mysqli_real_escape_string($conn, trim($_POST['para_correo']));
    $pnombre = mysqli_real_escape_string($conn, trim($_POST['para_nombre']));
    $asunto  = mysqli_real_escape_string($conn, trim($_POST['asunto']));
    $cuerpo  = mysqli_real_escape_string($conn, trim($_POST['cuerpo']));
    mysqli_query($conn,
        "INSERT INTO mensajes (para_correo,para_nombre,asunto,cuerpo,fecha)
         VALUES ('$pcorreo','$pnombre','$asunto','$cuerpo',NOW())");
    $msg = "Mensaje enviado a $pnombre."; $tab = 'mensajes';
}

// ── Estadísticas ──
$t_usu  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM usuarios"))[0] ?? 0;
$t_sol  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM solicitudes"))[0] ?? 0;
$t_q    = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM quejas"))[0] ?? 0;
$t_pag  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM pagos"))[0] ?? 0;
$t_pend = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM solicitudes WHERE estado='pendiente'"))[0] ?? 0;
$t_msg  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM mensajes"))[0] ?? 0;

// ── Consultas ──
$r_usu  = mysqli_query($conn,"SELECT id,usuario,correo,rol FROM usuarios ORDER BY id DESC");
$r_sol  = mysqli_query($conn,"SELECT * FROM solicitudes ORDER BY fecha DESC LIMIT 50");
$r_q    = mysqli_query($conn,"SELECT * FROM quejas ORDER BY fecha DESC LIMIT 50");
$r_pag  = mysqli_query($conn,"SELECT * FROM pagos ORDER BY fecha DESC LIMIT 50");
$r_msg  = mysqli_query($conn,"SELECT * FROM mensajes ORDER BY fecha DESC LIMIT 50");
// Correos únicos de solicitudes para el selector de mensajes
$r_correos = mysqli_query($conn,"SELECT DISTINCT nombre,correo FROM solicitudes ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin · SOF-LEX CODE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../assets/styles.css">
  <style>
    .admin-wrap  { display:grid; grid-template-columns:230px 1fr; min-height:calc(100vh - 100px); }
    .admin-side  {
      background:var(--glass); backdrop-filter:blur(14px);
      border-right:1px solid var(--glass-border);
      padding:24px 14px; position:sticky; top:100px; height:fit-content;
    }
    .admin-main  { padding:28px 32px; display:flex; flex-direction:column; gap:22px; }
    .s-link {
      display:flex; align-items:center; gap:10px;
      padding:11px 14px; border-radius:var(--r-md);
      background:linear-gradient(135deg,#fde8f2,#f9d0e4);
      text-decoration:none; color:var(--text-main);
      font-weight:600; font-size:13px; margin-bottom:7px;
      border:1px solid rgba(199,116,149,.15); transition:.2s;
    }
    .s-link:hover,.s-link.active {
      background:linear-gradient(135deg,var(--pink-dark),var(--plum));
      color:#fff; border-color:transparent;
    }
    .s-link .s-badge {
      margin-left:auto; background:var(--pink-dark); color:#fff;
      border-radius:50px; padding:1px 7px; font-size:11px;
    }
    .s-link.active .s-badge { background:rgba(255,255,255,.3); }

    .sec-title { font-family:'Playfair Display',serif; font-size:24px; color:var(--plum); margin-bottom:18px; display:flex; align-items:center; gap:10px; }
    .tab-panel  { display:none; } .tab-panel.active { display:block; }

    /* Forms inline */
    .rol-form { display:inline-flex; align-items:center; gap:6px; }
    .rol-sel  { padding:5px 9px; border-radius:50px; font-size:12px; font-weight:700; border:1.5px solid rgba(199,116,149,.3); background:#fff; color:var(--plum); font-family:'Nunito',sans-serif; }
    .btn-xs { padding:5px 13px; border-radius:50px; border:none; font-size:11px; font-weight:700; cursor:pointer; transition:.2s; font-family:'Nunito',sans-serif; }
    .bx-pink { background:linear-gradient(135deg,var(--pink-dark),var(--plum)); color:#fff; }
    .bx-red  { background:linear-gradient(135deg,#e57373,#c62828); color:#fff; }
    .bx-grn  { background:linear-gradient(135deg,#66bb6a,#2e7d32); color:#fff; }
    .btn-xs:hover { opacity:.85; }
    .yo-tag { display:inline-block; padding:2px 8px; border-radius:50px; background:rgba(199,116,149,.15); color:var(--plum); font-size:10px; font-weight:700; margin-left:4px; }

    /* Formulario mensaje */
    .msg-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .msg-form-full { grid-column:1/-1; }

    /* Card mensaje enviado */
    .msg-card {
      background:rgba(255,255,255,.75); border-radius:16px;
      padding:18px; border:1px solid rgba(199,116,149,.15);
      margin-bottom:12px; transition:.2s;
    }
    .msg-card:hover { box-shadow:var(--shadow-sm); }
    .msg-card .mc-head { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px; }
    .msg-card .mc-name { font-weight:700; color:var(--plum); font-size:14px; }
    .msg-card .mc-date { font-size:11px; color:var(--text-light); }
    .msg-card .mc-sub  { font-size:13px; color:var(--text-main); font-weight:600; margin-bottom:6px; }
    .msg-card .mc-body { font-size:13px; color:var(--text-light); line-height:1.6; }

    /* Pago card */
    .pago-estado-form { display:inline-flex; align-items:center; gap:6px; }

    @media(max-width:900px){ .admin-wrap{grid-template-columns:1fr;} .admin-side{position:static;} .msg-form-grid{grid-template-columns:1fr;} }
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo-wrap">
    <img src="../logo.png" alt="SOF-LEX CODE">
    <div><div class="brand-name">SOF-LEX CODE</div><div class="brand-tagline">Panel Administrativo</div></div>
  </div>
  <nav class="nav-center">
    <a href="../index.php"><i class="fa-solid fa-house"></i> Ver Sitio</a>
  </nav>
  <div style="display:flex;align-items:center;gap:10px;">
    <span class="user-badge"><i class="fa-solid fa-user-shield"></i> <?= $admin ?></span>
    <a href="../api/logout.php" style="padding:8px 16px;border-radius:50px;background:linear-gradient(135deg,var(--pink-dark),var(--plum));color:#fff;text-decoration:none;font-size:13px;font-weight:700;">
      <i class="fa-solid fa-right-from-bracket"></i> Salir
    </a>
  </div>
</header>

<div class="admin-wrap">

  <!-- SIDEBAR -->
  <aside class="admin-side">
    <div class="sidebar-title">Administracion</div>
    <a href="admin.php?tab=dashboard"   class="s-link <?= $tab==='dashboard'  ?'active':'' ?>"><i class="fa-solid fa-gauge"          style="width:16px"></i> Dashboard</a>
    <a href="admin.php?tab=usuarios"    class="s-link <?= $tab==='usuarios'   ?'active':'' ?>"><i class="fa-solid fa-users"          style="width:16px"></i> Usuarios <span class="s-badge"><?= $t_usu ?></span></a>
    <a href="admin.php?tab=solicitudes" class="s-link <?= $tab==='solicitudes'?'active':'' ?>"><i class="fa-solid fa-concierge-bell" style="width:16px"></i> Solicitudes <span class="s-badge"><?= $t_sol ?></span></a>
    <a href="admin.php?tab=pagos"       class="s-link <?= $tab==='pagos'      ?'active':'' ?>"><i class="fa-solid fa-credit-card"    style="width:16px"></i> Pagos <span class="s-badge"><?= $t_pag ?></span></a>
    <a href="admin.php?tab=quejas"      class="s-link <?= $tab==='quejas'     ?'active':'' ?>"><i class="fa-solid fa-comment-dots"   style="width:16px"></i> Quejas <span class="s-badge"><?= $t_q ?></span></a>
    <a href="admin.php?tab=mensajes"    class="s-link <?= $tab==='mensajes'   ?'active':'' ?>"><i class="fa-solid fa-paper-plane"    style="width:16px"></i> Mensajes <span class="s-badge"><?= $t_msg ?></span></a>
    <a href="../index.php"              class="s-link" style="margin-top:14px;"><i class="fa-solid fa-house" style="width:16px"></i> Inicio del Sitio</a>
    <a href="../api/logout.php"         class="s-link"><i class="fa-solid fa-right-from-bracket" style="width:16px"></i> Cerrar Sesion</a>
  </aside>

  <!-- MAIN -->
  <main class="admin-main">

    <?php if ($msg): ?>
      <div class="alert alert-<?= $msg_tipo==='error'?'error':'success' ?>">
        <i class="fa-solid fa-<?= $msg_tipo==='error'?'circle-exclamation':'circle-check' ?>"></i>
        <?= htmlspecialchars($msg) ?>
      </div>
    <?php endif; ?>

    <!-- ══════════ DASHBOARD ══════════ -->
    <div class="tab-panel <?= $tab==='dashboard'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-gauge" style="color:var(--pink-dark)"></i> Dashboard</div>
      <div class="stats-row">
        <div class="stat-card"><i class="fa-solid fa-users"></i><div class="num"><?= $t_usu ?></div><div class="lbl">Usuarios</div></div>
        <div class="stat-card"><i class="fa-solid fa-concierge-bell"></i><div class="num"><?= $t_sol ?></div><div class="lbl">Solicitudes</div></div>
        <div class="stat-card"><i class="fa-solid fa-credit-card"></i><div class="num"><?= $t_pag ?></div><div class="lbl">Pagos</div></div>
        <div class="stat-card"><i class="fa-solid fa-comment-dots"></i><div class="num"><?= $t_q ?></div><div class="lbl">Quejas</div></div>
        <div class="stat-card"><i class="fa-solid fa-clock"></i><div class="num"><?= $t_pend ?></div><div class="lbl">Pendientes</div></div>
        <div class="stat-card"><i class="fa-solid fa-paper-plane"></i><div class="num"><?= $t_msg ?></div><div class="lbl">Mensajes</div></div>
      </div>
      <div class="glass-card" style="padding:26px;margin-top:4px;">
        <h2 style="font-family:'Playfair Display',serif;color:var(--plum);font-size:26px;margin-bottom:10px;">
          Bienvenida, <?= $admin ?>
        </h2>
        <p style="color:var(--text-light);line-height:1.8;">
          Gestiona usuarios, revisa solicitudes, verifica pagos, responde quejas y comunicate directamente con tus clientes desde este panel.
        </p>
        <div style="display:flex;gap:10px;margin-top:18px;flex-wrap:wrap;">
          <a href="admin.php?tab=solicitudes" style="padding:10px 18px;border-radius:50px;background:linear-gradient(135deg,var(--pink-dark),var(--plum));color:#fff;text-decoration:none;font-weight:700;font-size:13px;"><i class="fa-solid fa-concierge-bell"></i> Ver Solicitudes</a>
          <a href="admin.php?tab=pagos"       style="padding:10px 18px;border-radius:50px;background:rgba(255,255,255,.7);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-weight:700;font-size:13px;"><i class="fa-solid fa-credit-card"></i> Ver Pagos</a>
          <a href="admin.php?tab=mensajes"    style="padding:10px 18px;border-radius:50px;background:rgba(255,255,255,.7);border:1.5px solid rgba(199,116,149,.3);color:var(--plum);text-decoration:none;font-weight:700;font-size:13px;"><i class="fa-solid fa-paper-plane"></i> Enviar Mensaje</a>
        </div>
      </div>
    </div>

    <!-- ══════════ USUARIOS ══════════ -->
    <div class="tab-panel <?= $tab==='usuarios'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-users" style="color:var(--pink-dark)"></i> Gestion de Usuarios</div>
      <div class="glass-card" style="padding:22px;">
        <div class="table-wrap">
          <table>
            <thead><tr><th>#</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Cambiar Rol</th><th>Eliminar</th></tr></thead>
            <tbody>
              <?php if ($r_usu && mysqli_num_rows($r_usu) > 0): while ($u = mysqli_fetch_assoc($r_usu)): ?>
              <tr>
                <td><?= $u['id'] ?></td>
                <td>
                  <i class="fa-solid fa-user" style="color:var(--pink-dark);margin-right:5px;"></i>
                  <?= htmlspecialchars($u['usuario']) ?>
                  <?php if ((int)$u['id'] === $sid): ?><span class="yo-tag">Tu cuenta</span><?php endif; ?>
                </td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><span class="badge <?= $u['rol']==='admin'?'b-admin':'b-cliente' ?>"><?= $u['rol'] ?></span></td>
                <td>
                  <?php if ((int)$u['id'] !== $sid): ?>
                  <form method="POST" action="admin.php?tab=usuarios" class="rol-form">
                    <input type="hidden" name="id_usuario" value="<?= $u['id'] ?>">
                    <select name="nuevo_rol" class="rol-sel">
                      <option value="cliente" <?= $u['rol']==='cliente'?'selected':'' ?>>cliente</option>
                      <option value="admin"   <?= $u['rol']==='admin'  ?'selected':'' ?>>admin</option>
                    </select>
                    <button type="submit" name="cambiar_rol" class="btn-xs bx-pink"><i class="fa-solid fa-rotate"></i> Cambiar</button>
                  </form>
                  <?php else: ?><span style="color:var(--text-light);font-size:12px;">—</span><?php endif; ?>
                </td>
                <td>
                  <?php if ((int)$u['id'] !== $sid): ?>
                  <form method="POST" action="admin.php?tab=usuarios"
                        onsubmit="return confirm('Eliminar a <?= htmlspecialchars($u['usuario'],ENT_QUOTES) ?>?')">
                    <input type="hidden" name="id_usuario" value="<?= $u['id'] ?>">
                    <button type="submit" name="eliminar_usuario" class="btn-xs bx-red"><i class="fa-solid fa-trash"></i> Eliminar</button>
                  </form>
                  <?php else: ?><span style="color:var(--text-light);font-size:12px;">—</span><?php endif; ?>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--text-light);">No hay usuarios.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════ SOLICITUDES ══════════ -->
    <div class="tab-panel <?= $tab==='solicitudes'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-concierge-bell" style="color:var(--pink-dark)"></i> Solicitudes de Servicio</div>
      <div class="glass-card" style="padding:22px;">
        <div class="table-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Servicio</th><th>Fecha</th><th>Estado</th><th>Actualizar</th><th>Mensaje</th></tr></thead>
            <tbody>
              <?php if ($r_sol && mysqli_num_rows($r_sol) > 0): while ($s = mysqli_fetch_assoc($r_sol)):
                $est = $s['estado'] ?? 'pendiente';
                $cls = $est==='pendiente'?'b-pending':($est==='en proceso'?'b-progress':'b-done');
                $f   = $s['fecha'] ? substr($s['fecha'],0,10) : '—';
              ?>
              <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['nombre']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($s['correo']) ?></td>
                <td><?= htmlspecialchars($s['servicio']) ?></td>
                <td><?= $f ?></td>
                <td><span class="badge <?= $cls ?>"><?= $est ?></span></td>
                <td>
                  <form method="POST" action="admin.php?tab=solicitudes" class="rol-form">
                    <input type="hidden" name="sol_id" value="<?= $s['id'] ?>">
                    <select name="nuevo_estado" class="rol-sel">
                      <option value="pendiente"  <?= $est==='pendiente' ?'selected':'' ?>>pendiente</option>
                      <option value="en proceso" <?= $est==='en proceso'?'selected':'' ?>>en proceso</option>
                      <option value="resuelto"   <?= $est==='resuelto'  ?'selected':'' ?>>resuelto</option>
                    </select>
                    <button type="submit" name="cambiar_estado_sol" class="btn-xs bx-pink"><i class="fa-solid fa-rotate"></i></button>
                  </form>
                </td>
                <td>
                  <button class="btn-xs bx-grn"
                    onclick="abrirMsg('<?= htmlspecialchars($s['correo'],ENT_QUOTES) ?>','<?= htmlspecialchars($s['nombre'],ENT_QUOTES) ?>','<?= htmlspecialchars($s['servicio'],ENT_QUOTES) ?>')">
                    <i class="fa-solid fa-paper-plane"></i> Contactar
                  </button>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--text-light);">No hay solicitudes.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════ PAGOS ══════════ -->
    <div class="tab-panel <?= $tab==='pagos'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-credit-card" style="color:var(--pink-dark)"></i> Pagos Registrados</div>
      <div class="glass-card" style="padding:22px;">
        <div class="table-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Servicio</th><th>Metodo</th><th>Referencia</th><th>Monto COP</th><th>Fecha</th><th>Estado</th><th>Actualizar</th></tr></thead>
            <tbody>
              <?php if ($r_pag && mysqli_num_rows($r_pag) > 0): while ($p = mysqli_fetch_assoc($r_pag)):
                $pest = $p['estado_pago'] ?? 'pendiente';
                $pcls = $pest==='verificado'?'b-done':($pest==='rechazado'?'b-pending':'b-progress');
                $pf   = $p['fecha'] ? substr($p['fecha'],0,10) : '—';
              ?>
              <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td style="font-size:11px;"><?= htmlspecialchars($p['correo']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($p['servicio']) ?></td>
                <td>
                  <span class="badge b-cliente"><?= htmlspecialchars($p['metodo_pago']) ?></span>
                </td>
                <td style="font-size:12px;"><?= htmlspecialchars($p['referencia'] ?: '—') ?></td>
                <td style="font-weight:700;color:var(--plum);">
                  <?= $p['monto'] > 0 ? '$'.number_format($p['monto'],0,',','.') : '—' ?>
                </td>
                <td><?= $pf ?></td>
                <td><span class="badge <?= $pcls ?>"><?= $pest ?></span></td>
                <td>
                  <form method="POST" action="admin.php?tab=pagos" class="pago-estado-form">
                    <input type="hidden" name="pago_id" value="<?= $p['id'] ?>">
                    <select name="estado_pago" class="rol-sel">
                      <option value="pendiente"  <?= $pest==='pendiente' ?'selected':'' ?>>pendiente</option>
                      <option value="verificado" <?= $pest==='verificado'?'selected':'' ?>>verificado</option>
                      <option value="rechazado"  <?= $pest==='rechazado' ?'selected':'' ?>>rechazado</option>
                    </select>
                    <button type="submit" name="actualizar_pago" class="btn-xs bx-pink"><i class="fa-solid fa-rotate"></i></button>
                  </form>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="10" style="text-align:center;padding:30px;color:var(--text-light);">No hay pagos registrados.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════ QUEJAS ══════════ -->
    <div class="tab-panel <?= $tab==='quejas'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-comment-dots" style="color:var(--pink-dark)"></i> Quejas y Sugerencias</div>
      <div class="glass-card" style="padding:22px;">
        <div class="table-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Tipo</th><th>Mensaje</th><th>Fecha</th><th>Responder</th></tr></thead>
            <tbody>
              <?php if ($r_q && mysqli_num_rows($r_q) > 0): while ($q = mysqli_fetch_assoc($r_q)):
                $qt  = $q['tipo'] ?? 'queja';
                $qcls= $qt==='queja'?'b-pending':'b-progress';
                $qf  = $q['fecha'] ? substr($q['fecha'],0,10) : '—';
                $qm  = htmlspecialchars(mb_substr($q['mensaje']??'',0,80));
                if(mb_strlen($q['mensaje']??'')>80) $qm.='…';
              ?>
              <tr>
                <td><?= $q['id'] ?></td>
                <td><?= htmlspecialchars($q['nombre']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($q['correo']) ?></td>
                <td><span class="badge <?= $qcls ?>"><?= $qt ?></span></td>
                <td style="max-width:220px;"><?= $qm ?></td>
                <td><?= $qf ?></td>
                <td>
                  <button class="btn-xs bx-grn"
                    onclick="abrirMsg('<?= htmlspecialchars($q['correo'],ENT_QUOTES) ?>','<?= htmlspecialchars($q['nombre'],ENT_QUOTES) ?>','Respuesta a tu <?= $qt ?>')">
                    <i class="fa-solid fa-reply"></i> Responder
                  </button>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--text-light);">No hay quejas.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ══════════ MENSAJES ══════════ -->
    <div class="tab-panel <?= $tab==='mensajes'?'active':'' ?>">
      <div class="sec-title"><i class="fa-solid fa-paper-plane" style="color:var(--pink-dark)"></i> Comunicacion con Clientes</div>

      <!-- Formulario nuevo mensaje -->
      <div class="glass-card" style="padding:26px;margin-bottom:22px;">
        <h3 style="font-family:'Playfair Display',serif;color:var(--plum);margin-bottom:18px;font-size:18px;">
          <i class="fa-solid fa-envelope" style="color:var(--pink-dark);margin-right:8px;"></i>
          Enviar mensaje a un cliente
        </h3>
        <form method="POST" action="admin.php?tab=mensajes">
          <div class="msg-form-grid">
            <div class="form-group">
              <label>Seleccionar cliente (por solicitud)</label>
              <select id="sel-cliente" class="rol-sel" style="width:100%;padding:12px;border-radius:12px;font-size:13px;"
                      onchange="autoFill(this)">
                <option value="">— Selecciona un cliente —</option>
                <?php
                  mysqli_data_seek($r_correos, 0);
                  while ($c = mysqli_fetch_assoc($r_correos)):
                ?>
                <option value="<?= htmlspecialchars($c['correo'],ENT_QUOTES) ?>"
                        data-nombre="<?= htmlspecialchars($c['nombre'],ENT_QUOTES) ?>">
                  <?= htmlspecialchars($c['nombre']) ?> — <?= htmlspecialchars($c['correo']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Nombre del destinatario</label>
              <input type="text" name="para_nombre" id="inp-pnombre" placeholder="Nombre" required
                     style="padding:12px;border-radius:12px;">
            </div>
            <div class="form-group">
              <label>Correo del destinatario</label>
              <input type="email" name="para_correo" id="inp-pcorreo" placeholder="correo@email.com" required
                     style="padding:12px;border-radius:12px;">
            </div>
            <div class="form-group">
              <label>Asunto</label>
              <input type="text" name="asunto" id="inp-asunto" placeholder="Asunto del mensaje" required
                     style="padding:12px;border-radius:12px;">
            </div>
            <div class="form-group msg-form-full">
              <label>Mensaje</label>
              <textarea name="cuerpo" placeholder="Escribe tu mensaje aquí..." required
                        style="min-height:120px;padding:12px;border-radius:12px;"></textarea>
            </div>
          </div>
          <button type="submit" name="enviar_msg" class="btn-submit" style="max-width:220px;">
            <i class="fa-solid fa-paper-plane"></i> Enviar Mensaje
          </button>
        </form>
      </div>

      <!-- Historial de mensajes -->
      <div class="glass-card" style="padding:22px;">
        <h3 style="font-family:'Playfair Display',serif;color:var(--plum);margin-bottom:16px;font-size:18px;">
          <i class="fa-solid fa-clock-rotate-left" style="color:var(--pink-dark);margin-right:8px;"></i>
          Historial de mensajes enviados
        </h3>
        <?php if ($r_msg && mysqli_num_rows($r_msg) > 0): while ($m = mysqli_fetch_assoc($r_msg)):
          $mf = $m['fecha'] ? date('d/m/Y H:i', strtotime($m['fecha'])) : '—';
        ?>
        <div class="msg-card">
          <div class="mc-head">
            <div>
              <div class="mc-name"><i class="fa-solid fa-user" style="color:var(--pink-dark);margin-right:5px;"></i><?= htmlspecialchars($m['para_nombre']) ?></div>
              <div style="font-size:12px;color:var(--text-light);margin-top:2px;"><?= htmlspecialchars($m['para_correo']) ?></div>
            </div>
            <div class="mc-date"><?= $mf ?></div>
          </div>
          <div class="mc-sub"><i class="fa-solid fa-tag" style="color:var(--pink-dark);margin-right:4px;"></i><?= htmlspecialchars($m['asunto']) ?></div>
          <div class="mc-body"><?= nl2br(htmlspecialchars($m['cuerpo'])) ?></div>
        </div>
        <?php endwhile; else: ?>
          <p style="text-align:center;color:var(--text-light);padding:20px;">No has enviado mensajes aun.</p>
        <?php endif; ?>
      </div>
    </div>

  </main>
</div>

<!-- MODAL mensaje rápido (desde botón Contactar/Responder) -->
<div id="modal-msg" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:34px;width:100%;max-width:500px;margin:20px;box-shadow:0 16px 40px rgba(0,0,0,.2);">
    <h3 style="font-family:'Playfair Display',serif;color:var(--plum);margin-bottom:18px;font-size:20px;">
      <i class="fa-solid fa-paper-plane" style="color:var(--pink-dark);margin-right:8px;"></i> Enviar Mensaje
    </h3>
    <form method="POST" action="admin.php?tab=mensajes">
      <div class="form-group">
        <label>Para</label>
        <input type="text"  name="para_nombre" id="m-nombre" readonly
               style="padding:11px;border-radius:12px;background:#f9f9f9;">
      </div>
      <div class="form-group">
        <label>Correo</label>
        <input type="email" name="para_correo" id="m-correo" readonly
               style="padding:11px;border-radius:12px;background:#f9f9f9;">
      </div>
      <div class="form-group">
        <label>Asunto</label>
        <input type="text" name="asunto" id="m-asunto"
               style="padding:11px;border-radius:12px;" required>
      </div>
      <div class="form-group">
        <label>Mensaje</label>
        <textarea name="cuerpo" style="min-height:100px;padding:11px;border-radius:12px;" required
                  placeholder="Escribe tu mensaje..."></textarea>
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" name="enviar_msg" class="btn-submit" style="flex:1;">
          <i class="fa-solid fa-paper-plane"></i> Enviar
        </button>
        <button type="button" onclick="cerrarModal()"
                style="flex:1;padding:14px;border-radius:50px;border:1.5px solid rgba(199,116,149,.3);background:transparent;color:var(--plum);font-weight:700;font-size:14px;cursor:pointer;font-family:'Nunito',sans-serif;">
          Cancelar
        </button>
      </div>
    </form>
  </div>
</div>

<footer><p>2025 SOF-LEX CODE · Panel Administrativo</p></footer>

<script>
function abrirMsg(correo, nombre, asunto) {
  document.getElementById('m-correo').value = correo;
  document.getElementById('m-nombre').value = nombre;
  document.getElementById('m-asunto').value = 'Re: ' + asunto;
  document.getElementById('modal-msg').style.display = 'flex';
}
function cerrarModal() {
  document.getElementById('modal-msg').style.display = 'none';
}
// Cerrar modal al click fuera
document.getElementById('modal-msg').addEventListener('click', function(e) {
  if (e.target === this) cerrarModal();
});

// Auto-llenar campos de mensaje desde selector de clientes
function autoFill(sel) {
  const opt = sel.options[sel.selectedIndex];
  if (opt.value) {
    document.getElementById('inp-pcorreo').value = opt.value;
    document.getElementById('inp-pnombre').value = opt.dataset.nombre || '';
  }
}
</script>
</body>
</html>
