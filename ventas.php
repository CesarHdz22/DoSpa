<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DO SPA - Base</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/ventas.css">
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
      <section id="ventasPage" class="page">
        <h2>Ventas</h2>
        <div class="ventas-container">
            <!-- Tabla de pedidos -->
            <div class="ventas-lista">
            

            <!-- Tabla con Table.js -->
            <table id="ventasTabla" class="ventas-tabla display">
                <thead>
                <tr>
                    <th>Id Venta</th>
                    <th>Fecha</th>
                    <th>Comprador</th>
                    <th>Taller</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $sql="SELECT * FROM venta ORDER BY fecha DESC";
                               
                  $result=mysqli_query($conexion,$sql);
                  while($mostrar=mysqli_fetch_array($result)){

                    if(empty($mostrar['id_alumna'])){
                      $id_comprador = $mostrar['id_cliente'];
                      $sql2 = "SELECT * FROM clientes WHERE id_cliente = '$id_comprador'";
                      }else{
                        $id_comprador = $mostrar['id_alumna'];
                        $sql2 = "SELECT * FROM alumnas WHERE id_alumna = '$id_comprador'";
                      }
                    
                    $result2=mysqli_query($conexion,$sql2);
                  while($mostrar2=mysqli_fetch_array($result2)){
                ?>
                <tr>
                    <td><a href="detalles.php?id=<?php echo $mostrar['idVenta'] ?>&comprador=<?php echo $mostrar['comprador_tipo'] ?>&idcomprador=<?php echo $id_comprador ?>"><?php echo $mostrar['idVenta'] ?></a></td>
                    <td><?php echo $mostrar['fecha'] ?></td>
                    <td><?php echo $mostrar2['nombre']." ".$mostrar2['apat']." ".$mostrar2['amat']." - ".ucfirst($mostrar['comprador_tipo']) ?></td>
                    <td><?php echo $mostrar['id_taller'] ?></td>
                    <td><?php echo "$".$mostrar['total'] ?></td>
                    <td><?php echo $mostrar['estado'] ?></td>
                </tr> 
                <?php
                    }
                  }
                ?>
                
                </tbody>
            </table>
            </div>
        </div>
      </section>

      
    </main>
  </div>
  <script src="librerias/tables.js"></script>
  

  <script>
    // Inicializar tabla de ventas con Simple-DataTables
    const tablaVentas = new simpleDatatables.DataTable("#ventasTabla", {
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