<?php
include_once("conexion.php");

// Evitar cache del navegador / proxy
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_GET['idKit'])) {
    echo "<em>Kit no especificado.</em>";
    exit;
}

$idKit = intval($_GET['idKit']);

// 1. Traer los productos que ya están en el kit
$sqlKit = "SELECT p.id_producto, p.nombre, pk.cantidad, p.Stock, p.precio_unitario
           FROM productos_kits pk
           JOIN productos p ON p.id_producto = pk.id_producto
           WHERE pk.id_kit = ?";
$stmtKit = mysqli_prepare($conexion, $sqlKit);
mysqli_stmt_bind_param($stmtKit, "i", $idKit);
mysqli_stmt_execute($stmtKit);
$resKit = mysqli_stmt_get_result($stmtKit);

$productosEnKit = [];
if (mysqli_num_rows($resKit) > 0) {
    while ($row = mysqli_fetch_assoc($resKit)) {
        $productosEnKit[$row['id_producto']] = $row; // guardamos por id
    }
}

mysqli_stmt_close($stmtKit);

// 2. Traer todos los productos
$sqlTodos = "SELECT id_producto, nombre, Stock, precio_unitario FROM productos ORDER BY nombre";
$resTodos = mysqli_query($conexion, $sqlTodos);

if (mysqli_num_rows($resTodos) == 0) {
    echo "<em>No hay productos registrados.</em>";
} else {
    echo "
    <table id='tablaProductosKit' class='subtabla'>
        <thead>
            <tr>
                <th>Seleccionar</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>";

    while ($row = mysqli_fetch_assoc($resTodos)) {
        $enKit = isset($productosEnKit[$row['id_producto']]);
        $cantidad = $enKit ? $productosEnKit[$row['id_producto']]['cantidad'] : 1; // por defecto 1 si no está en kit

        $color = ($row['Stock'] == 0 || ($enKit && $cantidad > $row['Stock'])) 
            ? 'style="color:#943154; font-weight:bold; cursor:pointer;" title="No hay suficientes productos en stock"' 
            : '';

        echo "<tr>
                <td>
                    <input type='checkbox' class='chkProd' name='productos[]' value='".htmlspecialchars($row['id_producto'])."' ".($enKit ? 'checked' : '').">
                </td>
                <td {$color}>".htmlspecialchars($row['nombre'])."</td>
                <td>$ ".htmlspecialchars($row['precio_unitario'])."</td>
                <td><input type='number' min='1' value='".htmlspecialchars($cantidad)."' class='cantidadProd' style='width:60px;'></td>
              </tr>";
    }

    echo "  </tbody>
    </table>";
}
?>
