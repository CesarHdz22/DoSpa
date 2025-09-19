<?php
session_start();
include_once("conexion.php");

if(!empty($_POST['user']) && !empty($_POST['pass'])){
$usuario=$_POST['user'];
$contra=$_POST['pass'];

$consulta="SELECT * FROM usuarios WHERE User='$usuario' AND Pass='$contra'";

$resultado=mysqli_query($conexion,$consulta);
$filas=mysqli_num_rows($resultado);

while($row=mysqli_fetch_assoc($resultado)) {

$id=$row["Id_Usuario"];
$cargo=$row["Cargo"];
$nombre=$row["Nombre"];
$amat=$row["Amat"];
$apat=$row["Apat"];

$_SESSION['Id_Usuario']=$id;
$_SESSION['cargo']=$cargo;
$_SESSION['amat']=$amat;
$_SESSION['apat']=$apat;
$_SESSION['nombre']=$nombre;
$_SESSION['tipoCliente'] = "";

}

if($filas > 0 ){
    
header('location: main.php');
}

echo "<script>alert('Usuario Inexistente'); window.history.go(-1);</script>";
}else{

    echo "<script>alert('Favor de llenar todos los datos'); window.history.go(-1);</script>";
}
