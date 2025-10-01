<?php
session_start();
include_once("conexion.php");
header('Content-Type: application/json; charset=utf-8');
mysqli_set_charset($conexion, 'utf8mb4');

$eventos = [];

// Talleres
$sqlT = "SELECT a.id_agenda, t.nombre, a.fecha, a.hora_inicio, a.hora_fin, a.ubicacion, a.variacion
         FROM agenda a
         LEFT JOIN talleres t ON t.id_taller = a.id_taller";
if ($rt = mysqli_query($conexion, $sqlT)) {
    while ($row = mysqli_fetch_assoc($rt)) {
        $eventos[] = [
            "id"    => "T-" . $row["id_agenda"],
            "title" => "[Taller] " . $row["nombre"] . " (" . $row["variacion"] . ")",
            "start" => $row["fecha"] . "T" . $row["hora_inicio"],
            "end"   => $row["fecha"] . "T" . $row["hora_fin"],
            "color" => "#3a87ad", // azul para talleres
            "extendedProps" => [
                "ubicacion" => $row["ubicacion"]
            ]
        ];
    }
    mysqli_free_result($rt);
}

// Cursos
$sqlC = "SELECT ac.id_agenda_curso, c.nombre, ac.fecha, ac.hora_inicio, ac.hora_fin, ac.ubicacion, ac.variacion
         FROM agenda_cursos ac
         LEFT JOIN cursos c ON c.id_curso = ac.id_curso";
if ($rc = mysqli_query($conexion, $sqlC)) {
    while ($row = mysqli_fetch_assoc($rc)) {
        $eventos[] = [
            "id"    => "C-" . $row["id_agenda_curso"],
            "title" => "[Curso] " . $row["nombre"] . " (" . $row["variacion"] . ")",
            "start" => $row["fecha"] . "T" . $row["hora_inicio"],
            "end"   => $row["fecha"] . "T" . $row["hora_fin"],
            "color" => "#f39c12", // naranja para cursos
            "extendedProps" => [
                "ubicacion" => $row["ubicacion"]
            ]
        ];
    }
    mysqli_free_result($rc);
}

echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
