<?php
include("conexion.php");

$tipo = $_POST['tipo'];
$_SESSION['tipoCliente'] = $tipo;
if ($tipo == "alumna") {
    $sql = "SELECT * FROM alumnas";
} else {
    $sql = "SELECT * FROM clientes";
}



$result = mysqli_query($conexion, $sql);

// Construir tabla
$html = '
<section class="tipos">
    <div class="tipos-container">
        <div class="section-header">
            <h3>'.ucfirst($tipo).'s</h3>
            <div class="section-actions">
                <img src="img/agregar.png" 
                    alt="Agregar" 
                    class="icon btn-agregar-kit" 
                    width="20" 
                    style="cursor:pointer;"
                    data-tipo="'.$tipo.'">
            </div>
        </div>

        <table id="personasTabla" class="tablaTipos">
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
    $idPersona = ($tipo == "alumna") ? $mostrar['id_alumna'] : $mostrar['id_cliente'];
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
    </div>
</section>
';


echo $html;
