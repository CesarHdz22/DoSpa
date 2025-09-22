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
  <link rel="stylesheet" href="css/inventario.css">
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
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Alumnas / Maestras</span></a></li>
        <li class="active"><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
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

        <div class="inventario">
            <h2 class="titulo-inventario" align="center">INVENTARIO</h2>

            <div class="tablas-inventario">
                <!-- Productos -->
                <section class="productos">
                <div class="section-header">
                    <h3>Productos</h3>
                    <div class="section-actions">
                        <img src="img/editar.png" alt="Editar" class="icon btn-editar" width="20" data-tipo="producto">
                        <img src="img/eliminar.png" alt="Eliminar" class="icon btn-eliminar" width="20" data-tipo="producto">
                        <a href="agregarProducto.php"><img src="img/agregar.png" alt="Agregar" class="icon btn-agregar" width="20"></a>
                    </div>
                </div>
                <table id="TablaProductos" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Precio Unitario</th>
                        <th>Stock</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = "SELECT * FROM productos";
                        $r = mysqli_query($conexion, $sql);
                        while($mostrar = mysqli_fetch_array($r)) {
                    ?>
                    <tr>
                        <td><?php echo $mostrar['id_producto'] ?></td>
                        <td><?php echo $mostrar['nombre'] ?></td>
                        <td><?php echo "$".$mostrar['precio_unitario'] ?></td>
                        <td><?php echo $mostrar['Stock'] ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </section>

                <!-- Kits -->
                <section class="kits">
                <div class="section-header">
                    <h3>Kits</h3>
                    <div class="section-actions">
                        <img src="img/editar.png" alt="Editar" class="icon btn-editar" width="20" data-tipo="kit">
                        <img src="img/eliminar.png" alt="Eliminar" class="icon btn-eliminar" width="20" data-tipo="kit">
                        <a href="agregarKits.php"><img src="img/agregar.png" alt="Agregar" class="icon btn-agregar" width="20"></a>
                    </div>
                </div>
                <table id="TablaKits" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Precio Unitario</th>
                        <th>Stock</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = "SELECT * FROM kits_productos";
                        $r = mysqli_query($conexion, $sql);
                        while($mostrar = mysqli_fetch_array($r)) {
                    ?>
                    <tr>
                        <td><?php echo $mostrar['id_kit'] ?></td>
                        <td><?php echo $mostrar['nombre'] ?></td>
                        <td><?php echo "$".$mostrar['precio_unitario'] ?></td>
                        <td><?php echo $mostrar['Stock'] ?></td>   
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </section>
            </div>
            <br>
            <button class="btn-confirmar" disabled>Confirmar Compra</button>
        
            <div id="carritoResumen" style="display:none; margin-top:12px; padding:8px; border-radius:6px;">
                <strong>Resumen del carrito</strong>
                <div id="detalleCarrito" style="margin-top:8px;"></div>
                <div style="margin-top:8px;">
                    <button id="vaciarCarritoBtn" style="padding:6px 10px; border-radius:6px; cursor:pointer;">Vaciar carrito</button>
                </div>
            </div>

            
            <!-- Modal / Popup (sin estilos inline) -->
            <div id="modalComprador" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalCompradorTitle">
                <button id="cerrarModal" class="btn-cerrar" aria-label="Cerrar">&times;</button>

                <div class="confirmacion" id="confirmacionModalContent">
                <label for="tipo">Selecciona tipo de comprador:</label>
                <select id="tipo" name="tipo" aria-controls="contenedor-tabla">
                    <option value="">-- Seleccionar --</option>
                    <option value="cliente">Cliente</option>
                    <option value="alumna">Alumna</option>
                </select>

                <div id="contenedor-tabla" aria-live="polite"></div>
                </div>
            </div>
            </div>

            

        </div>

        
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="librerias/tables.js"></script>
<script src="librerias/carrito.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

  // Listener delegado: funciona aunque #tipo esté dentro de modal dinámico
  $(document).on('change', '#tipo', function() {
    const tipoSeleccionado = $(this).val();
    const $contenedor = $('#contenedor-tabla');

    console.log('[DEBUG] cambio tipo ->', tipoSeleccionado);

    if (!tipoSeleccionado) {
      $contenedor.empty();
      return;
    }

    $contenedor.html('<p>Cargando...</p>');

    $.ajax({
      url: 'getTipo.php',
      type: 'POST',
      data: { tipo: tipoSeleccionado },
      dataType: 'html',
      success: function(respuestaHtml) {
        console.log('[DEBUG] respuesta recibida length=', respuestaHtml.length);
        $contenedor.html(respuestaHtml);

        // Inicializar simple-datatables si existe la tabla
        const personaTableEl = document.querySelector('#personasTabla');
        if (personaTableEl && !personaTableEl.dataset._datatable) {
          new simpleDatatables.DataTable("#personasTabla", {
            searchable: true,
            fixedHeight: true,
            perPage: 5
          });
          personaTableEl.dataset._datatable = '1';
        }

        // Delegación de click por fila
        $(document).off('click', '#personasTabla tbody tr'); // evitar duplicados
        $(document).on('click', '#personasTabla tbody tr', function() {
          const idComprador = $(this).find('td').first().text().trim();
          console.log('[DEBUG] fila clic id=', idComprador);

          // Obtener carrito
          const carrito = window.getCarrito ? window.getCarrito() : [];
          if (!carrito || carrito.length === 0) {
            alert('El carrito está vacío.');
            return;
          }

          // Crear formulario dinámico
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'confirmar_compra.php'; // Cambiar a tu página destino
          form.style.display = 'none';

          // Input para id del comprador
          const inputId = document.createElement('input');
          inputId.type = 'hidden';
          inputId.name = 'idComprador';
          inputId.value = idComprador;
          form.appendChild(inputId);

          // Input para carrito en JSON
          const inputCarrito = document.createElement('input');
          inputCarrito.type = 'hidden';
          inputCarrito.name = 'carrito';
          inputCarrito.value = JSON.stringify(carrito);
          form.appendChild(inputCarrito);

          // Input para tipo de cliente (si quieres mantenerlo en sesión)
          const inputTipo = document.createElement('input');
          inputTipo.type = 'hidden';
          inputTipo.name = 'tipoCliente';
          inputTipo.value = tipoSeleccionado;
          form.appendChild(inputTipo);

          document.body.appendChild(form);
          form.submit();
        });

      },
      error: function(xhr, status, err) {
        console.error('AJAX getTipo error:', status, err);
        $contenedor.html('<p style="color:red;">Error al cargar. Revisa la consola (Network).</p>');
      }
    });
  });

});
</script>

<script>
// Listener para todos los botones de editar
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
        // Obtener el carrito
        const carrito = window.getCarrito ? window.getCarrito() : [];

        // Verificar que haya exactamente un producto seleccionado
        if (!carrito || carrito.length !== 1) {
            Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Selecciona exactamente un producto para editar."
            });
            return;
        }

        // Obtener el tipo del producto en el carrito
        const tipoCarrito = carrito[0].type; // debe existir este campo en tu array

        // Verificar que el botón corresponda al tipo de producto
        const tipoBtn = btn.dataset.tipo; // agrega data-tipo="kit", "producto", etc. en tus botones

        if (tipoCarrito !== tipoBtn) {
            alert(`Este botón solo funciona para ${tipoBtn}s.`);
            return;
        }

        // Crear formulario dinámico para enviar por POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'editarProductos.php';
        form.style.display = 'none';

        // Input para carrito como JSON
        const inputCarrito = document.createElement('input');
        inputCarrito.type = 'hidden';
        inputCarrito.name = 'carrito';
        inputCarrito.value = JSON.stringify(carrito);
        form.appendChild(inputCarrito);

        document.body.appendChild(form);
        form.submit();
    });
});

// Listener para todos los botones de eliminar
document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
        // Obtener carrito global
        const carrito = window.getCarrito ? window.getCarrito() : [];

        // Verificar que haya exactamente un producto seleccionado
        if (!carrito || carrito.length !== 1) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Selecciona exactamente un producto para eliminar.",
          });
          return; 
        }

        // Tipo del producto en el carrito
        const tipoCarrito = carrito[0].type;

        // Tipo del botón (data-tipo="kit", "producto", etc.)
        const tipoBtn = btn.dataset.tipo;

        if (tipoCarrito !== tipoBtn) {

          Swal.fire({
              icon: "error",
              title: "Oops...",
              text: `Este botón solo funciona para ${tipoBtn}s.`
          });
          return;
        }

        // Confirmación
        if (!confirm(`¿Eliminar ${carrito[0].nombre}?`)) return;

        // Crear formulario dinámico para enviar por POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'eliminarProductos.php';
        form.style.display = 'none';

        // Input para carrito como JSON
        const inputCarrito = document.createElement('input');
        inputCarrito.type = 'hidden';
        inputCarrito.name = 'carrito';
        inputCarrito.value = JSON.stringify(carrito);
        form.appendChild(inputCarrito);

        document.body.appendChild(form);
        form.submit();
    });
});


</script>



<script>
// Resaltar fila seleccionada
$('#TablaProductos tbody').on('click', 'tr', function() {
  $('#TablaProductos tbody tr').removeClass('row-selected');
  $(this).addClass('row-selected');
});
$('#TablaKits tbody').on('click', 'tr', function() {
  $('#TablaKits tbody tr').removeClass('row-selected');
  $(this).addClass('row-selected');
});

// Icono eliminar de la cabecera
document.querySelectorAll('.btn-eliminar').forEach(btn => {
  btn.addEventListener('click', () => {
    const tipo = btn.dataset.tipo; // 'producto' | 'kit'
    const tableId = (tipo === 'kit') ? '#TablaKits' : '#TablaProductos';
    const rowSel = document.querySelector(`${tableId} tbody tr.row-selected`);

    if (!rowSel) {            
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Selecciona una fila primero."
      });
      return;
    }

    const id = (rowSel.querySelector('td') || {}).innerText?.trim();
    if (!id) {
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se pudo leer el ID."
      });
      return;
    }

    if (!confirm(`¿Eliminar este ${tipo}?`)) return;

    window.location.href = `eliminarProductos.php?id=${encodeURIComponent(id)}&type=${encodeURIComponent(tipo)}`;
  });
});


</script>

</body>
</html>
<?php
}
?>