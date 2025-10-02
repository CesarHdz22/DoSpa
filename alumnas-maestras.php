<?php include_once("usuario_controlador.php"); ?>
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
                        <img src="img/oculto.png" id="btnToggleAlumnas" class="btn-mini btn-secondary" title="Mostrar / ocultar inactivos" width="20">
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

                                    <?php if ($a['estatus'] == 1): ?>
                                        <!-- Alumna activa: botón eliminar -->
                                        <button class="btn-mini btn-delete-alumna" 
                                                data-id="<?php echo e($a['id_alumna']); ?>"
                                                title="Eliminar Alumna">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Alumna inactiva: botón activar -->
                                        <form method="post" style="display:inline;">
                                          <input type="hidden" name="action" value="activate_alumna">
                                          <input type="hidden" name="id_alumna" value="<?php echo e($a['id_alumna']); ?>">
                                          <button type="submit" class="btn-mini btn-activate-alumna" title="Activar Alumna">
                                            <i class="fa fa-check"></i>
                                          </button>
                                        </form>
                                    <?php endif; ?>
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
                        <img src="img/agregar.png" class="btn-mini btn-primary icon btn-agregar" alt="Agregar" id="btnNuevaMaestra" width="20" data-tipo="curso" title="Agregar sesión de curso">
                        <img src="img/oculto.png" id="btnToggleMaestras" class="btn-mini btn-secondary" title="Mostrar / ocultar inactivos" width="20">
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
                                    <?php if ($m['estatus'] == 1): ?>
                                        <!-- Maestra activa: botón eliminar -->
                                        <button class="btn-mini btn-delete-maestra" 
                                                data-id="<?php echo e($m['id_maestra']); ?>"
                                                title="Eliminar Maestra">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Maestra inactiva: botón activar -->
                                        <form method="post" style="display:inline;">
                                          <input type="hidden" name="action" value="activate_maestra">
                                          <input type="hidden" name="id_maestra" value="<?php echo e($m['id_maestra']); ?>">
                                          <button type="submit" class="btn-mini btn-activate-maestra" title="Activar Maestra">
                                            <i class="fa fa-check"></i>
                                          </button>
                                        </form>
                                    <?php endif; ?>
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
                        <img src="img/oculto.png" class="btn-mini btn-secondary" alt="Mostrar Inactivos" id="btnToggleClientes" width="20" title="Mostrar clientes inactivos">
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
                                    <?php if($c['estatus'] == 1): ?>
                                        <button class="btn-mini btn-delete-cliente" data-id="<?php echo e($c['id_cliente']); ?>">
                                          <i class="fa fa-trash"></i>
                                        </button>
                                      <?php else: ?>
                                        <button class="btn-mini btn-activate-cliente" data-id="<?php echo e($c['id_cliente']); ?>">
                                          <i class="fa fa-check"></i>
                                        </button>
                                      <?php endif; ?>                                   
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
                                     <?php
                                      if ($u['Id_Usuario'] != $Id_Usuario) {
                                        ?>
                                    <button class="btn-mini btn-delete" 
                                      data-id="<?php echo e($u['Id_Usuario']); ?>"
                                      title="Eliminar Usuario">
                                      <i class="fa fa-trash"></i>
                                    </button>    
                                    <?php
                                      }
                                      ?>

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
  <script src="librerias/usuarios.js"></script>

  <!-- Messages -->
  <?php if (!empty($_SESSION['flash_ok'])): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        title: "<?php echo addslashes($_SESSION['flash_ok']); ?>",
        icon: "success",
        timer: 2500,
        timerProgressBar: true,
        toast: true,
        position: "top-end",
        showConfirmButton: false
      });
    });
  </script>
  <?php unset($_SESSION['flash_ok']); endif; ?>

  <?php if (!empty($_SESSION['flash_err'])): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        title: "<?php echo addslashes($_SESSION['flash_err']); ?>",
        icon: "error",
        timer: 2500,
        timerProgressBar: true,
        toast: true,
        position: "top-end",
        showConfirmButton: false
      });
    });
  </script>
  <?php unset($_SESSION['flash_err']); endif; ?>
</body>
</html>