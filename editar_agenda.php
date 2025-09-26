<?php
session_start();
include_once("conexion.php");
if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }

$Nombre = $_SESSION['nombre'] ?? ''; $Apat = $_SESSION['apat'] ?? ''; $Amat = $_SESSION['amat'] ?? '';
$type = isset($_GET['type']) ? strtolower(trim($_GET['type'])) : 'taller';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!in_array($type, ['taller','curso'], true) || $id <= 0) { header("Location: agenda.php?msg=bad_params"); exit; }
mysqli_set_charset($conexion, 'utf8mb4');

if ($type === 'taller') {
  $sql = "SELECT a.id_agenda, a.id_taller, a.fecha, a.hora_inicio, a.hora_fin, a.ubicacion, a.variacion
          FROM agenda a WHERE a.id_agenda = ?";
  $st = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($st, "i", $id); mysqli_stmt_execute($st);
  $res = mysqli_stmt_get_result($st); $row = mysqli_fetch_assoc($res); mysqli_stmt_close($st);
  $cat = mysqli_query($conexion, "SELECT id_taller, nombre FROM talleres ORDER BY nombre ASC");
} else {
  $sql = "SELECT ac.id_agenda_curso AS id_agenda, ac.id_curso, ac.fecha, ac.hora_inicio, ac.hora_fin, ac.ubicacion, ac.variacion
          FROM agenda_cursos ac WHERE ac.id_agenda_curso = ?";
  $st = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($st, "i", $id); mysqli_stmt_execute($st);
  $res = mysqli_stmt_get_result($st); $row = mysqli_fetch_assoc($res); mysqli_stmt_close($st);
  $cat = mysqli_query($conexion, "SELECT id_curso, nombre FROM cursos ORDER BY nombre ASC");
}
if (!$row) { header("Location: agenda.php?msg=no_row"); exit; }
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Agenda - DO SPA</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/editarP.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Iconos de FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
  <div class="dashboard">
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

    <main class="main">
      <header class="topbar">
        <div class="user-info">
          <span><?php echo h("$Nombre $Apat $Amat"); ?></span>
          <a href="muerte.php"><img src="img/logout.png" width="20" alt="Salir"></a>
        </div>
      </header>

      <section id="edicion" class="page">
        <h2 style="text-align:center; margin-bottom:20px;">
          <?php echo $type==='taller'?'Editar Agenda de Taller':'Editar Agenda de Curso'; ?>
        </h2>

        <div class="edicion-wrapper">
          <form action="eAgenda.php" method="post" class="edicion-form" onsubmit="return validarAgenda(event);">
            <input type="hidden" name="type" value="<?php echo h($type); ?>">
            <input type="hidden" name="id"   value="<?php echo (int)$row['id_agenda']; ?>">

            <?php if ($type==='taller'): ?>
              <label for="id_taller">Taller:</label>
              <select name="id_taller" id="id_taller" required>
                <option value="">-- Selecciona --</option>
                <?php while($op = mysqli_fetch_assoc($cat)): ?>
                  <option value="<?php echo (int)$op['id_taller']; ?>"
                    <?php echo ((int)$op['id_taller'] === (int)($row['id_taller'] ?? 0))?'selected':''; ?>>
                    <?php echo h($op['nombre']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            <?php else: ?>
              <label for="id_curso">Curso:</label>
              <select name="id_curso" id="id_curso" required>
                <option value="">-- Selecciona --</option>
                <?php while($op = mysqli_fetch_assoc($cat)): ?>
                  <option value="<?php echo (int)$op['id_curso']; ?>"
                    <?php echo ((int)$op['id_curso'] === (int)($row['id_curso'] ?? 0))?'selected':''; ?>>
                    <?php echo h($op['nombre']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            <?php endif; ?>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo h($row['fecha'] ?? ''); ?>" required>

            <label for="hora_inicio">Hora inicio:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" value="<?php echo h(substr((string)($row['hora_inicio'] ?? ''),0,5)); ?>" required>

            <label for="hora_fin">Hora fin:</label>
            <input type="time" name="hora_fin" id="hora_fin" value="<?php echo h(substr((string)($row['hora_fin'] ?? ''),0,5)); ?>" required>

            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" maxlength="255" value="<?php echo h($row['ubicacion'] ?? ''); ?>">

            <label for="variacion">Variación (grupo/turno):</label>
            <input type="text" name="variacion" id="variacion" maxlength="100" value="<?php echo h($row['variacion'] ?? ''); ?>" placeholder="Matutino, Vespertino, Fin de semana...">

            <button type="submit" class="btn-submit">Guardar Cambios</button>
          </form>
        </div>
      </section>
    </main>
  </div>

  <script>
      async function validarAgenda(event) {
        event.preventDefault(); // detiene el envío por defecto
        
        var f  = document.getElementById('fecha').value.trim();
        var hi = document.getElementById('hora_inicio').value.trim();
        var hf = document.getElementById('hora_fin').value.trim();

        if (!f) {
            await Swal.fire({
                icon: "error",
                title: "La fecha es obligatoria.",
                text: "Ingresa una fecha válida"
            });
            return false;
        }

        if (!hi || !hf) {
            await Swal.fire({
                icon: "error",
                title: "Verifica tus horarios",
                text: "Las horas de inicio y fin son obligatorias."
            });
            return false;
        }

        var di = new Date('1970-01-01T' + hi + ':00');
        var df = new Date('1970-01-01T' + hf + ':00');

        if (isNaN(di.getTime()) || isNaN(df.getTime())) {
            await Swal.fire({
                icon: "error",
                title: "Hora inválida.",
                text: "Revisa el formato de la hora"
            });
            return false;
        }

        if (df <= di) {
            await Swal.fire({
                icon: "error",
                title: "Ajusta tus horarios",
                text: "La hora de fin debe ser mayor a la de inicio."
            });
            return false;
        }

        await Swal.fire({
            icon: "success",
            title: "Perfecto!",
            text: "Tu agenda ha sido validada correctamente."
        });

        // ✅ Aquí es donde se envía el formulario
        event.target.submit();
    }


  </script>
</body>
</html>
