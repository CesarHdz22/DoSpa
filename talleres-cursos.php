<?php
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { header("location: index.html"); exit; }

$Nombre = $_SESSION['nombre'] ?? '';
$Apat   = $_SESSION['apat']   ?? '';
$Amat   = $_SESSION['amat']   ?? '';

mysqli_set_charset($conexion, 'utf8mb4');

/* Para modal de alta (select de maestras en Taller) */
$optsMaestras = mysqli_query($conexion, "SELECT id_maestra, nombre FROM maestras ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>DO SPA - Talleres / Cursos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilos globales -->
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/inventario.css">
  <!-- Estilo específico de esta vista -->
  <link rel="stylesheet" href="css/talleres-cursos.css?v=2">

  <!-- DataTables (simple-datatables) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li class="active"><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
        <li><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
      </ul>
    </aside>

    <!-- Main -->
    <main class="main">
      <!-- Topbar -->
      <header class="topbar">
        <div class="user-info">
          <span><?php echo htmlspecialchars("$Nombre $Apat $Amat", ENT_QUOTES, 'UTF-8'); ?></span>
          <a href="muerte.php"><img src="img/logout.png" id="btn-logout" alt="Salir"></a>
        </div>
      </header>

      <div class="inventario">
        <h2 class="titulo-inventario" align="center">TALLERES / CURSOS</h2>

        <div class="tablas-inventario">
          <!-- TALLERES (catálogo) -->
          <section class="productos">
            <div class="section-header">
              <h3>Talleres</h3>
              <div class="section-actions">
                <!-- DAR DE ALTA Taller -->
                <img src="img/agregar.png" alt="Agregar" class="icon btn-alta" width="20" data-tipo="taller" title="Dar de alta Taller">
              </div>
            </div>

            <table id="TablaTalleres" class="display">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                  <th>Maestra</th>
                 
                  <th>Costo base</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sqlT = "SELECT t.id_taller, t.nombre, m.nombre AS maestra, t.costo_base, t.status
                         FROM talleres t
                         LEFT JOIN maestras m ON m.id_maestra = t.id_maestra
                         ORDER BY t.id_taller DESC";
                if ($rT = mysqli_query($conexion, $sqlT)):
                  while ($row = mysqli_fetch_assoc($rT)): ?>
                    <tr>
                      <td><?php echo (int)$row['id_taller']; ?></td>
                      <td><?php echo htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($row['maestra'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    
                      <td><?php echo '$'.number_format((float)$row['costo_base'], 2); ?></td>
                      <td><?php echo htmlspecialchars(ucfirst($row['status']) ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
              <?php endwhile; mysqli_free_result($rT); endif; ?>
              </tbody>
            </table>
          </section>

          <!-- CURSOS (catálogo) -->
          <section class="kits">
            <div class="section-header">
              <h3>Cursos</h3>
              <div class="section-actions">
                <!-- DAR DE ALTA Curso -->
                <img src="img/agregar.png" alt="Agregar" class="icon btn-alta" width="20" data-tipo="curso" title="Dar de alta Curso">
              </div>
              
            </div>

            <table id="TablaCursos" class="display">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                 
                  <th>Costo base</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sqlC = "SELECT id_curso, nombre, costo_base, status
                         FROM cursos
                         ORDER BY id_curso DESC";
                if ($rC = mysqli_query($conexion, $sqlC)):
                  while ($row = mysqli_fetch_assoc($rC)): ?>
                    <tr>
                      <td><?php echo (int)$row['id_curso']; ?></td>
                      <td><?php echo htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                      
                      <td><?php echo '$'.number_format((float)$row['costo_base'], 2); ?></td>
                      <td><?php echo htmlspecialchars(ucfirst($row['status']) ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
              <?php endwhile; mysqli_free_result($rC); endif; ?>
              </tbody>
            </table>
            
          </section>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL ALTA (DAR DE ALTA taller/curso) -->
  <div class="modal" id="modalAlta" aria-hidden="true">
    <div class="box">
      <header>
        <h3 id="modalAltaTitle">Dar de alta</h3>
        <button class="close" id="cerrarAlta" aria-label="Cerrar">&times;</button>
      </header>

      <form action="alta_taller_curso.php" method="post" id="formAlta">
        <input type="hidden" name="tipo" id="tipoAlta" value="taller">

        <div class="grid">
          <!-- Comunes -->
          <div style="grid-column:1 / -1;">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required>
          </div>

          <div>
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" required>
          </div>

          <div>
            <label for="costo_base">Costo base</label>
            <input type="number" step="0.01" name="costo_base" id="costo_base" required>
          </div>

          <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
              <option value="activo">Activo</option>
              <option value="pendiente">Pendiente</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>


          <div>
            <label for="gastos">Gastos</label>
            <input type="number" step="0.01" name="gastos" id="gastos" value="0.00">
          </div>

          <div id="wrapPreferencial">
            <label for="precio_preferencial">Precio preferencial</label>
            <select name="precio_preferencial" id="precio_preferencial">
              <option value="0">No</option>
              <option value="1">Sí</option>
            </select>
          </div>

          <!-- Solo TALLER -->
          <div id="wrapMaestra" style="display:none;">
            <label for="id_maestra">Maestra</label>
            <select name="id_maestra" id="id_maestra">
              <option value="">-- Selecciona --</option>
              <?php mysqli_data_seek($optsMaestras, 0); while($op = mysqli_fetch_assoc($optsMaestras)): ?>
                <option value="<?php echo (int)$op['id_maestra']; ?>">
                  <?php echo htmlspecialchars($op['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div id="wrapPorcentajes" style="display:none;">
            <label for="porcentaje_delia">Porcentaje Delia</label>
            <input type="number" step="0.01" name="porcentaje_delia" id="porcentaje_delia" value="0.00">
            <label for="porcentaje_caro">Porcentaje Caro</label>
            <input type="number" step="0.01" name="porcentaje_caro" id="porcentaje_caro" value="0.00">
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="btn-mini btn-primary">Guardar</button>
          <button type="button" class="btn-mini" id="cancelarAlta">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- libs -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="librerias/tables.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function(){
    // DataTables
    try { new simpleDatatables.DataTable("#TablaTalleres",{searchable:true,fixedHeight:true,perPage:8}); } catch(e){}
    try { new simpleDatatables.DataTable("#TablaCursos",{searchable:true,fixedHeight:true,perPage:8}); } catch(e){}

    // DAR DE ALTA (icono)
    document.querySelectorAll('.btn-alta').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const tipo = btn.dataset.tipo; // 'taller'|'curso'
        const modal = document.getElementById('modalAlta');
        document.getElementById('tipoAlta').value = tipo;
        document.getElementById('modalAltaTitle').innerText = (tipo==='curso') ? 'Dar de alta CURSO' : 'Dar de alta TALLER';

        // Mostrar/ocultar campos propios del taller
        document.getElementById('wrapMaestra').style.display     = (tipo==='taller')?'block':'none';
        document.getElementById('wrapPorcentajes').style.display = (tipo==='taller')?'grid':'none';

        // Limpiar
        ['nombre','fecha','costo_base','status','ingreso_bruto','gastos','porcentaje_delia','porcentaje_caro','id_maestra'].forEach(id=>{
          const el = document.getElementById(id); if (el) el.value = '';
        });
        document.getElementById('precio_preferencial').value = '0';

        modal.classList.add('open');
      });
    });
    // Cerrar modal alta
    document.getElementById('cerrarAlta').addEventListener('click',()=> document.getElementById('modalAlta').classList.remove('open'));
    document.getElementById('cancelarAlta').addEventListener('click',()=> document.getElementById('modalAlta').classList.remove('open'));
    document.getElementById('modalAlta').addEventListener('click',(e)=>{ if(e.target.id==='modalAlta'){ e.currentTarget.classList.remove('open'); } });

    // Validación simple del form de alta
    document.getElementById('formAlta').addEventListener('submit', function(e){
      const tipo = document.getElementById('tipoAlta').value;
      if (!document.getElementById('nombre').value.trim()){ alert('Nombre es requerido'); e.preventDefault(); return; }
      if (!document.getElementById('fecha').value){ alert('Fecha es requerida'); e.preventDefault(); return; }
      if (!document.getElementById('costo_base').value){ alert('Costo base es requerido'); e.preventDefault(); return; }
      if (tipo==='taller'){
        const ma = document.getElementById('id_maestra').value;
        if (!ma){ alert('Selecciona la Maestra'); e.preventDefault(); return; }
      }
    });
  });
  </script>
</body>
</html>
