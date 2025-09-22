<?php
declare(strict_types=1);
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: agenda.php?msg=err"); exit; }

$type      = $_POST['type'] ?? '';
$id_agenda = isset($_POST['id_agenda']) ? (int)$_POST['id_agenda'] : 0;     // id_agenda o id_agenda_curso
$id_rel    = isset($_POST['id_rel']) ? (int)$_POST['id_rel'] : 0;           // id_taller o id_curso
$id_alumna = isset($_POST['id_alumna']) ? (int)$_POST['id_alumna'] : 0;
$fecha_ref = $_POST['fecha_ref'] ?? date('Y-m-d');

try {
  if ($type === 'curso') {
    if ($id_agenda<=0 || $id_rel<=0 || $id_alumna<=0) throw new RuntimeException('Parámetros inválidos (curso).');

    // Nota: monto_pagado/saldo_pendiente pueden iniciarse en 0 hasta que registres pagos reales
    $sql = "INSERT INTO historial_pagos_cursos (id_alumna, id_agenda_curso, monto_pagado, saldo_pendiente, fecha_pago, metodo_pago, tipo_servicio)
            VALUES (?,?,?,?,?, 'otros', 'servicio')";
    $st  = mysqli_prepare($conexion, $sql);
    $cero = 0.00; 
    mysqli_stmt_bind_param($st, "iiids", $id_alumna, $id_agenda, $cero, $cero, $fecha_ref);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);

  } elseif ($type === 'taller') {
    if ($id_agenda<=0 || $id_rel<=0 || $id_alumna<=0) throw new RuntimeException('Parámetros inválidos (taller).');

    // En talleres el registro de ingresos usa id_taller + id_alumna
    $sql = "INSERT INTO ingresos_talleres (id_alumna, fecha, tipo_ingreso, metodo_pago, id_taller, costo)
            VALUES (?, ?, 'inscripcion', 'otros', ?, 0.00)";
    $st  = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "isi", $id_alumna, $fecha_ref, $id_rel);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);

  } else {
    throw new RuntimeException('Tipo inválido.');
  }

  header("Location: agenda.php?msg=ins_ok"); exit;

} catch (Throwable $e) {
  // echo "Error: ".$e->getMessage(); exit;
  header("Location: agenda.php?msg=ins_err"); exit;
}
