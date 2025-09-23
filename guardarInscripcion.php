<?php
session_start();
include_once("conexion.php");

if (empty($_SESSION['Id_Usuario'])) {
    header("location: index.html");
    exit;
}

// --- Validar datos recibidos ---
if (!isset($_POST['alumna'], $_POST['agenda'], $_POST['fecha'])) {
    echo "Error: Faltan datos obligatorios.";
    exit;
}

$idAlumna = mysqli_real_escape_string($conexion, $_POST['alumna']);
$agenda   = $_POST['agenda']; // viene como "T-1" o "C-3"
$fecha    = mysqli_real_escape_string($conexion, $_POST['fecha']);

$idAgendaTaller = "NULL";
$idAgendaCurso  = "NULL";

// --- Separar si es taller o curso ---
if (strpos($agenda, "T-") === 0) {
    $idTaller = substr($agenda, 2);

    // buscar la agenda correspondiente al taller
    $q = mysqli_query($conexion, "SELECT id_agenda FROM agenda WHERE id_taller = '$idTaller' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q)) {
        $idAgendaTaller = $r['id_agenda'];
    } else {
        echo "Error: No se encontró agenda para este taller.";
        exit;
    }

} elseif (strpos($agenda, "C-") === 0) {
    $idCurso = substr($agenda, 2);

    // buscar la agenda correspondiente al curso
    $q = mysqli_query($conexion, "SELECT id_agenda_curso FROM agenda_cursos WHERE id_curso = '$idCurso' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q)) {
        $idAgendaCurso = $r['id_agenda_curso'];
    } else {
        echo "Error: No se encontró agenda para este curso.";
        exit;
    }
} else {
    echo "Error: formato de agenda inválido.";
    exit;
}

// --- Insertar en intermedia_a ---
$sql = "INSERT INTO intermedia_a (id_alumna, id_agenda, id_agenda_curso, fecha) 
        VALUES ('$idAlumna', $idAgendaTaller, $idAgendaCurso, '$fecha')";

if (mysqli_query($conexion, $sql)) {
    // redirigir a inscripciones.php con éxito
    header("Location: inscripciones.php?msg=success");
    exit;
} else {
    echo "Error al guardar la inscripción: " . mysqli_error($conexion);
}
?>
