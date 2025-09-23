<?php
session_start();
include_once("conexion.php");

if(empty($_SESSION['Id_Usuario'])){
    echo json_encode(["status" => "error", "message" => "No tienes permisos"]);
    exit;
}

// Obtener datos del POST
$productos = json_decode($_POST['carrito'], true);

if(!$productos || count($productos) != 1){
    echo json_encode(["status" => "error", "message" => "Producto no válido"]);
    exit;
}

$id_producto = intval($productos[0]['id']); // ID del producto

// Actualizar stock a 0
$sql = "UPDATE productos SET Stock = 0 WHERE id_producto = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_producto);

if(mysqli_stmt_execute($stmt)){
    echo json_encode(["status" => "success", "message" => "Stock actualizado a 0"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al actualizar el stock"]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
