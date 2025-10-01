<?php
session_start();
include_once("conexion.php");

// Verificar si hay sesión activa
if(empty($_SESSION['Id_Usuario'])){
    header("Location: index.html");
    exit;
}

// Verificar que se hayan enviado los datos por POST
// Verificar que se hayan enviado los datos por POST
if(isset($_POST['nombreProducto'], $_POST['precioProducto'], $_POST['stockProducto'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombreProducto']);
    $precio = floatval($_POST['precioProducto']);
    $stock  = intval($_POST['stockProducto']);

    // Procesar imagen
    if(isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
        $imgData = addslashes(file_get_contents($_FILES['imagenProducto']['tmp_name']));
    } else {
        $imgData = null;
    }

    $sql = "INSERT INTO productos (nombre, precio_unitario, Stock, imagen) 
            VALUES ('$nombre', '$precio', '$stock', ".($imgData ? "'$imgData'" : "NULL").")";

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
}
 else {
    // Si no se enviaron los datos
    header("Location: inventario.php");
    exit;
}
?>
