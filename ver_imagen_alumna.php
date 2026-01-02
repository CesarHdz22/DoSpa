<?php
require_once 'conexion.php';

$id = intval($_GET['id'] ?? 0);

$sql = "SELECT imagen FROM alumnas WHERE id_alumna = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagen);
$stmt->fetch();

if ($imagen) {
    header("Content-Type: image/jpeg");
    echo $imagen;
} else {
    header("Content-Type: image/png");
    readfile("img/user_default.png");
}
