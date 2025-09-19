<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];

  $idComprador = $_POST['idComprador'];
  $carrito_json = $_POST['carrito'];
  $carrito = json_decode($carrito_json, true);
  $tipo = $_SESSION['tipoCliente'];

  // Validación mínima
  if (!is_array($carrito)) {
      echo "Carrito inválido.";
      exit;
  }

  // Guardar en sesión para que trabajes con él desde otras páginas si quieres
  $_SESSION['carrito_temp'] = $carrito;

  // Total (si también enviaste total por seguridad lo puedes tomar de $_POST['total'])
  $total = 0;
  foreach ($carrito as $item) {
      $precio = isset($item['precio']) ? floatval($item['precio']) : 0;
      $cant   = isset($item['cantidad']) ? intval($item['cantidad']) : 0;
      $total += $precio * $cant;
  }

  if ($tipo == "cliente"){
      $insertarVenta = "INSERT INTO venta (comprador_tipo,id_cliente,total,estado) ";
  }else if($tipo == "alumna"){
      $insertarVenta = "INSERT INTO venta (comprador_tipo,id_alumna,total,estado) ";
  }
      $insertarVenta .= "VALUES ('$tipo','$idComprador','$total','Pendiente')";

      if($resultado1 = mysqli_query($conexion,$insertarVenta)){
        $idVenta = mysqli_insert_id($conexion);

        foreach ($carrito as $item){

          if($item['type'] == "producto"){
            $insertarDetalle = "INSERT INTO detalle_venta (idVenta,id_producto,cantidad,precio_unitario,subtotal) ";
          }else if($item['type'] == "kit"){
            $insertarDetalle = "INSERT INTO detalle_venta (idVenta,id_kit,cantidad,precio_unitario,subtotal) ";
          }

          $id_seleccion = $item['id'];
          $precio_unitario = $item['precio'];
          $cantidad = $item['cantidad'];
          $subtotal = $item['precio'] * $item['cantidad'];
          
          $insertarDetalle .= "VALUES ('$idVenta','$id_seleccion','$cantidad','$precio_unitario','$subtotal')";
            
          if($resultado2 = mysqli_query($conexion,$insertarDetalle)){

            if($item['type'] == "producto"){
              $actualizarStock = "UPDATE productos SET Stock = Stock - '$cantidad' WHERE id_producto = '$id_seleccion'";
            }else if($item['type'] == "kit"){
              $actualizarStock = "UPDATE kits_productos SET Stock = Stock - '$cantidad' WHERE id_kit = '$id_seleccion'";
            }

            if($resultado3 = mysqli_query($conexion,$actualizarStock)){
              echo "<script> window.location.replace('ventas.php');</script>";
            }

          }
        }
      }

  


}