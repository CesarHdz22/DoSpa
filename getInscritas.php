<?php
include("conexion.php");

$id   = $_POST['id'] ?? 0;
$tipo = $_POST['tipo'] ?? '';

if (!$id) { echo "ID no válido"; exit; }

if ($tipo === "taller") {
    $sql = "SELECT a.id_alumna, a.nombre, a.apat, a.amat, a.correo
            FROM intermedia_a ia
            JOIN alumnas a ON ia.id_alumna = a.id_alumna
            WHERE ia.id_agenda = $id";
} else if ($tipo === "curso") {
    $sql = "SELECT a.id_alumna, a.nombre, a.apat, a.amat, a.correo
            FROM intermedia_a ia
            JOIN alumnas a ON ia.id_alumna = a.id_alumna
            WHERE ia.id_agenda_curso = $id";
} else {
    echo "Tipo no válido"; exit;
}

$result = mysqli_query($conexion, $sql);

$html = '<table class="ventas-tabla"><thead><tr>
          <th>ID</th><th>Nombre</th><th>Correo</th></tr></thead><tbody>';

while($row = mysqli_fetch_assoc($result)){
    $html .= '<tr>
                <td>'.$row['id_alumna'].'</td>
                <td>'.$row['nombre'].' '.$row['apat'].' '.$row['amat'].'</td>
                <td>'.$row['correo'].'</td>
              </tr>';
}

$html .= '</tbody></table>';

echo $html;
