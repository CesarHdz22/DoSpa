<?php
include_once("conexion.php");

if (!isset($_GET['idKit'])) {
    echo "<em>Kit no especificado.</em>";
    exit;
}

$idKit = intval($_GET['idKit']);

// Obtener productos de este kit
$sql = "SELECT p.nombre, pk.cantidad
        FROM productos_kits pk
        JOIN productos p ON p.id_producto = pk.id_producto
        WHERE pk.id_kit = $idKit";

$res = mysqli_query($conexion, $sql);

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
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['cantidad']}</td>
              </tr>";
    }
    echo "  </tbody>
    </table>";
}
?>
