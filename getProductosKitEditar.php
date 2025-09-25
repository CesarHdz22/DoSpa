<?php
include_once("conexion.php");

if (!isset($_GET['idKit'])) {
    echo "<em>Kit no especificado.</em>";
    exit;
}

$idKit = intval($_GET['idKit']);

// Traer solo los productos activos (Stock > 0)
$sql = "SELECT p.id_producto, p.nombre, p.precio_unitario,
               IFNULL(pk.cantidad, 0) AS cantidad,
               IF(pk.id_producto IS NULL, 0, 1) AS seleccionado
        FROM productos p
        LEFT JOIN productos_kits pk
          ON p.id_producto = pk.id_producto AND pk.id_kit = $idKit
        WHERE p.Stock > 0";

$res = mysqli_query($conexion, $sql);

echo "<style>
        .fila-roja { background-color: #ffcccc !important; }
      </style>";

echo "<table name='productosEditables' id='tablaEditarKitProductos' class='display' style='width:100%'>
        <thead>
          <tr>
            <th>Seleccionar</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>";

while ($row = mysqli_fetch_assoc($res)) {
    $checked = $row['seleccionado'] ? "checked" : "";
    $cantidad = $row['cantidad'] > 0 ? $row['cantidad'] : 1;
    $classRow = $row['seleccionado'] ? "fila-roja" : "";  // Si está en el kit → fila roja

    echo "<tr class='{$classRow}'>
            <td><input type='checkbox' class='chkProd' value='{$row['id_producto']}' $checked></td>
            <td>".htmlspecialchars($row['nombre'])."</td>
            <td>$ ".htmlspecialchars($row['precio_unitario'])."</td>
            <td><input type='number' min='1' value='{$cantidad}' class='cantidadProd' style='width:60px;'></td>
          </tr>";
}
echo "</tbody></table>";
?>

