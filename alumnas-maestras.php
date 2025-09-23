<?php
// ====== alumnas-maestras.php ======
session_start();
include_once("conexion.php");
if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }

$Nombre = $_SESSION['nombre'] ?? '';
$Apat   = $_SESSION['apat']   ?? '';
$Amat   = $_SESSION['amat']   ?? '';
mysqli_set_charset($conexion, 'utf8mb4');

function e($s){ return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8'); }
function post($k,$d=null){ return $_POST[$k] ?? $d; }
function is_post(){ return (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'); }

/* -------------------- INSERTS (Agregar) -------------------- */
if (is_post()) {
  $action = post('action','');

  if ($action === 'add_alumna') {
    $nombre    = trim(post('nombre',''));
    $apat      = trim(post('apat',''));
    $amat      = trim(post('amat',''));
    $telefono  = trim(post('telefono',''));
    $correo    = trim(post('correo',''));
    $direccion = trim(post('direccion',''));
    $desc_ap   = intval(post('descuento_aplicado', 0)) ? 1 : 0;
    $tipo_desc = trim(post('tipo_descuento',''));

    if ($nombre === '') {
      $_SESSION['flash_err'] = 'El nombre de la alumna es obligatorio.';
    } else {
      $sql  = "INSERT INTO alumnas (nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento)
               VALUES (?,?,?,?,?,?,?,?)";
      $stmt = mysqli_prepare($conexion, $sql);
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssis', $nombre, $apat, $amat, $telefono, $correo, $direccion, $desc_ap, $tipo_desc);
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_' . ($ok ? 'ok' : 'err')] = $ok ? 'Alumna agregada.' : ('Error: '.mysqli_error($conexion));
      } else {
        $_SESSION['flash_err'] = 'Error preparando consulta: '.mysqli_error($conexion);
      }
    }
    header("Location: alumnas-maestras.php"); exit;
  }

  if ($action === 'add_maestra') {
    $nombre   = trim(post('nombre',''));
    $base     = trim(post('base',''));
    $acuerdo  = trim(post('acuerdo',''));
    $gastos   = (post('gastos','') === '' ? null : floatval(post('gastos',0)));
    $porc_gan = (post('porcentaje_ganancia','') === '' ? null : floatval(post('porcentaje_ganancia',0)));

    if ($nombre === '') {
      $_SESSION['flash_err'] = 'El nombre de la maestra es obligatorio.';
    } else {
      $sql  = "INSERT INTO maestras (nombre, base, acuerdo, gastos, porcentaje_ganancia) VALUES (?,?,?,?,?)";
      $stmt = mysqli_prepare($conexion, $sql);
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssdd', $nombre, $base, $acuerdo, $gastos, $porc_gan);
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_' . ($ok ? 'ok' : 'err')] = $ok ? 'Maestra agregada.' : ('Error: '.mysqli_error($conexion));
      } else {
        $_SESSION['flash_err'] = 'Error preparando consulta: '.mysqli_error($conexion);
      }
    }
    header("Location: alumnas-maestras.php"); exit;
  }
}

/* -------------------- Listados -------------------- */
$sqlAlumnas  = "SELECT id_alumna, nombre, apat, amat, telefono, correo FROM alumnas ORDER BY id_alumna ASC";
$sqlMaestras = "SELECT id_maestra, nombre, base, acuerdo, gastos, porcentaje_ganancia FROM maestras ORDER BY id_maestra ASC";

$alumnas  = mysqli_query($conexion, $sqlAlumnas);
$maestras = mysqli_query($conexion, $sqlMaestras);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alumnas / Maestras - DO SPA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilos globales -->
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/talleres-curso.css">
  <!-- CSS específico -->
  <link rel="stylesheet" href="css/alumnas-maestras.css">
  <!-- (Opcional) datatables si lo usas -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- Header / Sidebar -->
  <header class="app-header">
    <div class="left"><h1 class="app-title">DO SPA</h1></div>
    <div class="right"><span class="user">Hola, <?php echo e($_SESSION['nombre'] ?? ''); ?></span></div>
  </header>

  <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li class="active"><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Alumnas / Maestras</span></a></li>
        <li><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
      </ul>
    </aside>

  <!-- Main -->
  <main class="main">
     <!-- Topbar -->
      <header class="topbar">
        <div class="user-info">
          <span><?php echo htmlspecialchars("$Nombre $Apat $Amat", ENT_QUOTES, 'UTF-8'); ?></span>
          <a href="muerte.php"><img src="img/logout.png" id="btn-logout" alt="Salir"></a>
        </div>
      </header>


    <div class="inventario alumnas-maestras">
      <?php if (!empty($_SESSION['flash_ok'])): ?>
        <div class="alert alert-success"><?php echo e($_SESSION['flash_ok']); unset($_SESSION['flash_ok']); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['flash_err'])): ?>
        <div class="alert alert-error"><?php echo e($_SESSION['flash_err']); unset($_SESSION['flash_err']); ?></div>
      <?php endif; ?>

      <center>
          <div class="grid-2">
                  <h2>Alumnas / Clientes</h2>
                  <!-- ================== Card Alumnas ================== -->
                  <section class="card">
                    <header>
                      <h3>Alumnas</h3>
                      <div class="section-actions">
                        <img src="img/agregar.png" class="btn-mini btn-primary" alt="Agregar" id="btnNuevaAlumna" class="icon btn-agregar" width="20" data-tipo="curso" title="Agregar">

                      </div>
                    </header>
                    <div class="body">
                      <!-- Modal Alumna -->
                      <div class="modal" id="modalAlumna">
                        <div class="modal-content">
                          <header>
                            <h3>Nueva alumna</h3>
                            <button class="close" data-close="#modalAlumna">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="add_alumna">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido paterno</label>
                                <input type="text" name="apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido materno</label>
                                <input type="text" name="amat">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion">
                              </div>
                              <div class="form-control">
                                <label>Descuento aplicado</label>
                                <select name="descuento_aplicado">
                                  <option value="0">No</option>
                                  <option value="1">Sí</option>
                                </select>
                              </div>
                              <div class="form-control">
                                <label>Tipo de descuento</label>
                                <input type="text" name="tipo_descuento">
                              </div>
                            </div>
                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!-- Tabla -->
                      <div class="dataTable-container">
                        <table class="display" id="tablaAlumnas">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Apellidos</th>
                              <th>Teléfono</th>
                              <th>Correo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($alumnas === false): ?>
                              <tr><td colspan="5">Error SQL (alumnas): <?php echo e(mysqli_error($conexion)); ?></td></tr>
                            <?php elseif (mysqli_num_rows($alumnas) === 0): ?>
                              <tr><td colspan="5">Sin alumnas</td></tr>
                            <?php else: ?>
                              <?php while ($a = mysqli_fetch_assoc($alumnas)): ?>
                                <tr>
                                  <td><?php echo e($a['id_alumna']); ?></td>
                                  <td><?php echo e($a['nombre']); ?></td>
                                  <td><?php echo e(($a['apat'] ?? '').' '.($a['amat'] ?? '')); ?></td>
                                  <td><?php echo e($a['telefono']); ?></td>
                                  <td><?php echo e($a['correo']); ?></td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </section>

                  <!-- ================== Card Maestras ================== -->
                  <section class="card">
                    <header>
                      <h3>Clientes</h3>
                      <div class="section-actions">
                        <img src="img/agregar.png" class="btn-mini btn-primary" alt="Agregar" id="btnNuevaMaestra"class="icon btn-agregar" width="20" data-tipo="curso" title="Agregar sesión de curso">
                       
                      </div>
                    </header>
                    <div class="body">
                      <!-- Panel de formulario (toggle) -->
                     <!-- Modal Maestra -->
                      <div class="modal" id="modalMaestra">
                        <div class="modal-content">
                          <header>
                            <h3>Nuev Cliente</h3>
                            <button class="close" data-close="#modalMaestra">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="add_maestra">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Base</label>
                                <input type="text" name="base">
                              </div>
                              <div class="form-control">
                                <label>Acuerdo</label>
                                <input type="text" name="acuerdo">
                              </div>
                              <div class="form-control">
                                <label>% Ganancia</label>
                                <input type="number" step="0.01" name="porcentaje_ganancia">
                              </div>
                            </div>
                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!-- Tabla -->
                      <div class="dataTable-container">
                        <table class="display" id="tablaMaestras">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Base</th>
                              <th>Acuerdo</th>
                              <th>Gastos</th>
                              <th>% Ganancia</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($maestras === false): ?>
                              <tr><td colspan="6">Error SQL (maestras): <?php echo e(mysqli_error($conexion)); ?></td></tr>
                            <?php elseif (mysqli_num_rows($maestras) === 0): ?>
                              <tr><td colspan="6">Sin maestras</td></tr>
                            <?php else: ?>
                              <?php while ($m = mysqli_fetch_assoc($maestras)): ?>
                                <tr>
                                  <td><?php echo e($m['id_maestra']); ?></td>
                                  <td><?php echo e($m['nombre']); ?></td>
                                  <td><?php echo e($m['base']); ?></td>
                                  <td><?php echo e($m['acuerdo']); ?></td>
                                  <td><?php echo e(number_format((float)($m['gastos'] ?? 0), 2)); ?></td>
                                  <td><?php echo e(number_format((float)($m['porcentaje_ganancia'] ?? 0), 2)); ?>%</td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </section>
          </div>
      </center>
     
    </div>
  </main>

  <!-- Scripts -->
  <script src="librerias/tables.js" defer></script>
  <script>
 document.addEventListener('DOMContentLoaded', () => {
  const openModal = (id) => document.querySelector(id)?.classList.add('open');
  const closeModal = (id) => document.querySelector(id)?.classList.remove('open');

  document.getElementById('btnNuevaAlumna')?.addEventListener('click', () => openModal('#modalAlumna'));
  document.getElementById('btnNuevaMaestra')?.addEventListener('click', () => openModal('#modalMaestra'));

  document.querySelectorAll('.modal .close').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-close');
      closeModal(target);
    });
  });

  // Cerrar al hacer clic fuera del contenido
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.remove('open');
    });
  });
});

  </script>
</body>
</html>
