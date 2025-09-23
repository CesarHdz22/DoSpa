<?php
include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_intermedia"];
    $alumna = $_POST["alumna"];
    $agenda = $_POST["agenda"];
    $fecha = $_POST["fecha"];

    if (strpos($agenda, "T-") === 0) {
        $id_taller = str_replace("T-", "", $agenda);
        $sql = "UPDATE intermedia_a 
                SET id_alumna = '$alumna', id_agenda = (
                    SELECT id_agenda FROM agenda WHERE id_taller = '$id_taller' LIMIT 1
                ), id_agenda_curso = NULL, fecha = '$fecha'
                WHERE id_intermedia = '$id'";
    } else {
        $id_curso = str_replace("C-", "", $agenda);
        $sql = "UPDATE intermedia_a 
                SET id_alumna = '$alumna', id_agenda_curso = (
                    SELECT id_agenda_curso FROM agenda_cursos WHERE id_curso = '$id_curso' LIMIT 1
                ), id_agenda = NULL, fecha = '$fecha'
                WHERE id_intermedia = '$id'";
    }

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Inscripción actualizada correctamente'); window.location.href='inscripciones.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
