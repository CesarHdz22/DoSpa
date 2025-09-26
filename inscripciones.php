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
  <style>
   tbody tr:hover {
      background: #E9C6C4;
      cursor: pointer;
    }
  </style>
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
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
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
            <div class="section-header">
              <h3>Inscripciones</h3>
              <div class="section-actions">

                <!-- Ícono EDITAR existente (selección + editar) -->
                <img src="img/editar.png" alt="Editar" class="icon btn-editar" width="20" data-tipo="taller" title="Editar">

              </div>
            </div>
           
            <table class="tablaInscripciones" id="tablaInscripciones">
            <thead>
                <tr>
                <th>Id</th>
                <th>Alumna</th>
                <th>Agenda</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                
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
                <td><?php echo "$".$row['total'] ?></td>
                <td><?php echo $row['estado'] ?></td>
                
                </tr>
                <?php } ?>
            </tbody>
            </table>
        </div>
    </section>


      <!-- FORMULARIO FLOTANTE PARA NUEVA INSCRIPCIÓN -->
      <div class="overlay" id="overlay"></div>
      <div class="form-panel-float" id="formAgregar">
        <h3>Nueva Inscripción</h3>
        <form action="guardarInscripcion.php" method="POST">
          <div class="form-control">
            <label for="alumna">Alumna</label>
            <select name="alumna" id="alumna" required>
              <option value="">Selecciona una alumna</option>
              <?php
                $q = mysqli_query($conexion, "SELECT * FROM alumnas");
                while($a = mysqli_fetch_array($q)){
                  echo "<option value='{$a['id_alumna']}'>{$a['nombre']} {$a['apat']} {$a['amat']}</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-control">
            <label for="agenda">Agenda</label>
            <select name="agenda" id="agenda" required>
              <option value="">Selecciona un taller/curso</option>
              <?php
                $q2 = mysqli_query($conexion, "SELECT id_taller, nombre FROM talleres");
                while($t = mysqli_fetch_array($q2)){
                  echo "<option value='T-{$t['id_taller']}'>Taller - {$t['nombre']}</option>";
                }
                $q3 = mysqli_query($conexion, "SELECT id_curso, nombre FROM cursos");
                while($c = mysqli_fetch_array($q3)){
                  echo "<option value='C-{$c['id_curso']}'>Curso - {$c['nombre']}</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-control">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" required>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-mini btn-primary">Guardar</button>
            <button type="button" class="btn btn-mini btn-cerrar">Cancelar</button>
          </div>
        </form>
      </div>
      <!-- FORMULARIO FLOTANTE PARA EDITAR INSCRIPCIÓN -->
      <div class="overlay" id="overlayEditar"></div>
      <div class="form-panel-float" id="formEditar">
        <h3>Editar Inscripción</h3>
        <form action="editarInscripcion.php" method="POST">
          <!-- ID oculto para saber qué editar -->
          <input type="hidden" name="id_intermedia" id="edit_id_intermedia">

          <div class="form-control">
            <label for="edit_alumna">Alumna</label>
            <select name="alumna" id="edit_alumna" required>
              <option value="">Selecciona una alumna</option>
              <?php
                $q = mysqli_query($conexion, "SELECT * FROM alumnas");
                while($a = mysqli_fetch_array($q)){
                  echo "<option value='{$a['id_alumna']}'>{$a['nombre']} {$a['apat']} {$a['amat']}</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-control">
            <label for="edit_agenda">Agenda</label>
            <select name="agenda" id="edit_agenda" required>
              <option value="">Selecciona un taller/curso</option>
              <?php
                $q2 = mysqli_query($conexion, "SELECT id_taller, nombre FROM talleres");
                while($t = mysqli_fetch_array($q2)){
                  echo "<option value='T-{$t['id_taller']}'>Taller - {$t['nombre']}</option>";
                }
                $q3 = mysqli_query($conexion, "SELECT id_curso, nombre FROM cursos");
                while($c = mysqli_fetch_array($q3)){
                  echo "<option value='C-{$c['id_curso']}'>Curso - {$c['nombre']}</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-control">
            <label for="edit_fecha">Fecha</label>
            <input type="date" name="fecha" id="edit_fecha" required>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-mini btn-primary">Actualizar</button>
            <button type="button" class="btn btn-mini btn-cerrar">Cancelar</button>
          </div>
        </form>
      </div>

    </main>
  </div>
  <script src="librerias/tables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Inicializar tabla de ventas con Simple-DataTables
    const tablaVentas = new simpleDatatables.DataTable("#tablaInscripciones", {
      searchable: true,
      fixedHeight: true,
      perPage: 5
    });

  </script>
  <script>
    const btnAgregar = document.querySelector(".btn-agregar");
    const formPanel = document.getElementById("formAgregar");
    const btnCerrar = formPanel.querySelector(".btn-cerrar");

    // Abrir formulario
    btnAgregar.addEventListener("click", () => {
      formPanel.classList.add("open");
    });

    // Cerrar formulario
    btnCerrar.addEventListener("click", () => {
      formPanel.classList.remove("open");
    });

    const overlay = document.getElementById("overlay");

    btnAgregar.addEventListener("click", () => {
      formPanel.classList.add("open");
      overlay.classList.add("open");
    });

    btnCerrar.addEventListener("click", () => {
      formPanel.classList.remove("open");
      overlay.classList.remove("open");
    });

    overlay.addEventListener("click", () => {
      formPanel.classList.remove("open");
      overlay.classList.remove("open");
    });

  </script>

  <script>
    const btnEditar = document.querySelector(".btn-editar");
    const formEditar = document.getElementById("formEditar");
    const btnCerrarEditar = formEditar.querySelector(".btn-cerrar");
    const overlayEditar = document.getElementById("overlayEditar");

    // Cuando clic en editar
    btnEditar.addEventListener("click", () => {
      const fila = document.querySelector("#tablaInscripciones tbody tr.selected");
      if (!fila) {
         Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Por favor selecciona una inscripción de la tabla."
                });
                return;
      }

      // Extraer valores de la fila seleccionada
      const id = fila.cells[0].innerText.trim();
      const alumna = fila.cells[1].innerText.trim();
      const agenda = fila.cells[2].innerText.trim();
      const fecha = fila.cells[3].innerText.trim();

      // Rellenar campos en el form
      document.getElementById("edit_id_intermedia").value = id;
      document.getElementById("edit_fecha").value = fecha;

      // Alumna y agenda -> como los select tienen options con value, lo mejor es setear por value
      [...document.querySelectorAll("#edit_alumna option")].forEach(opt => {
        if (alumna.includes(opt.text)) opt.selected = true;
      });
      [...document.querySelectorAll("#edit_agenda option")].forEach(opt => {
        if (agenda.includes(opt.text)) opt.selected = true;
      });

      // Mostrar modal
      formEditar.classList.add("open");
      overlayEditar.classList.add("open");
    });

    // Cerrar modal
    btnCerrarEditar.addEventListener("click", () => {
      formEditar.classList.remove("open");
      overlayEditar.classList.remove("open");
    });
    overlayEditar.addEventListener("click", () => {
      formEditar.classList.remove("open");
      overlayEditar.classList.remove("open");
    });

    // Permitir seleccionar fila en la tabla
    document.querySelectorAll("#tablaInscripciones tbody tr").forEach(row => {
      row.addEventListener("click", () => {
        document.querySelectorAll("#tablaInscripciones tbody tr").forEach(r => r.classList.remove("selected"));
        row.classList.add("selected");
      });
    });
  </script>


  
</body>
</html>
<?php
}
?>