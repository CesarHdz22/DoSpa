<?php
declare(strict_types=1);
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: agenda.php?msg=err"); exit; }

$type        = $_POST['type'] ?? '';
$id_taller   = isset($_POST['id_taller']) ? (int)$_POST['id_taller'] : 0;
$id_curso    = isset($_POST['id_curso'])  ? (int)$_POST['id_curso']  : 0;
$fecha       = $_POST['fecha'] ?? null;
$hora_inicio = $_POST['hora_inicio'] ?? null;
$hora_fin    = $_POST['hora_fin'] ?? null;
$ubicacion   = trim($_POST['ubicacion'] ?? '');
$variacion   = trim($_POST['variacion'] ?? '');

try {
  if (!in_array($type, ['taller','curso'], true)) throw new RuntimeException('Tipo inválido.');
  if (!$fecha || !$hora_inicio || !$hora_fin) throw new RuntimeException('Fecha y horas son obligatorias.');

  if ($type === 'taller') {
    if ($id_taller <= 0) throw new RuntimeException('Selecciona un taller.');
    $sql = "INSERT INTO agenda (id_taller, fecha, hora_inicio, hora_fin, ubicacion, variacion)
            VALUES (?, ?, ?, ?, ?, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "isssss", $id_taller, $fecha, $hora_inicio, $hora_fin, $ubicacion, $variacion);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
  } else {
    if ($id_curso <= 0) throw new RuntimeException('Selecciona un curso.');
    $sql = "INSERT INTO agenda_cursos (id_curso, fecha, hora_inicio, hora_fin, ubicacion, variacion)
            VALUES (?, ?, ?, ?, ?, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "isssss", $id_curso, $fecha, $hora_inicio, $hora_fin, $ubicacion, $variacion);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
  }

  header("Location: agenda.php?msg=creada_ok"); exit;

} catch (Throwable $e) {
  // echo "Error: ".$e->getMessage();
  header("Location: agenda.php?msg=creada_err"); exit;
}
