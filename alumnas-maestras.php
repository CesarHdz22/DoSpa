<?php
// ====== alumnas-maestras.php ======
session_start();
include_once("conexion.php");
if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }
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
    <nav>
      <ul>
        <li><a href="main.php"><i class="fa fa-gauge"></i><span>Dashboard</span></a></li>
        <li><a href="talleres-cursos.php"><i class="fa fa-chalkboard"></i><span>Talleres / Cursos</span></a></li>
        <li class="active"><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Alumnas / Maestras</span></a></li>
        <li><a href="ventas.php"><i class="fa fa-cart-shopping"></i><span>Ventas</span></a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main">
    <div class="inventario alumnas-maestras">
      <?php if (!empty($_SESSION['flash_ok'])): ?>
        <div class="alert alert-success"><?php echo e($_SESSION['flash_ok']); unset($_SESSION['flash_ok']); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['flash_err'])): ?>
        <div class="alert alert-error"><?php echo e($_SESSION['flash_err']); unset($_SESSION['flash_err']); ?></div>
      <?php endif; ?>

      <div class="page-title"><i class="fa-solid fa-users"></i> Alumnas / Maestras</div>

      <div class="grid-2">
        <!-- ================== Card Alumnas ================== -->
        <section class="card">
          <header>
            <h3>Alumnas</h3>
            <div class="section-actions">
              <button class="btn-mini btn-primary" id="btnNuevaAlumna"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </header>
          <div class="body">
            <!-- Panel de formulario (toggle) -->
            <div class="form-panel" id="panelFormAlumna">
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
                  <div class="form-control" style="grid-column:1/-1;">
                    <label>Dirección</label>
                    <input type="text" name="direccion">
                  </div>
                  <div class="form-control">
                    <label>Descuento aplicado</label>
                    <select name="descuento_aplicado">
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                    </select>
                    <div class="hint">Si es "Sí", especifica el tipo</div>
                  </div>
                  <div class="form-control">
                    <label>Tipo de descuento</label>
                    <input type="text" name="tipo_descuento" placeholder="10%, Estudiante, Promo, etc.">
                  </div>
                </div>
                <div class="form-actions">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar alumna</button>
                </div>
              </form>
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
            <h3>Maestras</h3>
            <div class="section-actions">
              <button class="btn-mini btn-primary" id="btnNuevaMaestra"><i class="fa fa-plus"></i> Agregar</button>
            </div>
          </header>
          <div class="body">
            <!-- Panel de formulario (toggle) -->
            <div class="form-panel" id="panelFormMaestra">
              <form method="post" autocomplete="off">
                <input type="hidden" name="action" value="add_maestra">
                <div class="form-grid">
                  <div class="form-control">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required>
                  </div>
                  <div class="form-control">
                    <label>Base</label>
                    <input type="text" name="base" placeholder="Localidad">
                  </div>
                  <div class="form-control">
                    <label>Acuerdo</label>
                    <input type="text" name="acuerdo" placeholder="comision, pago fijo, etc.">
                  </div>

                  <div class="form-control">
                    <label>% Ganancia</label>
                    <input type="number" step="0.01" name="porcentaje_ganancia" placeholder="0.00">
                  </div>
                </div>
                <div class="form-actions">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar maestra</button>
                </div>
              </form>
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
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Datatables
      const DataTable = window.simpleDatatables?.DataTable;
      if (DataTable) {
        try { new DataTable('#tablaAlumnas',  { searchable: true, fixedHeight: true }); } catch(e){}
        try { new DataTable('#tablaMaestras', { searchable: true, fixedHeight: true }); } catch(e){}
      }

      // Toggle formularios al darle "Agregar", como en taller/curso
      const btnAl  = document.getElementById('btnNuevaAlumna');
      const pnlAl  = document.getElementById('panelFormAlumna');
      const btnMa  = document.getElementById('btnNuevaMaestra');
      const pnlMa  = document.getElementById('panelFormMaestra');

      btnAl?.addEventListener('click', () => {
        pnlAl.classList.toggle('open');
        if (pnlAl.classList.contains('open')) {
          pnlMa.classList.remove('open');
          pnlAl.querySelector('input[name="nombre"]')?.focus();
          pnlAl.scrollIntoView({ behavior:'smooth', block:'start' });
        }
      });

      btnMa?.addEventListener('click', () => {
        pnlMa.classList.toggle('open');
        if (pnlMa.classList.contains('open')) {
          pnlAl.classList.remove('open');
          pnlMa.querySelector('input[name="nombre"]')?.focus();
          pnlMa.scrollIntoView({ behavior:'smooth', block:'start' });
        }
      });
    });
  </script>
</body>
</html>
