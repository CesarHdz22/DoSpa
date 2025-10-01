<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT imagen FROM productos WHERE id_producto = $id";
    $res = mysqli_query($conexion, $sql);
    if ($row = mysqli_fetch_assoc($res)) {
        header("Content-Type: image/jpeg"); // cámbialo si usas PNG
        echo $row['imagen'];
        exit;
    }
}
http_response_code(404);
echo "Imagen no disponible";
?>
