<?php
header('Content-Type: application/json');
require 'conexion.php';

$id_curso     = $_POST['id_curso'];
$nombre       = $_POST['nombre'];
$descripcion  = $_POST['descripcion'];
$fecha        = $_POST['fecha'];
$hora_inicio  = $_POST['hora_inicio'];
$hora_fin     = $_POST['hora_fin'];
$status       = $_POST['status'];

// Validación básica
if (
    empty($id_curso) ||
    empty($nombre) ||
    empty($fecha) ||
    empty($hora_inicio) ||
    empty($hora_fin)
) {
    echo json_encode([
        'ok' => false,
        'error' => 'Todos los campos obligatorios deben llenarse'
    ]);
    exit;
}

// Validación lógica de horas (detalle fino 😎)
if ($hora_inicio >= $hora_fin) {
    echo json_encode([
        'ok' => false,
        'error' => 'La hora de inicio debe ser menor que la hora de fin'
    ]);
    exit;
}

$sql = "INSERT INTO modulos
        (id_curso, nombre, descripcion, fecha, hora_inicio, hora_fin, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "issssss",
    $id_curso,
    $nombre,
    $descripcion,
    $fecha,
    $hora_inicio,
    $hora_fin,
    $status
);

if ($stmt->execute()) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode([
        'ok' => false,
        'error' => 'Error al guardar el módulo'
    ]);
}
