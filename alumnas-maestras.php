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
      // === Eliminar Usuario ===
      if (is_post() && post('action') === 'delete_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
          $id = intval(post('id'));
          $res = mysqli_query($conexion, "DELETE FROM usuarios WHERE Id_Usuario = $id");
          header('Content-Type: application/json');
          echo json_encode(['success' => $res ? true : false]);
          exit;
      }
      // === Eliminar Cliente ===
      if (is_post() && post('action') === 'delete_cliente') {
          $id = intval(post('id'));
          $res = mysqli_query($conexion, "DELETE FROM clientes WHERE id_cliente = $id");
          header('Content-Type: application/json');
          echo json_encode(['success' => $res ? true : false]);
          exit;
      }
      // === Eliminar Maestra ===
      if (is_post() && post('action') === 'delete_maestra') {
          $id = intval(post('id'));
          $res = mysqli_query($conexion, "DELETE FROM maestras WHERE id_maestra = $id");
          header('Content-Type: application/json');
          echo json_encode(['success' => $res ? true : false]);
          exit;
      }
      // === Eliminar Alumna ===
      if (is_post() && post('action') === 'delete_alumna') {
          $id = intval(post('id'));
          $res = mysqli_query($conexion, "DELETE FROM alumnas WHERE id_alumna = $id");
          header('Content-Type: application/json');
          echo json_encode(['success' => $res ? true : false]);
          exit;
      }

      //Que chistoso - CESAR
      // === Editar Usuario ===
      if ($action === 'edit_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
          $id = intval(post('Id_Usuario'));
          $sql = "UPDATE usuarios 
                  SET Nombre=?, Apat=?, Amat=?, Correo=?, User=?, Cargo=?"
                  . (post('Pass') !== '' ? ", Pass=? " : "") . 
                  "WHERE Id_Usuario=?";
          
          if (post('Pass') !== '') {
              $pass = password_hash(post('Pass'), PASSWORD_DEFAULT);
              $stmt = mysqli_prepare($conexion, $sql);
              mysqli_stmt_bind_param(
                $stmt, "sssssssi",
                post('Nombre'), post('Apat'), post('Amat'),
                post('Correo'), post('User'), post('Cargo'),
                $pass, $id
              );
          } else {
              $stmt = mysqli_prepare($conexion, $sql);
              mysqli_stmt_bind_param(
                $stmt, "ssssssi",
                post('Nombre'), post('Apat'), post('Amat'),
                post('Correo'), post('User'), post('Cargo'),
                $id
              );
          }
          $ok = mysqli_stmt_execute($stmt);
          $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Usuario actualizado.' : 'Error: '.mysqli_error($conexion);
          header("Location: alumnas-maestras.php"); exit;
      }
      // === Editar Cliente ===
      if ($action === 'edit_cliente') {
          $id = intval(post('id_cliente'));
          $sql = "UPDATE clientes 
                  SET nombre=?, apat=?, amat=?, correo=?, telefono=?, direccion=?
                  WHERE id_cliente=?";
          $stmt = mysqli_prepare($conexion, $sql);
          mysqli_stmt_bind_param(
              $stmt, "ssssssi",
              post('nombre'), post('apat'), post('amat'),
              post('correo'), post('telefono'), post('direccion'),
              $id
          );
          $ok = mysqli_stmt_execute($stmt);
          $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Cliente actualizado.' : 'Error: '.mysqli_error($conexion);
          header("Location: alumnas-maestras.php"); exit;
      }
      // === Editar Maestra ===
      if ($action === 'edit_maestra') {
          $id = intval(post('id_maestra'));
          $sql = "UPDATE maestras 
                  SET nombre=?, base=?, acuerdo=?, gastos=?, porcentaje_ganancia=? 
                  WHERE id_maestra=?";
          $stmt = mysqli_prepare($conexion, $sql);
          mysqli_stmt_bind_param(
              $stmt, "sssddi",
              post('nombre'), post('base'), post('acuerdo'),
              (post('gastos')===''?null:floatval(post('gastos'))),
              (post('porcentaje_ganancia')===''?null:floatval(post('porcentaje_ganancia'))),
              $id
          );
          $ok = mysqli_stmt_execute($stmt);
          $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Maestra actualizada.' : 'Error: '.mysqli_error($conexion);
          header("Location: alumnas-maestras.php"); exit;
      }
      // === Editar Alumna ===
      if ($action === 'edit_alumna') {
          $id = intval(post('id_alumna'));
          $sql = "UPDATE alumnas 
                  SET nombre=?, apat=?, amat=?, telefono=?, correo=?, direccion=?, descuento_aplicado=?, tipo_descuento=? 
                  WHERE id_alumna=?";
          $stmt = mysqli_prepare($conexion, $sql);
          mysqli_stmt_bind_param(
              $stmt, "sssssisisi",
              post('nombre'), post('apat'), post('amat'),
              post('telefono'), post('correo'), post('direccion'),
              intval(post('descuento_aplicado',0)), post('tipo_descuento'),
              $id
          );
          $ok = mysqli_stmt_execute($stmt);
          $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Alumna actualizada.' : 'Error: '.mysqli_error($conexion);
          header("Location: alumnas-maestras.php"); exit;
      }

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
    $sqlAlumnas  = "SELECT id_alumna, nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento FROM alumnas ORDER BY id_alumna ASC";
    $sqlMaestras = "SELECT id_maestra, nombre, base, acuerdo, gastos, porcentaje_ganancia FROM maestras ORDER BY id_maestra ASC";
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
  <link rel="stylesheet" href="css/talleres-cursos.css">

  <!-- CSS específico -->
  <link rel="stylesheet" href="css/alumnas-maestras.css">

  <!-- datatables -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
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
                              <th>Acciones</th>
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
                                  <td>
                                    <button class="btn-mini btn-edit-alumna"
                                      data-id="<?php echo e($a['id_alumna']); ?>"
                                      data-nombre="<?php echo e($a['nombre']); ?>"
                                      data-apat="<?php echo e($a['apat']); ?>"
                                      data-amat="<?php echo e($a['amat']); ?>"
                                      data-telefono="<?php echo e($a['telefono']); ?>"
                                      data-correo="<?php echo e($a['correo']); ?>"
                                      data-direccion="<?php echo e($a['direccion']); ?>"
                                      data-descuento="<?php echo e($a['descuento_aplicado']); ?>"
                                      data-tipo="<?php echo e($a['tipo_descuento']); ?>">
                                      <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn-mini btn-delete-alumna" 
                                            data-id="<?php echo e($a['id_alumna']); ?>"
                                            title="Eliminar Alumna">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                  </td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
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
                      <!-- Modal Editar Alumna -->
                      <div class="modal" id="modalEditarAlumna">
                        <div class="modal-content">
                          <header>
                            <h3>Editar Alumna</h3>
                            <button class="close" data-close="#modalEditarAlumna">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="edit_alumna">
                            <input type="hidden" name="id_alumna" id="edit_id_alumna">

                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="edit_nombre_alumna" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apat" id="edit_apat_alumna">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="amat" id="edit_amat_alumna">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" id="edit_telefono_alumna">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo" id="edit_correo_alumna">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion" id="edit_direccion_alumna">
                              </div>
                              <div class="form-control">
                                <label>Descuento aplicado</label>
                                <select name="descuento_aplicado" id="edit_descuento_alumna">
                                  <option value="0">No</option>
                                  <option value="1">Sí</option>
                                </select>
                              </div>
                              <div class="form-control">
                                <label>Tipo de descuento</label>
                                <input type="text" name="tipo_descuento" id="edit_tipo_descuento_alumna">
                              </div>
                            </div>

                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
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
                              <th>Acciones</th>
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
                                  <td>
                                    <button class="btn-mini btn-edit-maestra"
                                      data-id="<?php echo e($m['id_maestra']); ?>"
                                      data-nombre="<?php echo e($m['nombre']); ?>"
                                      data-base="<?php echo e($m['base']); ?>"
                                      data-acuerdo="<?php echo e($m['acuerdo']); ?>"
                                      data-gastos="<?php echo e($m['gastos']); ?>"
                                      data-porcentaje="<?php echo e($m['porcentaje_ganancia']); ?>">
                                      <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn-mini btn-delete-maestra" 
                                            data-id="<?php echo e($m['id_maestra']); ?>"
                                            title="Eliminar Maestra">
                                        <i class="fa fa-trash"></i>
                                    </button>                                    
                                  </td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- Panel de formulario -->
                      <!-- Modal Maestra -->
                      <div class="modal" id="modalMaestra">
                        <div class="modal-content">
                          <header>
                            <h3>Nueva Maestra</h3>
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
                      <!-- Modal Editar Maestra -->
                      <div class="modal" id="modalEditarMaestra">
                        <div class="modal-content">
                          <header>
                            <h3>Editar Maestra</h3>
                            <button class="close" data-close="#modalEditarMaestra">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="edit_maestra">
                            <input type="hidden" name="id_maestra" id="edit_id_maestra">

                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="edit_nombre_maestra" required>
                              </div>
                              <div class="form-control">
                                <label>Base</label>
                                <input type="text" name="base" id="edit_base_maestra">
                              </div>
                              <div class="form-control">
                                <label>Acuerdo</label>
                                <input type="text" name="acuerdo" id="edit_acuerdo_maestra">
                              </div>
                              <div class="form-control">
                                <label>Gastos</label>
                                <input type="number" step="0.01" name="gastos" id="edit_gastos_maestra">
                              </div>
                              <div class="form-control">
                                <label>% Ganancia</label>
                                <input type="number" step="0.01" name="porcentaje_ganancia" id="edit_porcentaje_maestra">
                              </div>
                            </div>

                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
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
                      <!-- Modal Editar Cliente -->
                      <div class="modal" id="modalEditarCliente">
                        <div class="modal-content">
                          <header>
                            <h3>Editar Cliente</h3>
                            <button class="close" data-close="#modalEditarCliente">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="edit_cliente">
                            <input type="hidden" name="id_cliente" id="edit_id_cliente">

                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="edit_nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apat" id="edit_apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="amat" id="edit_amat">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo" id="edit_correo">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" id="edit_telefono">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion" id="edit_direccion">
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
                              <th>Acciones</th>
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
                                  <td>
                                    <button class="btn-mini btn-edit-cliente"
                                      data-id="<?php echo e($c['id_cliente']); ?>"
                                      data-nombre="<?php echo e($c['nombre']); ?>"
                                      data-apat="<?php echo e($c['apat']); ?>"
                                      data-amat="<?php echo e($c['amat']); ?>"
                                      data-correo="<?php echo e($c['correo']); ?>"
                                      data-telefono="<?php echo e($c['telefono']); ?>"
                                      data-direccion="<?php echo e($c['direccion']); ?>">
                                      <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn-mini btn-delete-cliente" 
                                            data-id="<?php echo e($c['id_cliente']); ?>"
                                            title="Eliminar Cliente">
                                        <i class="fa fa-trash"></i>
                                    </button>                                    
                                  </td>
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
                              <th>Acciones</th>
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
                                  <td>
                                    <!-- Botón Editar -->
                                    <button class="btn-mini btn-edit" 
                                      data-id="<?php echo e($u['Id_Usuario']); ?>"
                                      data-nombre="<?php echo e($u['Nombre']); ?>"
                                      data-apat="<?php echo e($u['Apat']); ?>"
                                      data-amat="<?php echo e($u['Amat']); ?>"
                                      data-correo="<?php echo e($u['Correo']); ?>"
                                      data-user="<?php echo e($u['User']); ?>"
                                      data-cargo="<?php echo e($u['Cargo']); ?>">
                                      <i class="fa fa-edit"></i>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button class="btn-mini btn-delete" 
                                      data-id="<?php echo e($u['Id_Usuario']); ?>"
                                      title="Eliminar Usuario">
                                      <i class="fa fa-trash"></i>
                                    </button>
                                  </td>
                                </tr>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
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
                      <!-- Modal Editar Usuario -->
                      <div class="modal" id="modalEditarUsuario">
                        <div class="modal-content">
                          <header>
                            <h3>Editar Usuario</h3>
                            <button class="close" data-close="#modalEditarUsuario">&times;</button>
                          </header>
                          <form method="post" autocomplete="off">
                            <input type="hidden" name="action" value="edit_usuario">
                            <input type="hidden" name="Id_Usuario" id="edit_Id_Usuario">

                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="Nombre" id="edit_Nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="Apat" id="edit_Apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="Amat" id="edit_Amat">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="Correo" id="edit_Correo">
                              </div>
                              <div class="form-control">
                                <label>Usuario</label>
                                <input type="text" name="User" id="edit_User">
                              </div>
                              <div class="form-control">
                                <label>Contraseña</label>
                                <input type="password" name="Pass" id="edit_Pass" placeholder="(dejar vacío para no cambiar)">
                              </div>
                              <div class="form-control">
                                <label>Cargo</label>
                                <input type="text" name="Cargo" id="edit_Cargo">
                              </div>
                            </div>
                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        document.addEventListener("DOMContentLoaded", () => {

          function delegarEdicion(tbodySelector, btnClass, modalSelector, mapping) {
            const tbody = document.querySelector(tbodySelector);
            if (!tbody) return;

            tbody.addEventListener("click", (e) => {
              const btn = e.target.closest(btnClass);
              if (!btn) return;

              for (const [datasetKey, inputId] of Object.entries(mapping)) {
                const input = document.getElementById(inputId);
                if (input) {
                  input.value = btn.dataset[datasetKey] ?? '';
                }
              }

              document.querySelector(modalSelector)?.classList.add("open");
            });
          }

          // Usuarios
          delegarEdicion(
            "#tablaUsuarios tbody",
            ".btn-edit",
            "#modalEditarUsuario",
            {
              id: "edit_Id_Usuario",
              nombre: "edit_Nombre",
              apat: "edit_Apat",
              amat: "edit_Amat",
              correo: "edit_Correo",
              user: "edit_User",
              cargo: "edit_Cargo",
              pass: "edit_Pass" // si quieres dejarla vacía, después puedes hacer input.value=''
            }
          );

          // Clientes
          delegarEdicion(
            "#tablaClientes tbody",
            ".btn-edit-cliente",
            "#modalEditarCliente",
            {
              id: "edit_id_cliente",
              nombre: "edit_nombre",
              apat: "edit_apat",
              amat: "edit_amat",
              correo: "edit_correo",
              telefono: "edit_telefono",
              direccion: "edit_direccion"
            }
          );

          // Maestras
          delegarEdicion(
            "#tablaMaestras tbody",
            ".btn-edit-maestra",
            "#modalEditarMaestra",
            {
              id: "edit_id_maestra",
              nombre: "edit_nombre_maestra",
              base: "edit_base_maestra",
              acuerdo: "edit_acuerdo_maestra",
              gastos: "edit_gastos_maestra",
              porcentaje: "edit_porcentaje_maestra"
            }
          );

          // Alumnas
          delegarEdicion(
            "#tablaAlumnas tbody",
            ".btn-edit-alumna",
            "#modalEditarAlumna",
            {
              id: "edit_id_alumna",
              nombre: "edit_nombre_alumna",
              apat: "edit_apat_alumna",
              amat: "edit_amat_alumna",
              telefono: "edit_telefono_alumna",
              correo: "edit_correo_alumna",
              direccion: "edit_direccion_alumna",
              descuento: "edit_descuento_alumna",
              tipo: "edit_tipo_descuento_alumna"
            }
          );

        });


        document.addEventListener("DOMContentLoaded", () => {
          <?php if (!empty($_SESSION['flash_ok'])): ?>
            Swal.fire({
              title: "<?php echo addslashes($_SESSION['flash_ok']); ?>",
              icon: "success",
              draggable: true,
              timer: 2500,
              timerProgressBar: true,
              toast: true,
              position: "top-end",
              showConfirmButton: false
            });
            <?php unset($_SESSION['flash_ok']); ?>
          <?php endif; ?>

          <?php if (!empty($_SESSION['flash_err'])): ?>
            Swal.fire({
              title: "<?php echo addslashes($_SESSION['flash_err']); ?>",
              icon: "error",
              draggable: true,
              timer: 2500,
              timerProgressBar: true,
              toast: true,
              position: "top-end",
              showConfirmButton: false
            });
            <?php unset($_SESSION['flash_err']); ?>
          <?php endif; ?>
        });

        function setupDeleteButtons(tablaSelector, claseBoton, actionName, textoSingular) {
            const tabla = document.querySelector(tablaSelector + " tbody");
            if (!tabla) return;

            tabla.addEventListener("click", (e) => {
                const btn = e.target.closest(claseBoton);
                if (!btn) return;

                const id = btn.dataset.id;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("", {
                            method: "POST",
                            headers: {"Content-Type": "application/x-www-form-urlencoded"},
                            body: `action=${actionName}&id=${id}`
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado!', `El ${textoSingular} ha sido eliminado.`, 'success');
                                btn.closest("tr").remove();
                            } else {
                                Swal.fire('Error', `No se pudo eliminar el ${textoSingular}.`, 'error');
                            }
                        });
                    }
                });
            });
        }

        // Configuración de todas las tablas
        document.addEventListener("DOMContentLoaded", () => {
            setupDeleteButtons("#tablaUsuarios", ".btn-delete", "delete_usuario", "usuario");
            setupDeleteButtons("#tablaClientes", ".btn-delete-cliente", "delete_cliente", "cliente");
            setupDeleteButtons("#tablaMaestras", ".btn-delete-maestra", "delete_maestra", "maestra");
            setupDeleteButtons("#tablaAlumnas", ".btn-delete-alumna", "delete_alumna", "alumna");
        });

  </script>
</body>
</html>
