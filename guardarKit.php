<?php
include_once("conexion.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombreKit']);
    $precio = floatval($_POST['precioKit']);
    $stock = intval($_POST['stockKit']);
    $productos = json_decode($_POST['productosSeleccionados'], true);

    // Guardar kit en la tabla principal
    $sqlKit = "INSERT INTO kits_productos (nombre, precio_unitario, Stock) 
               VALUES ('$nombre', $precio, $stock)";
    if(mysqli_query($conexion, $sqlKit)){
        $idKit = mysqli_insert_id($conexion); // ID del nuevo kit

        // Insertar productos relacionados con cantidad en productos_kits
        $stmt = $conexion->prepare("INSERT INTO productos_kits (id_kit, id_producto, cantidad) VALUES (?, ?, ?)");
        foreach($productos as $prod){
            $idProd = intval($prod['id']);
            $cantidad = intval($prod['cantidad']);
            $stmt->bind_param("iii", $idKit, $idProd, $cantidad);
            $stmt->execute();
        }
        $stmt->close();

        header("Location: inventario.php?msg=kit_agregado");
        exit;
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
