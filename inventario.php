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
        width: 95%;
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
                        <img src="img/eliminar.png" alt="Poner Stock 0" class="icon btn-stock-cero" width="20" data-tipo="producto">
                       <img src="img/agregar.png" alt="Agregar" class="icon btn-agregar-producto" width="20" style="cursor:pointer;">
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
<!-- Modal Agregar Producto -->
<div id="modalAgregarProducto" class="modal" aria-hidden="true" style="display:none;">
  <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalAgregarProductoTitle">
    <button id="cerrarModalProducto" class="btn-cerrar" aria-label="Cerrar">&times;</button>
    
    <h3 id="modalAgregarProductoTitle">Agregar Producto</h3>
    <form id="formAgregarProducto" method="POST" action="guardarProducto.php">
      <div class="form-group">
        <label for="nombreProducto">Nombre del Producto:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" required>
      </div>

      <div class="form-group">
        <label for="precioProducto">Precio Unitario:</label>
        <input type="number" id="precioProducto" name="precioProducto" required min="0" step="0.01">
      </div>

      <div class="form-group">
        <label for="stockProducto">Stock:</label>
        <input type="number" id="stockProducto" name="stockProducto" required min="1">
      </div>

      <button type="submit" class="btn-confirmar">Guardar Producto</button>
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
  document.addEventListener("DOMContentLoaded", function() {
      // -------------------------------
      // Funciones auxiliares
      // -------------------------------
      const getCarrito = () => (window.getCarrito ? window.getCarrito() : []);
      
      const crearFormularioPost = (action, dataObj, name = 'carrito') => {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = action;
          form.style.display = 'none';
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = name;
          input.value = JSON.stringify(dataObj);
          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
      };

      const resaltarFila = (tablaSelector) => {
          $(tablaSelector + ' tbody').on('click', 'tr', function() {
              $(tablaSelector + ' tbody tr').removeClass('row-selected');
              $(this).addClass('row-selected');
          });
      };

      // -------------------------------
      // Resaltar filas
      // -------------------------------
      resaltarFila('#TablaProductos');
      resaltarFila('#TablaKits');

      // -------------------------------
      // Editar producto
      // -------------------------------
      document.querySelectorAll('.btn-editar[data-tipo="producto"]').forEach(btn => {
          btn.addEventListener('click', () => {
              const carrito = getCarrito();
              if (!carrito || carrito.length !== 1) {
                  Swal.fire({ icon: "error", title: "Oops...", text: "Selecciona exactamente un producto para editar." });
                  return;
              }
              if (carrito[0].type !== btn.dataset.tipo) {
                  alert(`Este botón solo funciona para ${btn.dataset.tipo}s.`);
                  return;
              }
              crearFormularioPost('editarProductos.php', carrito);
          });
      });

      // -------------------------------
      // Eliminar producto
      // -------------------------------
      const eliminarProducto = (btn) => {
          const carrito = getCarrito();
          if (!carrito || carrito.length !== 1) {
              Swal.fire({ icon: "error", title: "Oops...", text: "Selecciona exactamente un producto para eliminar." });
              return;
          }
          if (carrito[0].type !== btn.dataset.tipo) {
              Swal.fire({ icon: "error", title: "Oops...", text: `Este botón solo funciona para ${btn.dataset.tipo}s.` });
              return;
          }
          Swal.fire({
              title: `¿Eliminar ${carrito[0].nombre}?`,
              text: "Esta acción no se puede deshacer.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d33",
              cancelButtonColor: "#3085d6",
              confirmButtonText: "Sí, eliminar",
              cancelButtonText: "Cancelar"
          }).then((result) => {
              if (result.isConfirmed) {
                  crearFormularioPost('eliminarProductos.php', carrito);
              }
          });
      };

     document.querySelectorAll('.btn-stock-cero[data-tipo="producto"]').forEach(btn => {
          btn.addEventListener('click', () => ponerStockCero(btn));
      });

      // -------------------------------
      // Agregar producto
      // -------------------------------
      const modalProducto = document.getElementById('modalAgregarProducto');
      document.querySelector('.btn-agregar-producto').addEventListener('click', () => {
          modalProducto.style.display = 'flex';
          modalProducto.setAttribute('aria-hidden', 'false');
      });
      document.getElementById('cerrarModalProducto').addEventListener('click', () => {
          modalProducto.style.display = 'none';
          modalProducto.setAttribute('aria-hidden', 'true');
      });
      window.addEventListener('click', (e) => {
          if (e.target === modalProducto) {
              modalProducto.style.display = 'none';
              modalProducto.setAttribute('aria-hidden', 'true');
          }
      });

      // -------------------------------
      // Agregar kit
      // -------------------------------
      const modalKit = document.getElementById('modalAgregarKit');
      document.querySelector('.btn-agregar-kit').addEventListener('click', () => {
          modalKit.style.display = 'flex';
          modalKit.setAttribute('aria-hidden', 'false');
      });
      document.getElementById('cerrarModalKit').addEventListener('click', () => {
          modalKit.style.display = 'none';
          modalKit.setAttribute('aria-hidden', 'true');
      });
      window.addEventListener('click', (e) => {
          if (e.target === modalKit) {
              modalKit.style.display = 'none';
              modalKit.setAttribute('aria-hidden', 'true');
          }
      });

      // Validar selección de productos al agregar kit
      document.getElementById("formAgregarKit").addEventListener("submit", function(e){
          const seleccionados = [];
          document.querySelectorAll("#productosSeleccionables tbody tr").forEach(row => {
              const chk = row.querySelector(".chkProd");
              const cantidad = row.querySelector(".cantidadProd").value;
              if(chk.checked){
                  seleccionados.push({ id: chk.value, cantidad: cantidad });
              }
          });
          if(seleccionados.length === 0){
              alert("Selecciona al menos un producto para el kit.");
              e.preventDefault();
              return;
          }
          document.getElementById("productosSeleccionados").value = JSON.stringify(seleccionados);
      });

      // -------------------------------
      // Ver productos de kit (AJAX)
      // -------------------------------
      $(document).on('click', '#TablaKits .btn-ver-productos', function () {
          const idKit = $(this).closest('tr').find('td:first').text().trim();
          $('#contenidoKitProductos').html('<p>Cargando...</p>');
          $('#modalKitProductos').fadeIn().attr('aria-hidden', 'false');
          $.get('getKitProductos.php', { idKit: idKit, t: Date.now() }, function(data) {
              $('#contenidoKitProductos').html(data);
          });
      });

      $('#cerrarModalKitProductos').on('click', function() {
          $('#modalKitProductos').fadeOut().attr('aria-hidden', 'true');
      });
      $(window).on('click', function(e) {
          if (e.target === $('#modalKitProductos')[0]) {
              $('#modalKitProductos').fadeOut().attr('aria-hidden', 'true');
          }
      });

      // -------------------------------
      // Editar kit
      // -------------------------------
      document.querySelector('.btn-editar[data-tipo="kit"]').addEventListener('click', () => {
          const rowSel = document.querySelector('#TablaKits tbody tr.row-selected');
          if (!rowSel) {
              Swal.fire({ icon:"error", title:"Oops...", text:"Selecciona un kit primero." });
              return;
          }
          const idKit = rowSel.querySelector('td').innerText.trim();
          document.getElementById('editarIdKit').value = idKit;
          document.getElementById('editarNombreKit').value = rowSel.cells[1].innerText.trim();
          document.getElementById('editarPrecioKit').value = rowSel.cells[2].innerText.replace('$','').trim();
          document.getElementById('editarStockKit').value = rowSel.cells[3].innerText.trim();

          $('#productosEditarKit').html('<p>Cargando...</p>');
          $.get('getProductosKitEditar.php', { idKit: idKit, t: Date.now() }, function(data) {
              $('#productosEditarKit').html(data);
          });

          const modalEditar = document.getElementById('modalEditarKit');
          modalEditar.style.display = 'flex';
          modalEditar.setAttribute('aria-hidden', 'false');
      });

      document.getElementById('cerrarModalEditarKit').addEventListener('click', () => {
          const modalEditar = document.getElementById('modalEditarKit');
          modalEditar.style.display = 'none';
          modalEditar.setAttribute('aria-hidden', 'true');
      });

      // Validar productos al editar kit
      document.getElementById("formEditarKit").addEventListener("submit", function(e){
          const seleccionados = [];
          document.querySelectorAll("#productosEditarKit tbody tr").forEach(row => {
              const chk = row.querySelector(".chkProd");
              const cantidad = row.querySelector(".cantidadProd").value;
              if(chk && chk.checked){
                  seleccionados.push({ id: chk.value, cantidad: cantidad });
              }
          });
          if(seleccionados.length === 0){
              alert("Selecciona al menos un producto para el kit.");
              e.preventDefault();
              return;
          }
          document.getElementById("productosSeleccionadosEditar").value = JSON.stringify(seleccionados);
      });

      // -------------------------------
      // Eliminar kit
      // -------------------------------
      document.querySelectorAll('.btn-eliminar[data-tipo="kit"]').forEach(btn => {
          btn.addEventListener('click', () => {
              const rowSel = document.querySelector('#TablaKits tbody tr.row-selected');
              if (!rowSel) {
                  Swal.fire({ icon: "error", title: "Oops...", text: "Selecciona un kit primero." });
                  return;
              }
              const idKit = rowSel.querySelector('td').innerText.trim();
              const nombreKit = rowSel.cells[1].innerText.trim();
              Swal.fire({
                  title: `¿Eliminar el kit "${nombreKit}"?`,
                  text: "Esta acción no se puede deshacer.",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#d33",
                  cancelButtonColor: "#3085d6",
                  confirmButtonText: "Sí, eliminar",
                  cancelButtonText: "Cancelar"
              }).then((result) => {
                  if (result.isConfirmed) {
                      crearFormularioPost('eliminarKit.php', idKit, 'idKit');
                  }
              });
          });
      });

      // -------------------------------
      // Inicializar DataTables
      // -------------------------------
      const tablaKits = document.querySelector("#TablaKits");
      if (tablaKits && !tablaKits.dataset._datatable) {
          new simpleDatatables.DataTable("#TablaKits", {
              searchable: true,
              fixedHeight: true,
              perPage: 5
          });
          tablaKits.dataset._datatable = "1";
      }
  });

  // -------------------------------
  // Desactivar producto con verificación de kits
  // -------------------------------
const ponerStockCero = (btn) => {
    const carrito = getCarrito();
    if (!carrito || carrito.length !== 1) {
        Swal.fire({ icon: "error", title: "Oops...", text: "Selecciona exactamente un producto." });
        return;
    }
    const producto = carrito[0];
    if (producto.type !== btn.dataset.tipo) {
        Swal.fire({ icon: "error", title: "Oops...", text: `Este botón solo funciona para ${btn.dataset.tipo}s.` });
        return;
    }

    // Obtener los kits donde está este producto
    $.get('kitsDelProducto.php', { idProducto: producto.id, t: Date.now() }, function(respuesta){
        let res = JSON.parse(respuesta);
        if(res.status !== "success"){
            Swal.fire("Error", "No se pudo verificar los kits", "error");
            return;
        }

        // Construir tabla HTML
        let htmlKits = '';
        if(res.kits.length > 0){
            htmlKits = `
                <p>Este producto se encuentra en los siguientes kits:</p>
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border:1px solid #ccc; padding:4px;">#</th>
                            <th style="border:1px solid #ccc; padding:4px;">Nombre del Kit</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${res.kits.map((kit, index) => `
                            <tr>
                                <td style="border:1px solid #ccc; padding:4px; text-align:center;">${index + 1}</td>
                                <td style="border:1px solid #ccc; padding:4px;">${kit}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <br>
            `;
        }

        Swal.fire({
            title: `¿Poner stock de ${producto.nombre} en 0?`,
            html: htmlKits + "Esto marcará el producto como inactivo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, poner en 0",
            cancelButtonText: "Cancelar",
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ponerStockCero.php', { carrito: JSON.stringify(carrito) }, function(response){
                    let res2 = JSON.parse(response);
                    if(res2.status === "success"){
                        Swal.fire("¡Listo!", res2.message, "success").then(()=>{
                            location.reload(); // recarga la tabla para mostrar stock 0
                        });
                    } else {
                        Swal.fire("Error", res2.message, "error");
                    }
                });
            }
        });
    });
};


</script>


</body>
</html>
<?php
}
?>