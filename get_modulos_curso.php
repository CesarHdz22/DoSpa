<?php
header('Content-Type: application/json');
require 'conexion.php';

$id_curso = $_GET['id_curso'] ?? null;

if (!$id_curso) {
  echo json_encode(['ok' => false, 'error' => 'Curso no válido']);
  exit;
}

$sql = "SELECT nombre, descripcion, fecha, hora_inicio, hora_fin, status
        FROM modulos
        WHERE id_curso = ?
        ORDER BY fecha, hora_inicio";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$result = $stmt->get_result();

$modulos = [];

while ($row = $result->fetch_assoc()) {
  $modulos[] = $row;
}

echo json_encode([
  'ok' => true,
  'modulos' => $modulos
]);
