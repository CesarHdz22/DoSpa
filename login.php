<?php
session_start();
include_once("conexion.php");

if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    $usuario = $_POST['user'];
    $contra  = $_POST['pass'];

    // Consulta segura con prepared statement
    $sql = "SELECT Id_Usuario, Nombre, Apat, Amat, Cargo FROM usuarios WHERE User = ? AND Pass = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contra);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);

            // Guardar sesión correctamente (nota la C mayúscula en 'Cargo')
            $_SESSION['Id_Usuario']  = $row['Id_Usuario'];
            $_SESSION['Cargo']       = $row['Cargo'];
            $_SESSION['nombre']      = $row['Nombre'];
            $_SESSION['apat']        = $row['Apat'];
            $_SESSION['amat']        = $row['Amat'];
            $_SESSION['tipoCliente'] = "";

            header('Location: main.php');
            exit;
        } else {
            echo "<script>alert('Usuario Inexistente'); window.history.go(-1);</script>";
            exit;
        }
    } else {
        // Error preparando la consulta
        echo "<script>alert('Error en el servidor. Intente más tarde.'); window.history.go(-1);</script>";
        exit;
    }

} else {
    echo "<script>alert('Favor de llenar todos los datos'); window.history.go(-1);</script>";
    exit;
}
