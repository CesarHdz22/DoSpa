<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKit = intval($_POST['idKit']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombreKit']);
    $precio = floatval($_POST['precioKit']);
    $stock = intval($_POST['stockKit']);
    $productos = json_decode($_POST['productosSeleccionados'], true);

    // Actualizar datos del kit
    $sql = "UPDATE kits_productos 
            SET nombre='$nombre', precio_unitario=$precio, Stock=$stock 
            WHERE id_kit=$idKit";
    mysqli_query($conexion, $sql);

    // Borrar productos actuales
    mysqli_query($conexion, "DELETE FROM productos_kits WHERE id_kit=$idKit");

    // Insertar los seleccionados
    foreach ($productos as $p) {
        $idProd = intval($p['id']);
        $cant = intval($p['cantidad']);
        $sqlIns = "INSERT INTO productos_kits (id_kit, id_producto, cantidad) 
                   VALUES ($idKit, $idProd, $cant)";
        mysqli_query($conexion, $sqlIns);
    }

    header("Location: inventario.php");
    exit;
}
?>
