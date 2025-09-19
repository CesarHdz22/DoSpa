<?php
session_start();
include_once("conexion.php");

$idProducto = $_POST['id'];
$nombre = $_POST['nombre'];
$pu = $_POST['pu'];
$stock = $_POST['stock'];

$editarProductos = "UPDATE productos SET nombre = '$nombre', precio_unitario = '$pu', Stock = '$stock' WHERE id_producto = '$idProducto'";

if($r1 = mysqli_query($conexion,$editarProductos)){
    echo "<script> window.location.replace('inventario.php');</script>";
}