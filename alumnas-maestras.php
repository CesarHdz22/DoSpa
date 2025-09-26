<?php
    session_start();
    include_once("conexion.php");
    if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }

    /* Normalizar / inicializar variables de sesión — evitar Undefined variable */
    $Id_Usuario = $_SESSION['Id_Usuario'] ?? $_SESSION['id_usuario'] ?? null;
    $Nombre     = $_SESSION['nombre']     ?? $_SESSION['Nombre']     ?? '';
    $Apat       = $_SESSION['apat']       ?? $_SESSION['Apat']       ?? '';
    $Amat       = $_SESSION['amat']       ?? $_SESSION['Amat']       ?? '';
    $Cargo      = $_SESSION['Cargo']      ?? $_SESSION['cargo']      ?? '';

    mysqli_set_charset($conexion, 'utf8mb4');

    function e($s){ return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8'); }
    function post($k,$d=null){ return $_POST[$k] ?? $d; }
    function is_post(){ return ($_SERVER['REQUEST_METHOD'] === 'POST'); }

    /* -------------------- INSERTS -------------------- */
    if (is_post()) {
      $action = post('action','');

      // === Alumna ===
      if ($action === 'add_alumna') {
        $sql  = "INSERT INTO alumnas (nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento)
                VALUES (?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $desc_ap = intval(post('descuento_aplicado',0));
        mysqli_stmt_bind_param(
          $stmt, "ssssssis",
          post('nombre'), post('apat'), post('amat'),
          post('telefono'), post('correo'), post('direccion'),
          $desc_ap, post('tipo_descuento')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Alumna agregada.' : 'Error: '.mysqli_error($conexion);
        header("Location: alumnas-maestras.php"); exit;
      }

      // === Maestra ===
      if ($action === 'add_maestra') {
        $sql  = "INSERT INTO maestras (nombre, base, acuerdo, gastos, porcentaje_ganancia)
                VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $gastos = (post('gastos','')===''?null:floatval(post('gastos')));
        $porc   = (post('porcentaje_ganancia','')===''?null:floatval(post('porcentaje_ganancia')));
        mysqli_stmt_bind_param(
          $stmt, "sssdd",
          post('nombre'), post('base'), post('acuerdo'),
          $gastos, $porc
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Maestra agregada.' : 'Error: '.mysqli_error($conexion);
        header("Location: alumnas-maestras.php"); exit;
      }

      // === Cliente ===
      if ($action === 'add_cliente') {
        $sql  = "INSERT INTO clientes (nombre, apat, amat, correo, telefono, direccion)
                VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
          $stmt, "ssssss",
          post('nombre'), post('apat'), post('amat'),
          post('correo'), post('telefono'), post('direccion')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Cliente agregado.' : 'Error: '.mysqli_error($conexion);
        header("Location: alumnas-maestras.php"); exit;
      }

      // === Usuario ===
      if ($action === 'add_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
        $sql  = "INSERT INTO usuarios (Nombre, Apat, Amat, Correo, User, Pass, Cargo)
                VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $pass = password_hash(post('Pass'), PASSWORD_DEFAULT); // 🔐 recomendado
        mysqli_stmt_bind_param(
          $stmt, "sssssss",
          post('Nombre'), post('Apat'), post('Amat'),
          post('Correo'), post('User'), $pass, post('Cargo')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Usuario agregado.' : 'Error: '.mysqli_error($conexion);
        header("Location: alumnas-maestras.php"); exit;
      }
    }

    /* -------------------- Listados -------------------- */
    $sqlAlumnas  = "SELECT id_alumna, nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento 
                    FROM alumnas ORDER BY id_alumna ASC";
    $sqlMaestras = "SELECT id_maestra, nombre, base, acuerdo, gastos, porcentaje_ganancia 
                    FROM maestras ORDER BY id_maestra ASC";
    $sqlClientes = "SELECT * FROM clientes";
    $sqlUsuarios = "SELECT * FROM usuarios";

    $alumnas  = mysqli_query($conexion, $sqlAlumnas);
    $maestras = mysqli_query($conexion, $sqlMaestras);
    $clientes = mysqli_query($conexion, $sqlClientes);
    $usuarios = mysqli_query($conexion, $sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios - DO SPA</title>
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
  <style>
    /* ================================
      Estilos generales de tablas (igual inventario)
      ================================ */
    table.display {
      width: 100%;
      border-collapse: collapse;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin-top: 10px;
      font-size: 14px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border-radius: 8px;
      overflow: hidden; /* redondeo también en bordes de tabla */
    }

    /* Encabezado */
    table.display thead {
      background-color: #f9f9f9;
    }

    table.display th {
      text-align: left;
      padding: 10px 14px;
      font-weight: 600;
      border-bottom: 2px solid #e0e0e0;
      color: #333;
    }

    /* Celdas */
    table.display td {
      padding: 10px 14px;
      border-bottom: 1px solid #eee;
      color: #444;
    }

    /* Filas alternas */
    table.display tbody tr:nth-child(even) {
      background-color: #fafafa;
    }

    /* Hover fila */
    table.display tbody tr:hover {
      background-color: #E9C6C4; /* mismo efecto que en inventario */
      transition: background 0.2s ease;
    }

    /* ================================
      Responsive (pantallas chicas)
      ================================ */
    @media screen and (max-width: 600px) {
      table.display th,
      table.display td {
        font-size: 13px;
        padding: 8px 10px;
      }
    }

  </style>
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
        <li class="active"><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
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
                  <h2>Usuarios</h2>
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
                              <th>Apellido Paterno</th>
                              <th>Apellido Materno</th>
                              <th>Teléfono</th>
                              <th>Correo</th>
                              <th>Dirección</th>
                              <th>Descuento aplicado</th>
                              <th>Tipo de descuento</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($alumnas === false): ?>
                              <tr><td colspan="9">Error SQL (alumnas): <?php echo e(mysqli_error($conexion)); ?></td></tr>
                            <?php elseif (mysqli_num_rows($alumnas) === 0): ?>
                              <tr><td colspan="9">Sin alumnas</td></tr>
                            <?php else: ?>
                              <?php while ($a = mysqli_fetch_assoc($alumnas)): ?>
                                <tr>
                                  <td><?php echo e($a['id_alumna']); ?></td>
                                  <td><?php echo e($a['nombre']); ?></td>
                                  <td><?php echo e($a['apat']); ?></td>
                                  <td><?php echo e($a['amat']); ?></td>
                                  <td><?php echo e($a['telefono']); ?></td>
                                  <td><?php echo e($a['correo']); ?></td>
                                  <td><?php echo e($a['direccion']); ?></td>
                                  <td><?php echo $a['descuento_aplicado'] ? 'Sí' : 'No'; ?></td>
                                  <td><?php echo e($a['tipo_descuento']); ?></td>
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

                  <!-- ================== Card Clientes ================== -->
                  <section class="card">
                    <header>
                      <h3>Clientes</h3>
                      <div class="section-actions">
                        <img src="img/agregar.png" class="btn-mini btn-primary" alt="Agregar" id="btnNuevoCliente" class="icon btn-agregar" width="20" data-tipo="cliente" title="Agregar cliente">
                      </div>
                    </header>
                    <div class="body">

                      <!-- Modal Clientes -->
                      <div class="modal" id="modalClientes">
                        <div class="modal-content">
                          <header>
                            <h3>Nuevo Cliente</h3>
                            <button class="close" data-close="#modalClientes">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="add_cliente">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="amat">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion">
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
                        <table class="display" id="tablaClientes">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Apellido Paterno</th>
                              <th>Apellido Materno</th>
                              <th>Correo</th>
                              <th>Teléfono</th>
                              <th>Dirección</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($clientes === false): ?>
                              <tr><td colspan="7">Error SQL (clientes): <?php echo e(mysqli_error($conexion)); ?></td></tr>
                            <?php elseif (mysqli_num_rows($clientes) === 0): ?>
                              <tr><td colspan="7">Sin clientes</td></tr>
                            <?php else: ?>
                              <?php while ($c = mysqli_fetch_assoc($clientes)): ?>
                                <tr>
                                  <td><?php echo e($c['id_cliente']); ?></td>
                                  <td><?php echo e($c['nombre']); ?></td>
                                  <td><?php echo e($c['apat']); ?></td>
                                  <td><?php echo e($c['amat']); ?></td>
                                  <td><?php echo e($c['correo']); ?></td>
                                  <td><?php echo e($c['telefono']); ?></td>
                                  <td><?php echo e($c['direccion']); ?></td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </section>


                  <!-- ================== Card Usuarios ================== -->
                  <?php if (strcasecmp(trim($Cargo), 'Admin') === 0) { ?>
                  <section class="card">
                    <header>
                      <h3>Usuarios</h3>
                      <div class="section-actions">
                        <img src="img/agregar.png" class="btn-mini btn-primary" alt="Agregar" id="btnNuevoUsuario" class="icon btn-agregar" width="20" data-tipo="usuario" title="Agregar usuario">
                      </div>
                    </header>
                    <div class="body">

                      <!-- Modal Usuarios -->
                      <div class="modal" id="modalUsuarios">
                        <div class="modal-content">
                          <header>
                            <h3>Nuevo Usuario</h3>
                            <button class="close" data-close="#modalUsuarios">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="add_usuario">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="Nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="Apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="Amat">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="Correo">
                              </div>
                              <div class="form-control">
                                <label>Usuario</label>
                                <input type="text" name="User">
                              </div>
                              <div class="form-control">
                                <label>Contraseña</label>
                                <input type="password" name="Pass">
                              </div>
                              <div class="form-control">
                                <label>Cargo</label>
                                <input type="text" name="Cargo">
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
                        <table class="display" id="tablaUsuarios">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Apellido Paterno</th>
                              <th>Apellido Materno</th>
                              <th>Correo</th>
                              <th>Usuario</th>
                              <th>Cargo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($usuarios === false): ?>
                              <tr><td colspan="7">Error SQL (usuarios): <?php echo e(mysqli_error($conexion)); ?></td></tr>
                            <?php elseif (mysqli_num_rows($usuarios) === 0): ?>
                              <tr><td colspan="7">Sin usuarios</td></tr>
                            <?php else: ?>
                              <?php while ($u = mysqli_fetch_assoc($usuarios)): ?>
                                <tr>
                                  <td><?php echo e($u['Id_Usuario']); ?></td>
                                  <td><?php echo e($u['Nombre']); ?></td>
                                  <td><?php echo e($u['Apat']); ?></td>
                                  <td><?php echo e($u['Amat']); ?></td>
                                  <td><?php echo e($u['Correo']); ?></td>
                                  <td><?php echo e($u['User']); ?></td>
                                  <td><?php echo e($u['Cargo']); ?></td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </section>
                  <?php } ?>


                  
          </div>
      </center>
     
    </div>
  </main>

  <!-- Scripts -->
  <script src="librerias/tables.js" defer></script>
      <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" ></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', () => {
  const openModal = (id) => document.querySelector(id)?.classList.add('open');
  const closeModal = (id) => document.querySelector(id)?.classList.remove('open');

  // Alumna
  document.getElementById('btnNuevaAlumna')?.addEventListener('click', () => openModal('#modalAlumna'));

  // Maestra
  document.getElementById('btnNuevaMaestra')?.addEventListener('click', () => openModal('#modalMaestra'));

  // Cliente
  document.getElementById('btnNuevoCliente')?.addEventListener('click', () => openModal('#modalClientes'));

  // Usuario
  document.getElementById('btnNuevoUsuario')?.addEventListener('click', () => openModal('#modalUsuarios'));

  // Botones cerrar (X)
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

document.addEventListener("DOMContentLoaded", () => {
    new simpleDatatables.DataTable("#tablaAlumnas");
    new simpleDatatables.DataTable("#tablaMaestras");
    new simpleDatatables.DataTable("#tablaClientes");
    new simpleDatatables.DataTable("#tablaUsuarios");
});

  </script>

</body>
</html>
