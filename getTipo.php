<?php
include("conexion.php");

$tipo = $_POST['tipo'];

if ($tipo == "alumna") {
    $sql = "SELECT * FROM alumnas";
} else {
    $sql = "SELECT * FROM clientes";
}

$result = mysqli_query($conexion, $sql);

// Construir tabla
$html = '
<table id="personasTabla" class="ventas-tabla display">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Correo</th>
        </tr>
    </thead>
    <tbody>
';

while ($mostrar = mysqli_fetch_array($result)) {
    if ($tipo == "alumna") {
        $idPersona = $mostrar['id_alumna'];
    } else {
        $idPersona = $mostrar['id_cliente'];
    }

    $html .= "
        <tr>
            <td>{$idPersona}</td>
            <td>{$mostrar['nombre']} {$mostrar['apat']} {$mostrar['amat']}</td>
            <td>{$mostrar['correo']}</td>
        </tr>
    ";
}

$html .= '
    </tbody>
</table>
';

echo $html;
