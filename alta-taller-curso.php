<?php
declare(strict_types=1);
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: talleres_cursos.php?msg=err"); exit; }

$tipo   = $_POST['tipo'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$fecha  = $_POST['fecha'] ?? null;

$costo_base = isset($_POST['costo_base']) ? (float)$_POST['costo_base'] : 0.0;
$status     = $_POST['status'] ?? 'pendiente';
$ingreso    = isset($_POST['ingreso_bruto']) ? (float)$_POST['ingreso_bruto'] : 0.0;
$gastos     = isset($_POST['gastos']) ? (float)$_POST['gastos'] : 0.0;
$pref       = isset($_POST['precio_preferencial']) ? (int)$_POST['precio_preferencial'] : 0;

// Solo Taller
$id_maestra = isset($_POST['id_maestra']) ? (int)$_POST['id_maestra'] : null;
$p_delia    = isset($_POST['porcentaje_delia']) ? (float)$_POST['porcentaje_delia'] : null;
$p_caro     = isset($_POST['porcentaje_caro']) ? (float)$_POST['porcentaje_caro'] : null;

try {
  if (!in_array($tipo, ['taller','curso'], true)) {
    throw new RuntimeException('Tipo inválido.');
  }
  if ($nombre === '' || !$fecha) {
    throw new RuntimeException('Nombre y fecha son obligatorios.');
  }

  if ($tipo === 'taller') {
    if (!$id_maestra) throw new RuntimeException('Maestra requerida para taller.');
    $sql = "INSERT INTO talleres(nombre, id_maestra, fecha, costo_base, status, ingreso_bruto, gastos, porcentaje_delia, porcentaje_caro, precio_preferencial)VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "sisssdddii",
      $nombre, $id_maestra, $fecha, $costo_base, $status, $ingreso, $gastos, $p_delia, $p_caro, $pref
    );
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);

  } else { // curso
    $sql = "INSERT INTO cursos(nombre, fecha, costo_base, status, ingreso_bruto, gastos, precio_preferencial)VALUES (?, ?, ?, ?, ?, ?, ?)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "ssdssdi",
      $nombre, $fecha, $costo_base, $status, $ingreso, $gastos, $pref
    );
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
  }

  header("Location: talleres-cursos.php?msg=alta_ok"); exit;

} catch (Throwable $e) {
  // En dev puedes imprimir el error para depurar:
  // echo "Error: ".$e->getMessage();
  header("Location: talleres-cursos.php?msg=alta_err"); exit;
}
