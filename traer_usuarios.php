<?php
    session_start();
    include_once("conexion.php");

    $tipo = $_POST['tipo'] ?? '';

    if(!$tipo) { echo "Tipo no válido"; exit; }

    if($tipo === "cliente"){
        $sql = "SELECT id_cliente AS id, nombre, apat,amat FROM clientes";
    } else if($tipo === "alumna"){
        $sql = "SELECT id_alumna AS id, nombre, apat,amat FROM alumnas";
    } else {
        echo "Tipo no válido"; exit;
    }

    $result = mysqli_query($conexion, $sql);

    if(mysqli_num_rows($result) === 0){
        echo "<p>No hay usuarios de este tipo.</p>";
        exit;
    }

    echo "<table style='width:100%; border-collapse:collapse;' id='tablaUsuarios'>
            <thead>
            <tr><th>Nombre</th><th>Acción</th></tr>
            </thead>
            <tbody>";

    while($row = mysqli_fetch_assoc($result)){
        $nombreCompleto = htmlspecialchars($row['nombre'].' '.$row['apat'].' '.$row['amat']);
        echo "<tr>
                <td>$nombreCompleto</td>
                <td><button class='btn-seleccionar-usuario' data-id='{$row['id']}'>Seleccionar</button></td>
            </tr>";
    }

    echo "</tbody></table>";
