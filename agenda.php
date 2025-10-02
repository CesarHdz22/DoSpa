<?php 
session_start();
include_once("conexion.php");
if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }

$Nombre = $_SESSION['nombre'] ?? '';
$Apat   = $_SESSION['apat']   ?? '';
$Amat   = $_SESSION['amat']   ?? '';

mysqli_set_charset($conexion, 'utf8mb4');
 
// Opciones para selects del modal
$optsTalleres = mysqli_query($conexion, "SELECT id_taller, nombre FROM talleres ORDER BY nombre ASC");
$optsCursos   = mysqli_query($conexion, "SELECT id_curso, nombre FROM cursos ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DO SPA - Agenda</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/agenda.css">
  <link rel="stylesheet" href="css/inventario.css">
  <link rel="stylesheet" href="css/getTipo.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>
<body>
  <?php if (!empty($_SESSION['msg_error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?php echo addslashes($_SESSION['msg_error']); ?>'
    });
    </script>
    <?php unset($_SESSION['msg_error']); endif; ?>

    <?php if (!empty($_SESSION['msg_ok'])): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '<?php echo addslashes($_SESSION['msg_ok']); ?>'
    });
    </script>
  <?php unset($_SESSION['msg_ok']); endif; ?>

  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li class="active"><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
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

      <div class="inventario"><!-- mismas clases para heredar estilos -->

        <div class="titulo-agenda-container" style="display:flex; justify-content:center; align-items:center; gap:10px; margin-bottom:20px;">
          <h2 class="titulo-inventario">AGENDA</h2>


          <button class="btn-calendario" data-tipo="taller" title="Ver calendario">
            <img src="img/calendario.png" alt="Calendario" width="20">
          </button>
        </div>
      
        <div class="tablas-inventario">
          <!-- TALLERES -->
          <section class="productos">
            <div class="section-header">
              <h3>Talleres</h3>
              <div class="section-actions">
                <!-- Ícono AGREGAR como en inventario -->
                <img src="img/agregar.png" alt="Agregar" class="icon btn-agregar" width="20" data-tipo="taller" title="Agregar sesión de taller">
                <!-- Ícono EDITAR existente (selección + editar) -->
                <img src="img/editar.png" alt="Editar" class="icon btn-editar" width="20" data-tipo="taller" title="Editar sesión seleccionada">
                <!-- Ícono LISTAR INSCRITAS -->
                <img src="img/listar.png" alt="Listar" class="icon btn-listar" width="20" data-tipo="taller" data-id="<?= $idAgenda ?>" title="Listar Alumnas Inscritas">
              </div>
            </div>

            <table id="TablaTalleres" class="display">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                  <th>Fecha</th>
                  <th>Hora inicio</th>
                  <th>Hora fin</th>
                  <th>Ubicación</th>
                  <th>Variación</th>
                  <th>Alumnas</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sqlT = "SELECT a.id_agenda, a.id_taller,
                                COALESCE(t.nombre,'') AS nombre,
                                a.fecha, a.hora_inicio, a.hora_fin, a.ubicacion, a.variacion, a.cant_alum
                         FROM agenda a
                         LEFT JOIN talleres t ON t.id_taller = a.id_taller
                         ORDER BY a.fecha DESC, a.hora_inicio ASC";
                if ($rt = mysqli_query($conexion, $sqlT)):
                  while ($row = mysqli_fetch_assoc($rt)): ?>
                    <tr>
                      <td><?php echo (int)$row['id_agenda']; ?></td>
                      <td><?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(substr((string)($row['hora_inicio'] ?? ''),0,5), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(substr((string)($row['hora_fin'] ?? ''),0,5), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      
                      <td><?php echo htmlspecialchars($row['variacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['cant_alum'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <button type="button" class="btn-mini btn-agendar-row" data-tipo="taller" data-idrel="<?php echo (int)$row['id_agenda']; ?>">
                          Inscribir
                        </button>
                      </td>
                    </tr>
              <?php
                  endwhile; mysqli_free_result($rt);
                endif;
              ?>
              </tbody>
            </table>
          </section>

          <!-- CURSOS -->
          <section class="kits">
            <div class="section-header">
              <h3>Cursos</h3>
              <div class="section-actions">
                <img src="img/agregar.png" alt="Agregar" class="icon btn-agregar" width="20" data-tipo="curso" title="Agregar sesión de curso">
                <img src="img/editar.png" alt="Editar" class="icon btn-editar" width="20" data-tipo="curso" title="Editar sesión seleccionada">
                <img src="img/listar.png" alt="Listar" class="icon btn-listar" width="20" data-tipo="curso" data-id="<?= $idAgendaCurso ?>" title="Listar Alumnas Inscritas">

              </div>
            </div>

            <table id="TablaCursos" class="display">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                  <th>Fecha</th>
                  <th>Hora inicio</th>
                  <th>Hora fin</th>
                  <th>Ubicación</th>
                  <th>Variación</th>
                  <th>Alumnas</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sqlC = "SELECT ac.id_agenda_curso, ac.id_curso,
                                COALESCE(c.nombre,'') AS nombre,
                                ac.fecha, ac.hora_inicio, ac.hora_fin, ac.ubicacion, ac.variacion, ac.cant_alum
                         FROM agenda_cursos ac
                         LEFT JOIN cursos c ON c.id_curso = ac.id_curso
                         ORDER BY ac.fecha DESC, ac.hora_inicio ASC";
                if ($rc = mysqli_query($conexion, $sqlC)):
                  while ($row = mysqli_fetch_assoc($rc)): ?>
                    <tr>
                      <td><?php echo (int)$row['id_agenda_curso']; ?></td>
                      <td><?php echo htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(substr((string)($row['hora_inicio'] ?? ''),0,5), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(substr((string)($row['hora_fin'] ?? ''),0,5), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['variacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['cant_alum'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <button type="button" class="btn-mini btn-agendar-row" data-tipo="curso" data-idrel="<?php echo (int)$row['id_agenda_curso']; ?>">
                          Inscribir
                        </button>
                      </td>
                    </tr>
              <?php
                  endwhile; mysqli_free_result($rc);
                endif;
              ?>
              </tbody>
            </table>
          </section>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal (universal) -->
  <div class="modal" id="modalAgendar" aria-hidden="true">
    <div class="box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <header>
        <h3 id="modalTitle">Nueva sesión</h3>
        <button class="close" id="cerrarModal" aria-label="Cerrar">&times;</button>
      </header>

      <form action="agendar_save.php" method="post" id="formAgendar">
        <input type="hidden" name="type" id="typeField" value="taller">

        <div class="grid">
          <div id="wrapSelTaller">
            <label for="id_taller">Taller</label>
            <select name="id_taller" id="id_taller">
              <option value="">-- Selecciona --</option>
              <?php mysqli_data_seek($optsTalleres, 0); while($op = mysqli_fetch_assoc($optsTalleres)): ?>
                <option value="<?php echo (int)$op['id_taller']; ?>">
                  <?php echo htmlspecialchars($op['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div id="wrapSelCurso" style="display:none;">
            <label for="id_curso">Curso</label>
            <select name="id_curso" id="id_curso">
              <option value="">-- Selecciona --</option>
              <?php mysqli_data_seek($optsCursos, 0); while($op = mysqli_fetch_assoc($optsCursos)): ?>
                <option value="<?php echo (int)$op['id_curso']; ?>">
                  <?php echo htmlspecialchars($op['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div>
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" required>
          </div>
          <div>
            <label for="ubicacion">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" maxlength="255">
          </div>
          <div>
            <label for="hora_inicio">Hora inicio</label>
            <input type="time" name="hora_inicio" id="hora_inicio" required>
          </div>
          <div>
            <label for="hora_fin">Hora fin</label>
            <input type="time" name="hora_fin" id="hora_fin" required>
          </div>
          <div class="grid" style="grid-column:1 / -1;">
            <div>
              <label for="variacion">Variación (grupo/turno)</label>
              <input type="text" name="variacion" id="variacion" maxlength="100" placeholder="Matutino, Vespertino, Fin de semana...">
            </div>
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="btn-mini btn-primary">Guardar</button>
          <button type="button" class="btn-mini" id="cancelarModal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Inscribir (nuevo) -->
  <div class="modal" id="modalInscribir" aria-hidden="true">
    <div class="box" role="dialog" aria-modal="true" aria-labelledby="inscribirTitle" style="max-width:900px;">
      <header>
        <h3 id="inscribirTitle">Inscribir alumna</h3>
        <button class="close" id="cerrarInscribir" aria-label="Cerrar">&times;</button>
      </header>

      <div id="inscribirBody">
        <!-- Aquí se inyecta la tabla que devuelve getTipo.php -->
        <div style="margin-bottom:12px;">
          <strong>Selecciona una alumna de la lista:</strong>
        </div>
        <div id="resultadoPersonas"></div>

        <form id="formInscribir" action="agendar_inscripcion.php" method="post">
          <!-- campo obligatorio que pide getTipo.php -->
          <input type="hidden" name="tipo" value="alumna">
          <!-- id del taller/curso (relacion) -->
          <input type="hidden" name="tipo_agenda" id="ins_tipo_agenda" value="">
          <input type="hidden" name="id_rel" id="ins_id_rel" value="">
          <!-- id de la persona seleccionada -->
          <input type="hidden" name="id_persona" id="ins_id_persona" value="">

          <div style="margin-top:10px; display:flex; gap:8px; align-items:center;">
            <div id="ins_selected_info" style="flex:1; font-size:14px; color:#333;"></div>
            <button type="submit" class="btn-mini btn-primary" id="confirmarInscripcion" disabled>Inscribir</button>
            <button type="button" class="btn-mini" id="cancelarInscribir">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modalInscritas" class="modal-inscritas" style="display:none;">
  <div class="modal-content-inscritas">
    <span class="close-inscritas">&times;</span>
    <h2>Alumnas Inscritas</h2>
    <div id="contenidoInscritas"></div>
  </div>
</div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="librerias/tables.js"></script>
  <script src = "librerias/agenda.js"></script>
  
  <!-- Modal Calendario -->
<div id="modalCalendario" class="modal" aria-hidden="true">
  <div class="box" role="dialog" aria-modal="true" aria-labelledby="calendarioTitle" style="max-width:1000px;">
    <header>
      <h3 id="calendarioTitle">Calendario de Talleres</h3>
      <button class="close" id="cerrarCalendario" aria-label="Cerrar">&times;</button>
    </header>
    <div id="calendar"></div>
  </div>
</div>
</body>
</html>
