<?php
session_start();
include_once("conexion.php");

// Verificar si hay sesión activa
if(empty($_SESSION['Id_Usuario'])){
    header("Location: index.html");
    exit;
}

// Verificar que se hayan enviado los datos por POST
if(isset($_POST['nombreProducto'], $_POST['precioProducto'], $_POST['stockProducto'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombreProducto']);
    $precio = floatval($_POST['precioProducto']);
    $stock  = intval($_POST['stockProducto']);

    // Validar que no esté vacío el nombre
    if(empty($nombre) || $precio < 0 || $stock < 0){
        echo "<script>
                alert('Datos inválidos, revisa los campos.');
                window.history.back();
              </script>";
        exit;
    }

    // Insertar producto en la base de datos
    $sql = "INSERT INTO productos (nombre, precio_unitario, Stock) VALUES ('$nombre', '$precio', '$stock')";
    if(mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('Producto agregado correctamente.');
                window.location.href = 'inventario.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al guardar el producto: ".mysqli_error($conexion)."');
                window.history.back();
              </script>";
    }
} else {
    // Si no se enviaron los datos
    header("Location: inventario.php");
    exit;
}
?>
