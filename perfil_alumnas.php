<?php
session_start();
include_once("conexion.php");

// Validar sesión
if (empty($_SESSION['Id_Usuario'])) {
  header("location: index.html");
  exit();
}

// Variables de sesión (usuario que está viendo)
$idU = $_SESSION['Id_Usuario'];
$Nombre = $_SESSION['nombre'];
$Amat = $_SESSION['amat'];
$Apat = $_SESSION['apat'];

// Verificar y sanitizar id_alumna
if (!isset($_GET['id_alumna'])) {
  echo "No se proporcionó una alumna válida.";
  exit;
}
$idAlumna = intval($_GET['id_alumna']);

// Obtener datos de la alumna
$sqlAlumno = "SELECT * FROM alumnas WHERE id_alumna = $idAlumna";
$resultAlumno = mysqli_query($conexion, $sqlAlumno);
$mostrar = mysqli_fetch_assoc($resultAlumno);

if (!$mostrar) {
  echo '<div style="padding:20px;color:#b91c1c;font-weight:bold;">⚠️ No se encontró información de la alumna.</div>';
  exit;
}

// Preparar imagen si existe
$imgSrc = '';
if (!empty($mostrar['imagen'])) {
  $imgSrc = 'data:image/jpeg;base64,' . base64_encode($mostrar['imagen']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DO SPA - Perfil Alumna</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/detalles.css">
  <link rel="stylesheet" href="css/perfil_alumna.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <style>
    /* Pequeño estilo para el mensaje cuando no hay inscripciones */
    .msg-vacio {
      text-align:center;
      font-weight:bold;
      padding:15px;
      color:#374151;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
        <div class="logo">DO SPA</div>
        <ul id="menu">
            <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
            <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
            <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
            <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
            <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
            <li class="active"><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
            <li><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
        </ul>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="user-info">
          <span><?php echo htmlspecialchars($Nombre . " " . $Apat . " " . $Amat); ?></span>
          <a href="muerte.php"><img src="img/logout.png" id="btn-logout" alt="Salir"></a>
        </div>
      </header>

      <div class="ventas-detalle" id="detallePedido">
        <div class="perfil-alumna">
          <div class="foto-alumna">
            <?php if ($imgSrc !== ''): ?>
              <img src="<?php echo $imgSrc; ?>" alt="Foto de <?php echo htmlspecialchars($mostrar['nombre']); ?>" width="120" height="120">
            <?php else: ?>
              <img src="img/no-disponible.png" alt="Sin foto" width="120" height="120">
            <?php endif; ?>
          </div>

          <div class="info-alumna">
            <h3><?php echo htmlspecialchars($mostrar['nombre'] . " " . $mostrar['apat'] . " " . $mostrar['amat']); ?></h3>
            <p><b>ID Alumna:</b> <?php echo htmlspecialchars($mostrar['id_alumna'] ?? $mostrar['Id_Alumna']); ?></p>
            <p><b>Teléfono:</b> <?php echo htmlspecialchars($mostrar['telefono']); ?></p>
            <p><b>Correo:</b> <?php echo htmlspecialchars($mostrar['correo']); ?></p>
            <p><b>Dirección:</b> <?php echo htmlspecialchars($mostrar['direccion']); ?></p>
            <p><b>Descuento aplicado:</b> <?php echo htmlspecialchars($mostrar['descuento_aplicado']); ?></p>
            <p><b>Tipo de descuento:</b> <?php echo htmlspecialchars($mostrar['tipo_descuento']); ?></p>
            <p><b>Estatus:</b> 
              <?php echo (isset($mostrar['estatus']) && $mostrar['estatus'] == 1) ? "<span class='activo'>Activo</span>" : "<span class='inactivo'>Inactivo</span>"; ?>
            </p>
          </div>
        </div>
      </div>

      <br><br>

      <!-- Historial de Pagos: obtenemos todos los pagos relacionados a las inscripciones (intermedia_a) de esta alumna -->
      <div class="ventas-detalle">
        <h3>Historial de Pagos</h3>

        <?php
        $sqlPagos = "
          SELECT hp.*
          FROM historial_pagos hp
          INNER JOIN intermedia_a ia ON hp.id_intermedia = ia.id_intermedia
          WHERE ia.id_alumna = $idAlumna
          ORDER BY hp.fecha_pago DESC
        ";
        $resultPagos = mysqli_query($conexion, $sqlPagos);
        ?>

        <table class="display" id="historial">
          <thead>
            <tr>
              <th>Monto Pagado</th>
              <th>Saldo Pendiente</th>
              <th>Fecha de Pago</th>
              <th>Método de Pago</th>
              <th>Comprobante</th>
              <th>ID Inscripción</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($resultPagos && mysqli_num_rows($resultPagos) > 0) {
                while ($p = mysqli_fetch_assoc($resultPagos)) {
                  echo "<tr>";
                  echo "<td>$" . number_format($p['monto_pagado'],2) . "</td>";
                  echo "<td>$" . number_format($p['saldo_pendiente'],2) . "</td>";
                  echo "<td>" . htmlspecialchars($p['fecha_pago']) . "</td>";
                  echo "<td>" . htmlspecialchars(ucfirst($p['metodo_pago'])) . "</td>";
                  echo "<td>";
                    if (!empty($p['comprobante'])) {
                      echo "<a href='" . htmlspecialchars($p['comprobante']) . "' target='_blank'>Ver</a>";
                    } else {
                      echo "—";
                    }
                  echo "</td>";
                  echo "<td>" . htmlspecialchars($p['id_intermedia']) . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='msg-vacio'>No hay pagos registrados.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>

      <br><br>

      <!-- Inscripciones: todas las filas en intermedia_a para esta alumna -->
      <div class="ventas-detalle">
        <h3>Talleres y Cursos inscritos</h3>

        <?php
        $sqlInscrita = "
          SELECT 
            i.id_intermedia,
            COALESCE(t.nombre, c.nombre) AS nombre_evento,
            CASE 
              WHEN i.id_agenda IS NOT NULL THEN 'Taller'
              WHEN i.id_agenda_curso IS NOT NULL THEN 'Curso'
              ELSE 'Desconocido'
            END AS tipo,
            i.total,
            i.estado
          FROM intermedia_a i
          LEFT JOIN agenda a ON a.id_agenda = i.id_agenda
          LEFT JOIN talleres t ON t.id_taller = a.id_taller
          LEFT JOIN agenda_cursos ac ON ac.id_agenda_curso = i.id_agenda_curso
          LEFT JOIN cursos c ON c.id_curso = ac.id_curso
          WHERE i.id_alumna = $idAlumna
        ";
        $resultInscrita = mysqli_query($conexion, $sqlInscrita);
        ?>

        <?php if ($resultInscrita && mysqli_num_rows($resultInscrita) > 0): ?>
          <table class="display" id="inscrita">
            <thead>
              <tr>
                <th>ID Inscripción</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($resultInscrita)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['id_intermedia']); ?></td>
                  <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                  <td><?php echo htmlspecialchars($row['nombre_evento']); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst($row['estado'])); ?></td>
                  <td>$<?php echo number_format($row['total'], 2); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="msg-vacio">No hay inscripciones por el momento.</p>
        <?php endif; ?>
      </div>

      <br><br>

      <!-- Intereses -->
      <div class="ventas-detalle">
        <h3>Interés de la Alumna</h3>
        <?php
        $sqlIntereses = "
          SELECT 
            i.id_interesada,
            i.id_agenda,
            i.id_agenda_curso,
            COALESCE(t.nombre, c.nombre) AS nombre_evento,
            CASE 
              WHEN i.id_agenda IS NOT NULL THEN 'Taller'
              WHEN i.id_agenda_curso IS NOT NULL THEN 'Curso'
              ELSE 'Desconocido'
            END AS tipo
          FROM interesadas i
          LEFT JOIN agenda a ON a.id_agenda = i.id_agenda
          LEFT JOIN talleres t ON t.id_taller = a.id_taller
          LEFT JOIN agenda_cursos ac ON ac.id_agenda_curso = i.id_agenda_curso
          LEFT JOIN cursos c ON c.id_curso = ac.id_curso
          WHERE i.id_alumna = $idAlumna
        ";
        $resultIntereses = mysqli_query($conexion, $sqlIntereses);
        ?>

        <?php if ($resultIntereses && mysqli_num_rows($resultIntereses) > 0): ?>
          <table class="display" id="intereses">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($rowI = mysqli_fetch_assoc($resultIntereses)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($rowI['tipo']); ?></td>
                  <td><?php echo htmlspecialchars($rowI['nombre_evento']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="msg-vacio">No tiene intereses registrados.</p>
        <?php endif; ?>
      </div>

    </main>
  </div>

  <script src="librerias/tables.js"></script>
  <script>
    // Inicializar tablas si existen en DOM
    if (document.querySelector("#historial")) new simpleDatatables.DataTable("#historial", { perPage: 5 });
    if (document.querySelector("#inscrita")) new simpleDatatables.DataTable("#inscrita", { perPage: 5 });
    if (document.querySelector("#intereses")) new simpleDatatables.DataTable("#intereses", { perPage: 5 });
  </script>
</body>
</html>
