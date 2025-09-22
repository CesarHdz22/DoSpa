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
  <link rel="stylesheet" href="css/main.css">

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
        <li class="active"><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li>  
        <li><a href="inventario.php"><i class="fas fa-layer-group"></i>Inventario</a></li>
      </ul>
    </aside>


    <!-- Contenido principal -->
    <main class="main">
      <!-- Barra superior -->
      <header class="topbar">
        <div class="user-info">
          <span><?php echo $Nombre." ".$Apat." ".$Amat ?></span>
          <a href="muerte.php"><img src="img/logout.png" width="20px"></a>
        </div>
      </header>

      <!-- 🔹 Aquí irá el contenido específico de cada página -->
      <section id="dashboardPage" class="page active">
        <h2>Panel</h2>
        <section class="cards">
          <div class="card">
            <h3>Clientes</h3>
            <p class="number">45,679</p>
            <small>Aumento del 20%</small>
          </div>
          <div class="card">
            <h3>Pedidos</h3>
            <p class="number">80,927</p>
            <small>Aumento del 60%</small>
          </div>
          <div class="card">
            <h3>Entregas</h3>
            <p class="number">22,339</p>
            <small>Disminución del 2%</small>
          </div>
          <div class="card">
            <h3>Usuarios</h3>
            <p class="number">+1,900</p>
            <small>Crecimiento estable</small>
          </div>
          <div class="card revenue">
            <h3>Ingresos</h3>
            <p class="number">36,568</p>
            <small>Ingresos totales</small>
            <div class="stats">
              <span class="growth">+40%</span>
              <span class="refund">2.5%</span>
              <span class="online">+23.6%</span>
            </div>
          </div>
        </section>
        <section class="graphs">
          <div class="graph-card">
            <h3>Categorías de productos</h3>
            <canvas id="pieChart"></canvas>
          </div>
          <div class="graph-card">
            <h3>Visitas a productos</h3>
            <canvas id="lineChart"></canvas>
          </div>
        </section>
      </section>

      
    </main>
  </div>
  <script src="librerias/tables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Gráficas (solo cargan en el panel)
    function initCharts() {
      const pieCtx = document.getElementById("pieChart");
      if (pieCtx) {
        new Chart(pieCtx, {
          type: "pie",
          data: {
            labels: ["Automóviles", "Maquinaria", "Decoración del hogar", "Químicos"],
            datasets: [{
              data: [40, 30, 20, 10],
              backgroundColor: ["#ff7f50", "#6a0dad", "#9370db", "#ff6347"]
            }]
          }
        });
      }

      const lineCtx = document.getElementById("lineChart");
      if (lineCtx) {
        new Chart(lineCtx, {
          type: "line",
          data: {
            labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
            datasets: [{
              label: "Visitas",
              data: [12, 19, 10, 15, 22, 18, 14],
              borderColor: "#6a0dad",
              backgroundColor: "rgba(106,13,173,0.2)",
              tension: 0.4,
              fill: true,
              pointBackgroundColor: "#ff7f50"
            }]
          }
        });
      }
    }
      initCharts();

  </script>

</body>
</html>
<?php
}
?>