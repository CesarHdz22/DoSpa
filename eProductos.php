<?php
session_start();
include_once("conexion.php");

$idProducto = $_POST['id'];
$nombre = $_POST['nombre'];
$pu = $_POST['pu'];
$stock = $_POST['stock'];

if(isset($_FILES['editarImagenProducto']) && $_FILES['editarImagenProducto']['error'] === UPLOAD_ERR_OK) {
    $imgData = addslashes(file_get_contents($_FILES['editarImagenProducto']['tmp_name']));
    $editarProductos = "UPDATE productos 
                        SET nombre = '$nombre', precio_unitario = '$pu', Stock = '$stock', imagen = '$imgData' 
                        WHERE id_producto = '$idProducto'";
} else {
    $editarProductos = "UPDATE productos 
                        SET nombre = '$nombre', precio_unitario = '$pu', Stock = '$stock' 
                        WHERE id_producto = '$idProducto'";
}

if($r1 = mysqli_query($conexion,$editarProductos)){
    echo "<script> window.location.replace('inventario.php');</script>";
}
