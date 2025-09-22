<?php
declare(strict_types=1);
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: agenda.php?msg=err"); exit; }

$type  = $_POST['type'] ?? '';
$id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$fecha = $_POST['fecha'] ?? null;
$hini  = $_POST['hora_inicio'] ?? null;
$hfin  = $_POST['hora_fin'] ?? null;
$ubic  = $_POST['ubicacion'] ?? null;
$vari  = $_POST['variacion'] ?? null;

try {
  if ($type === 'taller') {
    $id_rel = isset($_POST['id_taller']) ? (int)$_POST['id_taller'] : 0;
    if ($id <= 0 || $id_rel <= 0) throw new RuntimeException('Parámetros inválidos (taller).');

    $sql = "UPDATE agenda
               SET id_taller=?, fecha=?, hora_inicio=?, hora_fin=?, ubicacion=?, variacion=?
             WHERE id_agenda=?";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "isssssi", $id_rel, $fecha, $hini, $hfin, $ubic, $vari, $id);
    mysqli_stmt_execute($st); mysqli_stmt_close($st);

  } elseif ($type === 'curso') {
    $id_rel = isset($_POST['id_curso']) ? (int)$_POST['id_curso'] : 0;
    if ($id <= 0 || $id_rel <= 0) throw new RuntimeException('Parámetros inválidos (curso).');

    $sql = "UPDATE agenda_cursos
               SET id_curso=?, fecha=?, hora_inicio=?, hora_fin=?, ubicacion=?, variacion=?
             WHERE id_agenda_curso=?";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "isssssi", $id_rel, $fecha, $hini, $hfin, $ubic, $vari, $id);
    mysqli_stmt_execute($st); mysqli_stmt_close($st);

  } else { throw new RuntimeException('Tipo inválido.'); }

  header("Location: agenda.php?msg=ok"); exit;

} catch (Throwable $e) {
  header("Location: agenda.php?msg=err"); exit;
}
