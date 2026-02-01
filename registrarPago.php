<?php
session_start();
include_once("conexion.php");

$tipo = $_GET['tipo'];
$idI = $_GET['id'];
$monto = $_POST['monto_pagado'];
$metodo_pago = $_POST['metodo_pago'];
$comprobante = $_POST['comprobante'];
$total = 0.0;


if($tipo == "inscripcion"){
    $consultaPrincipal = "SELECT * FROM historial_pagos WHERE id_intermedia = '$idI'";
}else{
    $consultaPrincipal = "SELECT * FROM historial_pagos WHERE idVenta = '$idI'";
}
$resultado = mysqli_query($conexion,$consultaPrincipal);
$filas = mysqli_num_rows($resultado);
if($filas == 0){
    if($tipo == "inscripcion"){
        $sql2 = "SELECT total FROM intermedia_a WHERE id_intermedia = '$idI'";
    }else{
        $sql2 = "SELECT total FROM venta WHERE idVenta = '$idI'";
    }
     $r2 = mysqli_query($conexion,$sql2);
    while($row = mysqli_fetch_array($r2)){
        $total = $row['total'];
    }
}else{
     if($tipo == "inscripcion"){
        $sql2 = "SELECT saldo_pendiente FROM historial_pagos WHERE id_intermedia = '$idI' ORDER BY fecha_pago DESC LIMIT 1";
    }else{
        $sql2 = "SELECT saldo_pendiente FROM historial_pagos WHERE idVenta = '$idI' ORDER BY fecha_pago DESC LIMIT 1";
    }
     $r2 = mysqli_query($conexion,$sql2);
    while($row = mysqli_fetch_array($r2)){
        $total = $row['saldo_pendiente'];
    }
}
$saldo_pendiente = $total - $monto;




if($saldo_pendiente < 0){
    echo "<script> alert('No puede pagar mas del total de la $tipo'); window.history.go(-1); </script>";
}else{

    $sql = "INSERT INTO historial_pagos";

    if($tipo == "inscripcion"){
        $sql .= " (id_intermedia,monto_pagado,saldo_pendiente,metodo_pago,concepto,comprobante) VALUES ('$idI','$monto','$saldo_pendiente','$metodo_pago','$tipo','$comprobante')";
    }else{
        $sql .= " (idVenta,monto_pagado,saldo_pendiente,metodo_pago,concepto,comprobante) VALUES ('$idI','$monto','$saldo_pendiente','$metodo_pago','$tipo','$comprobante')";
    }

    if($r = mysqli_query($conexion,$sql)){
        echo "<script> window.history.go(-2); </script>";
    }else{
        echo "<script> alert('Error en la consulta'); window.history.go(-1); </script>";

    }

}


