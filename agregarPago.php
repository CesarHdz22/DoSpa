<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];
  $idI = $_GET['id'];
  $tipo = $_GET['tipo'];

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

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">

  <!-- Librería SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Iconos de FontAwesome -->
 
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

        <section class="registro">

          <form id="formPago" method="POST" onsubmit="validarPago(event)">
              <div>
                  <label for="monto">Monto Pagado:</label>
                  <input type="text" id="monto" name="monto_pagado" required>
              </div>

              <div>
                  <label for="metodo">Método de Pago:</label>
                  <select id="metodo" name="metodo_pago" required>
                      <option value="">-- Selecciona --</option>
                      <option value="efectivo">Efectivo</option>
                      <option value="tarjeta">Tarjeta</option>
                      <option value="transferencia">Transferencia</option>
                      <option value="depósito">Depósito</option>
                      <option value="otros">Otros</option>
                  </select>
              </div>

              <div>
                  <label for="comprobante">Comprobante (link de Drive):</label>
                  <input type="text" id="comprobante" name="comprobante" required>
              </div>

              <button type="submit">Registrar Pago</button>
          </form>
        </section>
    
    </main>
  </div>

</body>
<script>
 async function validarPago(event) {
    event.preventDefault(); // Detener envío automático

    const monto = document.getElementById('monto').value.trim();
    const metodo = document.getElementById('metodo').value;
    const comprobante = document.getElementById('comprobante').value.trim();

    // Variables pasadas desde PHP
    const idI = <?= json_encode($idI) ?>;
    const tipo = <?= json_encode($tipo) ?>;

    // URL de la página anterior
    const returnUrl = document.referrer || 'index.php'; // fallback en caso de no haber referrer

    // Validación de los campos
    const regexMonto = /^[0-9]+(\.[0-9]{1,2})?$/;
    const regexDrive = /^https?:\/\/(drive\.google\.com|docs\.google\.com)\/.+$/;

    if (!regexMonto.test(monto)) {
        return Swal.fire("Monto inválido", "Ingresa un número válido con hasta 2 decimales", "error");
    }

    if (!metodo) {
        return Swal.fire("Método requerido", "Selecciona un método de pago", "error");
    }

    if (!regexDrive.test(comprobante)) {
        return Swal.fire("Enlace inválido", "El comprobante debe ser un enlace válido de Google Drive", "error");
    }

    // Preparar datos para enviar vía fetch
    const formData = new FormData();
    formData.append("monto_pagado", monto);
    formData.append("metodo_pago", metodo);
    formData.append("comprobante", comprobante);

    try {
        const response = await fetch(`registrarPago.php?id=${idI}&tipo=${tipo}`, {
            method: "POST",
            body: formData
        });

        if (!response.ok) throw new Error("Error en la petición");

        await Swal.fire("Pago registrado", "La información se guardó correctamente", "success");

        // Redirigir a la página anterior
        window.location.href = returnUrl;

    } catch (error) {
        console.error(error);
        Swal.fire("Error", "No se pudo registrar el pago", "error");
    }
}

</script>
</html>
<?php
}
?>