<?php 
session_start();
include_once("conexion.php");

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