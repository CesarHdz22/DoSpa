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

  <style>
      /* Estilos para el modal flotante */
      #modalKitProductos .modal-content {
        background: #fff;
        padding: 24px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        margin: auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      }

      /* Encabezado */
      #modalKitProductos h3 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: bold;
      }

      /* Botón cerrar */
      #modalKitProductos .btn-cerrar {
        position: absolute;
        top: 12px;
        right: 16px;
        background: transparent;
        border: none;
        font-size: 24px;
        color: #333;
        cursor: pointer;
      }

      /* Tabla interna dentro del modal */
      #tablaProductosKit {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      /* Encabezado de la tabla */
      #tablaProductosKit thead {
        background-color: #f2f2f2;
      }

      #tablaProductosKit th {
        text-align: left;
        padding: 8px 12px;
        font-weight: bold;
        border-bottom: 1px solid #ccc;
      }

      /* Celdas de la tabla */
      #tablaProductosKit td {
        padding: 8px 12px;
        border-bottom: 1px solid #e0e0e0;
      }

      /* Filas alternas */
      #tablaProductosKit tbody tr:nth-child(even) {
        background-color: #fafafa;
      }

      /* Responsive para pantallas pequeñas */
      @media screen and (max-width: 500px) {
        #modalKitProductos .modal-content {
          padding: 16px;
        }

        #tablaProductosKit th,
        #tablaProductosKit td {
          font-size: 14px;
          padding: 6px 8px;
        }
      }
           .modal {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
      }

      .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      }

      .modal-content h3 {
        margin-bottom: 15px;
        text-align: center;
      }

      .modal-content .form-group {
        margin-bottom: 12px;
      }

      .modal-content input, 
      .modal-content textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #bbb;
        border-radius: 6px;
      }

      .btn-cerrar {
        background: none;
        border: none;
        font-size: 22px;
        float: right;
        cursor: pointer;
      }
      #modalKitProductos .modal-content {
        width: 500px;
        max-width: 90%;
      }

      .btn-ver-productos {
          align-self: flex-start;
          margin-bottom: 0px;
          padding: 9px 18px;
          background: #943154;
          border: none;
          border-radius: 6px;
          color: white;
          font-size: 10px;
          cursor: pointer;
          transition: 0.2s ease;
      }
      .btn-ver-productos:hover {
        background: #45a049;
          transition: 0.2s ease;
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
                        <img src="img/agregar.png" alt="Agregar" class="icon btn-agregar-kit"  width="20" style="cursor:pointer;">
                    </div>
                </div>
                <table id="TablaKits" class="display">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Precio Unitario</th>
                        <th>Stock</th>
                        <th>Accion</th>
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
                          <td>
                             <button class="btn-ver-productos"  data-id="<?php echo $mostrar['id_kit'] ?>">Ver productos</button>
                          </td>
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

            <div id="modalKitProductos" class="modal" style="display:none;" aria-hidden="true">
              <div class="modal-content" role="dialog" aria-modal="true">
                <button id="cerrarModalKitProductos" class="btn-cerrar" aria-label="Cerrar">&times;</button>
                <h3>Productos del Kit</h3>
                <div id="contenidoKitProductos">
                  <p>Cargando...</p>
                </div>
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
            <!-- Modal Agregar Kit -->
            <div id="modalAgregarKit" class="modal" aria-hidden="true" style="display:none;">
              <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalAgregarKitTitle">
                <button id="cerrarModalKit" class="btn-cerrar" aria-label="Cerrar">&times;</button>
                
                <h3 id="modalAgregarKitTitle">Agregar Kit</h3>
                <form id="formAgregarKit" method="POST" action="guardarKit.php">
                  <div class="form-group">
                    <label for="nombreKit">Nombre del Kit:</label>
                    <input type="text" id="nombreKit" name="nombreKit" required>
                  </div>

                  <div class="form-group">
                    <label for="precioKit">Precio Unitario:</label>
                    <input type="number" id="precioKit" name="precioKit" required min="0" step="0.01">
                  </div>

                  <div class="form-group">
                    <label for="stockKit">Stock:</label>
                    <input type="number" id="stockKit" name="stockKit" required min="1">
                  </div>

                  <h4>Seleccionar productos del kit:</h4>
                <table id="productosSeleccionables" class="display" style="width:100%">
                  <thead>
                    <tr>
                      <th>Seleccionar</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sqlProd = "SELECT * FROM productos";
                      $resProd = mysqli_query($conexion, $sqlProd);
                      while($p = mysqli_fetch_assoc($resProd)){
                        echo "<tr>
                          <td><input type='checkbox' class='chkProd' value='{$p['id_producto']}'></td>
                          <td>{$p['nombre']}</td>
                          <td>$ {$p['precio_unitario']}</td>
                          <td><input type='number' min='1' value='1' class='cantidadProd' style='width:60px;'></td>
                        </tr>";
                      }
                    ?>
                  </tbody>
                </table>


                  <input type="hidden" name="productosSeleccionados" id="productosSeleccionados">

                  <button type="submit" class="btn-confirmar">Guardar Kit</button>
                </form>
              </div>
            </div>
      </div>

      <!-- Modal Editar Kit -->
      <div id="modalEditarKit" class="modal" aria-hidden="true" style="display:none;">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalEditarKitTitle">
          <button id="cerrarModalEditarKit" class="btn-cerrar" aria-label="Cerrar">&times;</button>
          
          <h3 id="modalEditarKitTitle">Editar Kit</h3>
          <form id="formEditarKit" method="POST" action="actualizarKit.php">
            <input type="hidden" name="idKit" id="editarIdKit">

            <div class="form-group">
              <label for="editarNombreKit">Nombre del Kit:</label>
              <input type="text" id="editarNombreKit" name="nombreKit" required>
            </div>

            <div class="form-group">
              <label for="editarPrecioKit">Precio Unitario:</label>
              <input type="number" id="editarPrecioKit" name="precioKit" required min="0" step="0.01">
            </div>

            <div class="form-group">
              <label for="editarStockKit">Stock:</label>
              <input type="number" id="editarStockKit" name="stockKit" required min="1">
            </div>

            <h4>Productos del kit:</h4>
            <div id="productosEditarKit">
              <p>Cargando productos...</p>
            </div>

            <input type="hidden" name="productosSeleccionados" id="productosSeleccionadosEditar">

            <button type="submit" class="btn-confirmar">Actualizar Kit</button>
          </form>
        </div>
      </div>

    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" ></script>
  <script src="librerias/tables.js"></script>
  <script src="librerias/carrito.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>

  // Listener solo para el botón de editar productos
document.querySelectorAll('.btn-editar[data-tipo="producto"]').forEach(btn => {
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
        const tipoBtn = btn.dataset.tipo; // debe ser "producto"

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

<script>
    document.getElementById("formAgregarKit").addEventListener("submit", function(e){
      const seleccionados = [];
      document.querySelectorAll("#productosSeleccionables tbody tr").forEach(row => {
        const chk = row.querySelector(".chkProd");
        const cantidad = row.querySelector(".cantidadProd").value;
        if(chk.checked){
          seleccionados.push({
            id: chk.value,
            cantidad: cantidad
          });
        }
      });

      if(seleccionados.length === 0){
        alert("Selecciona al menos un producto para el kit.");
        e.preventDefault();
        return;
      }

      document.getElementById("productosSeleccionados").value = JSON.stringify(seleccionados);
    });
</script>

<script>
  // Abrir modal de Agregar Kit
    document.querySelector('.btn-agregar-kit').addEventListener('click', () => {
      document.getElementById('modalAgregarKit').style.display = 'flex';
      document.getElementById('modalAgregarKit').setAttribute('aria-hidden', 'false');
    });

    // Cerrar modal de Agregar Kit
    document.getElementById('cerrarModalKit').addEventListener('click', () => {
      document.getElementById('modalAgregarKit').style.display = 'none';
      document.getElementById('modalAgregarKit').setAttribute('aria-hidden', 'true');
    });

    // También cerrar al hacer clic fuera del contenido
    window.addEventListener('click', (e) => {
      const modal = document.getElementById('modalAgregarKit');
      if (e.target === modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
      }
    });
</script>




<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar DataTable SOLO UNA VEZ
    const tablaKits = document.querySelector("#TablaKits");
    if (tablaKits && !tablaKits.dataset._datatable) {
        new simpleDatatables.DataTable("#TablaKits", {
            searchable: true,
            fixedHeight: true,
            perPage: 5
        });
        tablaKits.dataset._datatable = "1";
    }

    // Delegación de evento para "Ver productos"
    $(document).on('click', '#TablaKits .btn-ver-productos', function () {
       const idKit = $(this).closest('tr').find('td:first').text().trim();
        console.log("ID del kit clicado:", idKit);

        $('#contenidoKitProductos').html('<p>Cargando...</p>');
        $('#modalKitProductos').fadeIn().attr('aria-hidden', 'false');

        // Petición AJAX con cache-buster
        $.get('getKitProductos.php', { idKit: idKit, t: Date.now() }, function(data) {
            $('#contenidoKitProductos').html(data);
        });
    });

    // Cerrar modal
    $('#cerrarModalKitProductos').on('click', function() {
        $('#modalKitProductos').fadeOut().attr('aria-hidden', 'true');
    });

    $(window).on('click', function(e) {
        if (e.target === $('#modalKitProductos')[0]) {
            $('#modalKitProductos').fadeOut().attr('aria-hidden', 'true');
        }
    });
});


// Abrir modal de editar kit
document.querySelector('.btn-editar[data-tipo="kit"]').addEventListener('click', () => {
  const rowSel = document.querySelector('#TablaKits tbody tr.row-selected');
  if (!rowSel) {
    Swal.fire({ icon:"error", title:"Oops...", text:"Selecciona un kit primero." });
    return;
  }

  const idKit = rowSel.querySelector('td').innerText.trim();

  // Llenar inputs con la fila seleccionada
  document.getElementById('editarIdKit').value = idKit;
  document.getElementById('editarNombreKit').value = rowSel.cells[1].innerText.trim();
  document.getElementById('editarPrecioKit').value = rowSel.cells[2].innerText.replace('$','').trim();
  document.getElementById('editarStockKit').value = rowSel.cells[3].innerText.trim();

  // Cargar productos de este kit vía AJAX
  $('#productosEditarKit').html('<p>Cargando...</p>');
  $.get('getProductosKitEditar.php', { idKit: idKit, t: Date.now() }, function(data) {
      $('#productosEditarKit').html(data);
  });

  // Mostrar modal
  document.getElementById('modalEditarKit').style.display = 'flex';
  document.getElementById('modalEditarKit').setAttribute('aria-hidden', 'false');
});

// Cerrar modal editar
document.getElementById('cerrarModalEditarKit').addEventListener('click', () => {
  document.getElementById('modalEditarKit').style.display = 'none';
  document.getElementById('modalEditarKit').setAttribute('aria-hidden', 'true');
});

// Enviar formulario con productos seleccionados
document.getElementById("formEditarKit").addEventListener("submit", function(e){
  const seleccionados = [];
  document.querySelectorAll("#productosEditarKit tbody tr").forEach(row => {
    const chk = row.querySelector(".chkProd");
    const cantidad = row.querySelector(".cantidadProd").value;
    if(chk && chk.checked){
      seleccionados.push({
        id: chk.value,
        cantidad: cantidad
      });
    }
  });

  if(seleccionados.length === 0){
    alert("Selecciona al menos un producto para el kit.");
    e.preventDefault();
    return;
  }

  document.getElementById("productosSeleccionadosEditar").value = JSON.stringify(seleccionados);
});

</script>

</body>
</html>
<?php
}
?>