<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];
  $idVenta = $_GET['id'];
  $tipo_comprador = $_GET['comprador'];
  $id_comprador = $_GET['idcomprador'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DO SPA - Base</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/detalles.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
  <!-- Iconos de FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
</head>
<body>
  <div class="dashboard">
    <!-- Barra lateral -->
    <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li class="active"><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Alumnas / Maestras</span></a></li>
        <li><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
      </ul>
    </aside>


    <!-- Contenido principal -->
    <main class="main">
      <!-- Barra superior -->
      <header class="topbar">
        <div class="user-info">
          <span><?php echo $Nombre." ".$Apat." ".$Amat ?></span>
          <a href="muerte.php"><img src="img/logout.png" id="btn-logout" alt="Salir"></a>
        </div>
      </header>

      <!-- 🔹 Aquí irá el contenido específico de cada página -->
      
      <div class="ventas-detalle" id="detallePedido">

            <?php
            $sql4="SELECT estado FROM venta WHERE idVenta = '$idVenta'";
            $result4 = mysqli_query($conexion,$sql4);
            while($mostrar4=mysqli_fetch_array($result4)){
              $estado = $mostrar4['estado'];
            ?> 
            <h3>Venta #<?php echo $idVenta." - ".$mostrar4['estado'] ?></h3>
            
            <?php
            }
            ?>
            <div class="cliente">
               
                <?php
                    
                    if($tipo_comprador == "Alumna"){
                        $sql="SELECT * FROM alumnas WHERE id_alumna = '$id_comprador'";
                    }else{
                        $sql="SELECT * FROM clientes WHERE id_cliente = '$id_comprador'";
                    }

                    $result=mysqli_query($conexion,$sql);
                    while($mostrar=mysqli_fetch_array($result)){
                ?>
                <div>
                <strong><?php echo $mostrar['nombre']." ".$mostrar['apat']." ".$mostrar['amat'] ?></strong><br>
                <small><b>Contacto: </b><?php echo $mostrar['telefono']." - ".$mostrar['correo'] ?></small>
                </div>
                <?php
                    }
                ?>

            </div>
                
            <h4>Productos</h4>
            <?php
                    $sql="SELECT * FROM detalle_venta WHERE idVenta = '$idVenta'";
                                
                    $result=mysqli_query($conexion,$sql);
                    while($mostrar=mysqli_fetch_array($result)){
                        if(empty($mostrar['id_producto'])){
                            $idProducto = $mostrar['id_kit'];
                            $sql1 = "SELECT * FROM kits_productos WHERE id_kit = '$idProducto'";
                        }else{
                            $idProducto = $mostrar['id_producto'];
                            $sql1 = "SELECT * FROM productos WHERE id_producto = '$idProducto'";
                        }

                        
                        $result1=mysqli_query($conexion,$sql1);
                        while($mostrar1=mysqli_fetch_array($result1)){
                 ?>
            <ul class="items">
                <li><?php echo "x".$mostrar['cantidad']." ".$mostrar1['nombre']." - ". $mostrar1['precio_unitario']?></li>
            </ul>
                <?php
                        }
                    }
                $sql = "SELECT * FROM venta WHERE idVenta = '$idVenta'";
                $result=mysqli_query($conexion,$sql);
                while($mostrar=mysqli_fetch_array($result)){
            ?>

            <div class="total">
                <strong>Total: </strong> <?php echo "$" . $mostrar['total'] ?>
            </div>
            <?php
                }
            ?>
                
                  
            </div>
          <br><br>
            <div class="ventas-detalle">
                <h3>Historial de Pagos</h3>

                <?php
                  if($estado == "Pendiente"){
                ?>
                <a href="agregarPago.php?tipo=venta&idI=<?php echo $idVenta ?>"><img src="img/agregar.png" alt="Agregar" class="icon btn-agregar" width="20"></a>
                <?php
                  }
                ?>
                
                <table class="display" id="historial">
                    <thead>
                        <tr>
                            <th>Monto Pagado</th>
                            <th>Saldo Pendiente</th>
                            <th>Fecha de Pago</th>
                            <th>Metodo de Pago</th>
                            <th>Comprobante</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql3 = "SELECT * FROM historial_pagos WHERE idVenta = '$idVenta' ORDER BY fecha_pago DESC";
                            $result3 = mysqli_query($conexion,$sql3);
                            while($mostrar3=mysqli_fetch_array($result3)){
                            ?>
                        <tr>
                            
                            <td><?php echo "$".$mostrar3['monto_pagado'] ?></td>
                            <td><?php echo "$".$mostrar3['saldo_pendiente'] ?></td>
                            <td><?php echo $mostrar3['fecha_pago'] ?></td>
                            <td><?php echo ucfirst($mostrar3['metodo_pago']) ?></td>
                            <td><a href="<?php echo $mostrar3['comprobante'] ?>" target="_blank"><?php echo $mostrar3['comprobante'] ?></a></td>
                            
                        </tr>
                        <?php
                            }
                            ?>
                    </tbody>
                </table>
            
            </div>

    </main>
  </div>
  <script src="librerias/tables.js"></script>
  <script>
    // Inicializar tabla de ventas con Simple-DataTables
    const tablaVentas = new simpleDatatables.DataTable("#historial", {
      searchable: true,
      fixedHeight: true,
      perPage: 5
    });

  </script>

</body>
</html>
<?php
}
?>