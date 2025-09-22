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
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li class="active"><a href="agenda.php"><i class="fas fa-th-large"></i> Agenda</a></li>
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
          <form action="eAgenda.php" method="post" class="edicion-form" onsubmit="return validarAgenda()">
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
  function validarAgenda(){
    var f  = document.getElementById('fecha').value.trim();
    var hi = document.getElementById('hora_inicio').value.trim();
    var hf = document.getElementById('hora_fin').value.trim();
    if(!f){ alert('La fecha es obligatoria.'); return false; }
    if(!hi || !hf){ alert('Las horas de inicio y fin son obligatorias.'); return false; }
    var di = new Date('1970-01-01T' + hi + ':00');
    var df = new Date('1970-01-01T' + hf + ':00');
    if (isNaN(di.getTime()) || isNaN(df.getTime())) { alert('Hora inválida.'); return false; }
    if (df <= di) { alert('La hora de fin debe ser mayor a la de inicio.'); return false; }
    return true;
  }
  </script>
</body>
</html>
