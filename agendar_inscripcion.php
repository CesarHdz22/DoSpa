<?php
session_start();
include_once("conexion.php");
mysqli_set_charset($conexion, 'utf8mb4');

// Campos esperados por POST:
// tipo = 'alumna' (de getTipo.php)
// tipo_agenda = 'taller' | 'curso'   (desde el modal, definido por tu JS)
// id_rel = id_agenda  OR id_agenda_curso  (según tipo_agenda)
// id_persona = id_alumna

$tipoCliente = $_POST['tipo'] ?? '';
$tipoAgenda  = $_POST['tipo_agenda'] ?? '';
$idRel       = intval($_POST['id_rel'] ?? 0);
$idPersona   = intval($_POST['id_persona'] ?? 0);

// validaciones basicas
if ($tipoCliente !== 'alumna' || !in_array($tipoAgenda, ['taller','curso']) || !$idRel || !$idPersona) {
    $_SESSION['msg_error'] = "Datos inválidos o incompletos para la inscripción.";
    header("Location: agenda.php");
    exit;
}

// Normalizar nombres
$id_alumna = $idPersona;

// 1) Comprobar duplicado (evitar doble inscripción)
if ($tipoAgenda === 'taller') {
    $sqlCheck = "SELECT 1 FROM intermedia_a WHERE id_alumna = ? AND id_agenda = ? LIMIT 1";
} else { // curso
    $sqlCheck = "SELECT 1 FROM intermedia_a WHERE id_alumna = ? AND id_agenda_curso = ? LIMIT 1";
}

if ($stmt = mysqli_prepare($conexion, $sqlCheck)) {
    mysqli_stmt_bind_param($stmt, "ii", $id_alumna, $idRel);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        echo "<script> alert('La alumna ya está inscrita en esa sesión');</script>";
        $_SESSION['msg_error'] = "La alumna ya está inscrita en esa sesión.";
        header("Location: agenda.php");
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['msg_error'] = "Error comprobando existencia: " . mysqli_error($conexion);
    header("Location: agenda.php");
    exit;
}

// 2) Insertar en intermedia_a (solo la columna correspondiente)
if ($tipoAgenda === 'taller') {
    $sqlInsert = "INSERT INTO intermedia_a (id_alumna, id_agenda, estado) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($conexion, $sqlInsert)) {
        mysqli_stmt_bind_param($stmt, "iis", $id_alumna, $idRel,"Pendiente");
        $ok = mysqli_stmt_execute($stmt);
        $err = mysqli_error($conexion);
        mysqli_stmt_close($stmt);
    } else {
        $ok = false; $err = mysqli_error($conexion);
    }
} else { // curso
    $sqlInsert = "INSERT INTO intermedia_a (id_alumna, id_agenda_curso, estado) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($conexion, $sqlInsert)) {
        mysqli_stmt_bind_param($stmt, "iis", $id_alumna, $idRel,"Pendiente");
        $ok = mysqli_stmt_execute($stmt);
        $err = mysqli_error($conexion);
        mysqli_stmt_close($stmt);
    } else {
        $ok = false; $err = mysqli_error($conexion);
    }
}

// Resultado
if ($ok) {
    $_SESSION['msg_ok'] = "Inscripción guardada correctamente.";
} else {
    $_SESSION['msg_error'] = "Error al guardar la inscripción: " . $err;
}

header("Location: agenda.php");
exit;
