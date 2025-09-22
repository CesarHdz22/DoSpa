<?php
declare(strict_types=1);
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: agenda.php?msg=err"); exit; }

$type      = $_POST['type'] ?? '';        // 'taller' | 'curso'
$id_agenda = isset($_POST['id_agenda']) ? (int)$_POST['id_agenda'] : 0;
$id_curso  = isset($_POST['id_curso'])  ? (int)$_POST['id_curso']  : 0;
$id_alumna = isset($_POST['id_alumna']) ? (int)$_POST['id_alumna'] : 0;
$fecha     = $_POST['fecha'] ?? null;

try {
  if (!in_array($type, ['taller','curso'], true)) throw new RuntimeException('Tipo inválido.');
  if ($id_alumna <= 0 || !$fecha) throw new RuntimeException('Faltan datos.');

  if ($type === 'taller') {
    if ($id_agenda <= 0) throw new RuntimeException('Falta id_agenda');
    $sql = "INSERT INTO intermedia_A (id_alumna, id_agenda, id_curso, fecha) VALUES (?, ?, NULL, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "iis", $id_alumna, $id_agenda, $fecha);
  } else {
    if ($id_curso <= 0) throw new RuntimeException('Falta id_curso');
    $sql = "INSERT INTO intermedia_A (id_alumna, id_agenda, id_curso, fecha) VALUES (?, NULL, ?, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "iis", $id_alumna, $id_curso, $fecha);
  }

  mysqli_stmt_execute($st);
  mysqli_stmt_close($st);

  header("Location: agenda.php?msg=inscrita_ok"); exit;

} catch (Throwable $e) {
  // echo "Error: ".$e->getMessage();
  header("Location: agenda.php?msg=inscrita_err"); exit;
}
