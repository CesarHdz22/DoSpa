<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];
  $idI = $_GET['idI'];
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
  <style>
    /* ====== Formulario Registro de Pago ====== */
.registro {
  background: #fff;
  padding: 30px 25px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  max-width: 500px;
  margin: 30px auto;
  font-family: 'Segoe UI', sans-serif;
}

.registro h2 {
  font-size: 22px;
  margin-bottom: 20px;
  color: #943154;
  text-align: center;
  font-weight: 600;
}

.registro form div {
  display: flex;
  flex-direction: column;
  margin-bottom: 18px;
}

.registro label {
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 6px;
  color: #444;
}

.registro input[type="text"],
.registro select {
  padding: 10px 12px;
  font-size: 14px;
  border: 1.5px solid #ccc;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.registro input[type="text"]:focus,
.registro select:focus {
  border-color: #943154;
  box-shadow: 0 0 0 2px rgba(148, 49, 84, 0.2);
  outline: none;
}

.registro button {
  background: #943154;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.registro button:hover {
  background: #a94666;
  transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 600px) {
  .registro {
    padding: 20px 15px;
    margin: 20px;
  }
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

        <section class="registro">

          <form id="formPago" 
                method="POST" 
                action="registrarPago.php?tipo=<?= $tipo ?>&id=<?= $idI ?>" 
                onsubmit="return validarPago(event)">
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
                </select>
            </div>

            <div>
                <label for="comprobante">Comprobante (link de Drive):</label>
                <input type="text" id="comprobante" name="comprobante">
            </div>
            <center>
              <button type="submit">Registrar Pago</button>
            </center>
            
          </form>
        </section>
    
    </main>
  </div>

</body>
<script>
function validarPago(event) {
    const monto = document.getElementById('monto').value.trim();
    const metodo = document.getElementById('metodo').value;
    const comprobante = document.getElementById('comprobante').value.trim();

    const regexMonto = /^[0-9]+(\.[0-9]{1,2})?$/;
    const regexDrive = /^https?:\/\/(drive\.google\.com|docs\.google\.com)\/.+$/;

    if (!regexMonto.test(monto)) {
        Swal.fire("Monto inválido", "Ingresa un número válido con hasta 2 decimales", "error");
        event.preventDefault();
        return false;
    }

    if (!metodo) {
        Swal.fire("Método requerido", "Selecciona un método de pago", "error");
        event.preventDefault();
        return false;
    }

    if (metodo != "efectivo"){

      if (!regexDrive.test(comprobante)) {
        Swal.fire("Enlace inválido", "El comprobante debe ser un enlace válido de Google Drive", "error");
        event.preventDefault();
        return false;
      } 
    }

    return true;
}
</script>
</html>
<?php
}
?>