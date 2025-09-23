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

// Usar prepared statement
$sql = "SELECT p.nombre, pk.cantidad
        FROM productos_kits pk
        JOIN productos p ON p.id_producto = pk.id_producto
        WHERE pk.id_kit = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $idKit);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) == 0) {
    echo "<em>Este kit no contiene productos.</em>";
} else {
    echo "
    <table id='tablaProductosKit' class='subtabla'>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>";
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td>".htmlspecialchars($row['nombre'])."</td>
                <td>".htmlspecialchars($row['cantidad'])."</td>
              </tr>";
    }
    echo "  </tbody>
    </table>";
}

mysqli_stmt_close($stmt);
?>
