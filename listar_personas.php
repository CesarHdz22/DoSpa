<?php
include "conexion.php";

$tipo = $_GET['tipo'] ?? '';

if ($tipo === 'cliente') {
    $sql = "SELECT * FROM clientes";
} elseif ($tipo === 'alumna') {
    $sql = "SELECT * FROM alumnas";
} else {
    echo "<p>No válido</p>";
    exit;
}

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Extra</th><th>Seleccionar</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . ($tipo === 'cliente' ? $row['telefono'] : $row['matricula']) . "</td>";
        echo "<td><input type='radio' name='persona_id' value='" . $row['id'] . "'></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron registros</p>";
}
