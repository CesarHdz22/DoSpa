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
  <link rel="stylesheet" href="css/inscripciones.css">
  
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
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li class="active"><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
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
    
      <section class="inscripciones">
        <div class="inscripciones-container">
            <h2 class="inscripciones-title">Inscripciones</h2>
            <table class="tablaInscripciones" id="tablaInscripciones">
            <thead>
                <tr>
                <th>Id</th>
                <th>Alumna</th>
                <th>Agenda</th>
                <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM intermedia_a";
                    $r1 = mysqli_query($conexion,$sql);

                    while($row = mysqli_fetch_array($r1)){
                        $idAlumna = $row['id_alumna'];
                        $idAgenda = 0;
                        $nom_agenda = "";
                        $nom_sql = "";
                        $apat_sql = "";
                        $amat_sql = "";
                        $tipo = "";
                        
                        $sql2 = "SELECT * FROM alumnas WHERE id_alumna = '$idAlumna'";
                        $r2 = mysqli_query($conexion,$sql2);
                        while($row2 = mysqli_fetch_array($r2)){
                            $nom_sql = $row2['nombre'];
                            $apat_sql = $row2['apat'];
                            $amat_sql = $row2['amat'];
                        }    

                        if(!empty($row['id_agenda'])){
                            $tipo = "Taller";
                            $idAgenda = $row['id_agenda'];
                            $sql3 = "SELECT * FROM talleres WHERE id_taller = (SELECT id_taller FROM agenda WHERE id_agenda = '$idAgenda')";
                        }else{
                            $tipo = "Curso";
                            $idAgenda = $row['id_agenda_curso'];
                            $sql3 = "SELECT * FROM cursos WHERE id_curso = (SELECT id_curso FROM agenda_cursos WHERE id_agenda_curso = '$idAgenda')";
                        }
                        
                        $r3 = mysqli_query($conexion,$sql3);
                        while($row3 = mysqli_fetch_array($r3)){
                            $nom_agenda = $row3['nombre'];
                        }        
                ?>
                <tr>
                <td><a href="detallesInscripcion.php?id=<?php echo $row['id_intermedia'] ?>&tipo=<?php echo $tipo ?>&idC=<?php echo $row['id_alumna'] ?>"><?php echo $row['id_intermedia'] ?></a></td>
                <td><?php echo $nom_sql." ".$apat_sql." ".$amat_sql ?></td>
                <td><?php echo $nom_agenda." - ".$tipo ?></td>
                <td><?php echo $row['fecha']?></td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
        </div>
    </section>


      
    </main>
  </div>
  <script src="librerias/tables.js"></script>
  <script>
    // Inicializar tabla de ventas con Simple-DataTables
    const tablaVentas = new simpleDatatables.DataTable("#tablaInscripciones", {
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