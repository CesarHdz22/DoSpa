<?php
include_once("conexion.php");

if (!isset($_GET['idProducto'])) {
    echo json_encode(["status" => "error", "message" => "Producto no especificado"]);
    exit;
}

$idProducto = intval($_GET['idProducto']);

// Obtener los kits donde está este producto
$sql = "SELECT k.nombre 
        FROM kits_productos k
        JOIN productos_kits pk ON k.id_kit = pk.id_kit
        WHERE pk.id_producto = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $idProducto);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$kits = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kits[] = $row['nombre'];
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);

echo json_encode(["status" => "success", "kits" => $kits]);
?>
