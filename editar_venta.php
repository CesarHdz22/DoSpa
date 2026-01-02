<?php
include 'conexion.php';

$idVenta   = $_POST['idVenta'];
$total     = $_POST['total'];
$estado    = $_POST['estado'];
$id_taller = $_POST['id_taller'];

$sql = "UPDATE venta 
        SET total='$total', estado='$estado', id_taller='$id_taller'
        WHERE idVenta='$idVenta'";

if(mysqli_query($conexion, $sql)){
  echo json_encode(["ok"=>true]);
}else{
  echo json_encode([
    "ok"=>false,
    "error"=>mysqli_error($conexion)
  ]);
}
