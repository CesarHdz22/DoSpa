<?php
session_start();
include_once("conexion.php");
mysqli_set_charset($conexion, 'utf8mb4');

$tipoCliente = $_POST['tipo'] ?? '';
$tipoAgenda  = $_POST['tipo_agenda'] ?? '';
$idRel       = intval($_POST['id_rel'] ?? 0);
$idPersona   = intval($_POST['id_persona'] ?? 0);

if ($tipoCliente !== 'alumna' || !in_array($tipoAgenda, ['taller', 'curso']) || !$idRel || !$idPersona) {
    $_SESSION['msg_error'] = "Datos inválidos o incompletos para registrar como interesada.";
    header("Location: agenda.php");
    exit;
}

$id_alumna = $idPersona;

// 🧠 Verificar si el id_rel realmente existe en su tabla correspondiente
if ($tipoAgenda === 'taller') {
    $tablaPadre = "agenda";
    $columnaPadre = "id_agenda";
} else {
    $tablaPadre = "agenda_cursos";
    $columnaPadre = "id_agenda_curso";
}

$sqlCheckPadre = "SELECT 1 FROM $tablaPadre WHERE $columnaPadre = ? LIMIT 1";
if ($stmt = mysqli_prepare($conexion, $sqlCheckPadre)) {
    mysqli_stmt_bind_param($stmt, "i", $idRel);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $existePadre = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['msg_error'] = "Error al validar referencia: " . mysqli_error($conexion);
    header("Location: agenda.php");
    exit;
}

if (!$existePadre) {
    $_SESSION['msg_error'] = "No existe la sesión seleccionada (referencia inválida).";
    header("Location: agenda.php");
    exit;
}

// 🔍 Comprobar duplicado
if ($tipoAgenda === 'taller') {
    $sqlCheck = "SELECT 1 FROM interesadas WHERE id_alumna = ? AND id_agenda = ? LIMIT 1";
} else {
    $sqlCheck = "SELECT 1 FROM interesadas WHERE id_alumna = ? AND id_agenda_curso = ? LIMIT 1";
}

if ($stmt = mysqli_prepare($conexion, $sqlCheck)) {
    mysqli_stmt_bind_param($stmt, "ii", $id_alumna, $idRel);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        $_SESSION['msg_error'] = "La alumna ya está registrada como interesada en esta sesión.";
        header("Location: agenda.php");
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['msg_error'] = "Error comprobando duplicado: " . mysqli_error($conexion);
    header("Location: agenda.php");
    exit;
}

// ✅ Insertar solo si la referencia existe
if ($tipoAgenda === 'taller') {
    $sqlInsert = "INSERT INTO interesadas (id_alumna, id_agenda) VALUES (?, ?)";
} else {
    $sqlInsert = "INSERT INTO interesadas (id_alumna, id_agenda_curso) VALUES (?, ?)";
}

if ($stmt = mysqli_prepare($conexion, $sqlInsert)) {
    mysqli_stmt_bind_param($stmt, "ii", $id_alumna, $idRel);
    $ok = mysqli_stmt_execute($stmt);
    $err = mysqli_error($conexion);
    mysqli_stmt_close($stmt);
} else {
    $ok = false;
    $err = mysqli_error($conexion);
}

if ($ok) {
    $_SESSION['msg_ok'] = "Interés registrado correctamente.";
} else {
    $_SESSION['msg_error'] = "Error al guardar la interesada: " . $err;
}

header("Location: agenda.php");
exit;
?>
