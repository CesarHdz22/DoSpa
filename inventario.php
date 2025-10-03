
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
    <link rel="stylesheet" href="css/getTipo.css">
        <link rel="stylesheet" href="css/inventario_modal.css">
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
          <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Usuarios</span></a></li>
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
                              <img src="img/vista.png" id="btnVistaProducto" alt="Vista" class="icon" width="20" style="cursor:pointer;">
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
              <form id="formAgregarProducto" method="POST" action="guardarProducto.php" enctype="multipart/form-data">
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

                <div class="form-group">
                  <label for="imagenProducto">Imagen:</label>
                  <input type="file" id="imagenProducto" name="imagenProducto" accept="image/*" required>
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
              <form id="formEditarProducto" method="POST" action="eProductos.php" enctype="multipart/form-data">
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

                <div class="form-group">
                  <label for="editarImagenProducto">Imagen (opcional):</label>
                  <input type="file" id="editarImagenProducto" name="editarImagenProducto" accept="image/*">
                </div>

                <button type="submit" class="btn-confirmar">Guardar Cambios</button>
              </form>
            </div>
          </div>

          <!-- Modal Vista Producto -->
          <div id="modalVistaProducto" class="modal" aria-hidden="true" style="display:none;">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalVistaProductoTitle">
              <button id="cerrarModalVistaProducto" class="btn-cerrar" aria-label="Cerrar">&times;</button>

              <div class="product-card">
                <div class="card-header">
                  <h3 id="vistaNombreProducto">Título del Producto</h3>
                <p>Stock: <span id="vistaStockProducto">20</span></p>
                </div>

                <!-- Precio en círculo -->
                <div class="price-circle">
                  <span id="vistaPrecioProducto">$00</span>
                </div>

                <!-- Imagen del producto -->
                <div class="img-container">
                  <img id="vistaImgProducto" src="" alt="Imagen del producto">
                </div>


              </div>
            </div>
          </div>




      </main>
    </div>
                          <!-- Modal Clientes -->
                      <div class="modal" id="modalClientes">
                        <div class="modal-content">
                          <header>
                            <h3>Nuevo Cliente</h3>
                            <button class="close" data-close="#modalClientes">&times;</button>
                          </header>
                          <form method="post" autocomplete="off" action="usuario_controlador.php">
                            <input type="hidden" name="action" value="add_cliente">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido Materno</label>
                                <input type="text" name="amat">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion">
                              </div>
                            </div>
                            <div class="form-actions">
                              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!-- Modal Alumna -->
                      <div class="modal" id="modalAlumna">
                        <div class="modal-content">
                          <header>
                            <h3>Nueva alumna</h3>
                            <button class="close" data-close="#modalAlumna">&times;</button>
                          </header>
                          <form method="post" autocomplete="off" action="usuario_controlador.php">
                            <input type="hidden" name="action" value="add_alumna">
                            <div class="form-grid">
                              <div class="form-control">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" required>
                              </div>
                              <div class="form-control">
                                <label>Apellido paterno</label>
                                <input type="text" name="apat">
                              </div>
                              <div class="form-control">
                                <label>Apellido materno</label>
                                <input type="text" name="amat">
                              </div>
                              <div class="form-control">
                                <label>Teléfono</label>
                                <input type="text" name="telefono">
                              </div>
                              <div class="form-control">
                                <label>Correo</label>
                                <input type="email" name="correo">
                              </div>
                              <div class="form-control">
                                <label>Dirección</label>
                                <input type="text" name="direccion">
                              </div>
                              <div class="form-control">
                                <label>Descuento aplicado</label>
                                <select name="descuento_aplicado">
                                  <option value="0">No</option>
                                  <option value="1">Sí</option>
                                </select>
                              </div>
                              <div class="form-control">
                                <label>Tipo de descuento</label>
                                <input type="text" name="tipo_descuento">
                              </div>
                            </div>
                            <div class="form-actions">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i></button>
                            </div>
                          </form>
                        </div>
                      </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="librerias/tables.js"></script>
    <script src="librerias/carrito.js"></script>
    <script src="librerias/FunInventario.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
  const openModal = (modal) => {
    if (!modal) return;
    // cerrar otros abiertos
    document.querySelectorAll('.modal.open').forEach(m => {
      m.classList.remove('open');
      m.setAttribute('aria-hidden','true');
    });
    modal.classList.add('open');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  };

  const closeModal = (modal) => {
    if (!modal) return;
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  };

  document.addEventListener('click', (e) => {
    // 1) botones de agregar
    const btnAgregarKit = e.target.closest('.btn-agregar-kit');
    const btnAgregarProducto = e.target.closest('.btn-agregar-producto');

    if (btnAgregarKit) {
      const tipo = btnAgregarKit.getAttribute('data-tipo');
      if (tipo) {
        if (tipo === 'alumna') openModal(document.getElementById('modalAlumna'));
        else if (tipo === 'cliente') openModal(document.getElementById('modalClientes'));
      } else {
        openModal(document.getElementById('modalAgregarKit'));
      }
      return;
    }

    if (btnAgregarProducto) {
      openModal(document.getElementById('modalAgregarProducto'));
      return;
    }

    // 2) cierre por botones (data-close o btn-cerrar / close)
    const botonClose = e.target.closest('[data-close], .btn-cerrar, .close');
    if (botonClose) {
      const selector = botonClose.getAttribute('data-close');
      if (selector) {
        const modal = document.querySelector(selector);
        closeModal(modal);
      } else {
        const modal = botonClose.closest('.modal');
        closeModal(modal);
      }
      return;
    }

    // 3) clic directo sobre el overlay (fondo) -> cerrar
    const clickedModal = e.target.closest('.modal');
    if (clickedModal && e.target === clickedModal) closeModal(clickedModal);
  });

  // 4) ESC para cerrar
  window.addEventListener('keydown', (ev) => {
    if (ev.key === 'Escape') {
      document.querySelectorAll('.modal.open').forEach(m => closeModal(m));
    }
  });

  // ------------------------------
  // 5) Enviar formularios por AJAX (sin romper apertura de modales)
  const enviarFormulario = (form, modal) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      try {
        const res = await fetch('usuario_controlador.php', {
          method: 'POST',
          body: formData
        });
        let data;
        try { data = await res.json(); } catch(err){ data = null; }

        Swal.fire({
          icon: res.ok ? 'success' : 'error',
          title: res.ok ? '¡Listo!' : 'Error',
          text: res.ok ? 'Operación exitosa.' : 'Hubo un error',
          timer: 1500,
          showConfirmButton: false
        });

        if (res.ok) {
          closeModal(modal);
          setTimeout(()=>location.reload(), 1600);
        }
      } catch (err) {
        console.error(err);
        Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
      }
    });
  };

  // Formularios de usuario
  const formularios = [
    {id: 'formAgregarCliente', modal: 'modalClientes'},
    {id: 'formAgregarAlumna', modal: 'modalAlumna'}
  ];

  formularios.forEach(f => {
    const form = document.getElementById(f.id);
    const modal = document.getElementById(f.modal);
    if (form && modal) enviarFormulario(form, modal);
  });

});

</script>

  </body>
</html>
<?php
}
?> 