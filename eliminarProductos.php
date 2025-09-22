<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['Id_Usuario'])) { header("Location: index.html"); exit; }

require_once "conexion.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

// Recibir id y tipo (producto|kit)
$id   = isset($_GET['id'])   ? (int)$_GET['id']   : 0;
$type = isset($_GET['type']) ? strtolower(trim((string)$_GET['type'])) : '';

if ($id <= 0 || !in_array($type, ['producto','kit'], true)) {
  header("Location: inventario.php?msg=ID_invalido");
  exit;
}

// Con triggers instalados, basta con poner Stock=0
if ($type === 'kit') {
  $sql = "UPDATE kits_productos SET Stock = 0 WHERE id_kit = ?";
} else {
  $sql = "UPDATE productos SET Stock = 0 WHERE id_producto = ?";
}

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Redirige al inventario
header("Location: inventario.php?msg=Eliminado_ok");
exit;
