
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
    <link rel="stylesheet" href="css/inventario_modal.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/inventario.css">
    <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
   
    

    <style>
.sin-stock {
    color: #943154;
    font-weight: bold;
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
                                  $clase = ($mostrar['Stock'] == 0) ? 'sin-stock' : '';
                          ?>
                              <tr>
                                  <td><?php echo $mostrar['id_producto'] ?></td>
                                  <td class="<?php echo $clase; ?>" <?php echo ($mostrar['Stock'] == 0) ? 'title="No hay stock disponible"' : ''; ?>>
                                      <?php echo $mostrar['nombre'] ?>
                                  </td>
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
                                // Trae todos los kits y marca si tienen al menos 1 producto con Stock = 0
                                $sql = "
                                  SELECT k.*,
                                    CASE
                                      WHEN EXISTS (
                                        SELECT 1
                                        FROM productos_kits pk
                                        JOIN productos p ON p.id_producto = pk.id_producto
                                        WHERE pk.id_kit = k.id_kit AND p.Stock = 0
                                      ) THEN 1 ELSE 0
                                    END AS tiene_inactivos
                                  FROM kits_productos k
                                ";
                                $r = mysqli_query($conexion, $sql);
                                while($mostrar = mysqli_fetch_assoc($r)) {
                                    $flag = ($mostrar['tiene_inactivos'] == 1) ? '1' : '0';
                            ?>
                              <tr>
                                  <td><?php echo htmlspecialchars($mostrar['id_kit']); ?></td>
                                  <td class="nombre-kit">
                                    <?php echo htmlspecialchars($mostrar['nombre']); ?>
                                    <!-- flag oculto que perdurará aunque el datatable re-renderice -->
                                    <span class="kit-flag" style="display:none;"><?php echo $flag; ?></span>
                                  </td>
                                  <td><?php echo "$".htmlspecialchars($mostrar['precio_unitario']); ?></td>
                                  <td><?php echo htmlspecialchars($mostrar['Stock']); ?></td>
                                  <td>
                                    <button class="btn-ver-productos" data-id="<?php echo htmlspecialchars($mostrar['id_kit']); ?>">Ver productos</button>
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
                      <table id="productosSeleccionables" class="display">
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
                            $sqlProd = "SELECT * FROM productos WHERE Stock > 0";
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

          <!-- Modal Editar Producto -->
          <div id="modalEditarProducto" class="modal" aria-hidden="true" style="display:none;">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalEditarProductoTitle">
              <button id="cerrarModalEditarProducto" class="btn-cerrar" aria-label="Cerrar">&times;</button>
              
              <h3 id="modalEditarProductoTitle">Editar Producto</h3>
              <form id="formEditarProducto" method="POST" action="eProductos.php">
                <input type="hidden" name="id" id="editarIdProducto">

                <div class="form-group">
                  <label for="editarNombreProducto">Nombre:</label>
                  <input type="text" id="editarNombreProducto" name="nombre" required>
                </div>

                <div class="form-group">
                  <label for="editarPrecioProducto">Precio Unitario:</label>
                  <input type="number" id="editarPrecioProducto" name="pu" required min="0" step="0.01">
                </div>

                <div class="form-group">
                  <label for="editarStockProducto">Stock:</label>
                  <input type="number" id="editarStockProducto" name="stock" required min="0">
                </div>

                <button type="submit" class="btn-confirmar">Guardar Cambios</button>
              </form>
            </div>
          </div>


      </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" ></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="librerias/tables.js"></script>
    <script src="librerias/carrito.js"></script>
    <script src="librerias/FunInventario.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const tablaProductos = document.querySelector("#TablaProductos");
    const dataTable = new simpleDatatables.DataTable(tablaProductos);

    function aplicarEstilosStock() {
        // Recorre todas las filas visibles del tbody
        const filas = tablaProductos.querySelectorAll("tbody tr");
        filas.forEach(row => {
            const stock = parseInt(row.cells[3].innerText);
            if (stock === 0) {
                const nombreCell = row.cells[1];
                nombreCell.classList.add("sin-stock");
                nombreCell.title = "No hay stock disponible";
            }
        });
    }

    aplicarEstilosStock();

    // Reaplicar después de búsqueda, ordenamiento o cambio de página
    dataTable.on("datatable.search", aplicarEstilosStock);
    dataTable.on("datatable.sort", aplicarEstilosStock);
    dataTable.on("datatable.page", aplicarEstilosStock);
});

</script>
    
  </body>
</html>
<?php
}
?> 