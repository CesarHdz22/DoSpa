
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
            // Modal Agregar Kit
            // -------------------------------
            const modalKit = document.getElementById('modalAgregarKit');
            const btnAbrirKit = document.querySelector('.btn-agregar-kit');
            const btnCerrarKit = document.getElementById('cerrarModalKit');

            // Inicializa la DataTable del modal
            const tablaProductosSeleccionables = new simpleDatatables.DataTable("#productosSeleccionables");

            btnAbrirKit.addEventListener('click', () => {
                modalKit.style.display = 'flex';
                modalKit.setAttribute('aria-hidden', 'false');

                // 🔥 Reajustar columnas cuando el modal aparece
                setTimeout(() => {
                    tablaProductosSeleccionables.refresh();
                }, 200);
            });

            btnCerrarKit.addEventListener('click', () => {
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
const modalEditarKit = document.getElementById('modalEditarKit');
const btnEditarKit = document.querySelector('.btn-editar[data-tipo="kit"]');
const btnCerrarEditarKit = document.getElementById('cerrarModalEditarKit');
let tablaProductosEditarKit = null; // instancia datatable

btnEditarKit.addEventListener('click', () => {
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

    // carga productos del kit por AJAX
    $('#productosEditarKit').html('<p>Cargando...</p>');
    $.get('getProductosKitEditar.php', { idKit: idKit, t: Date.now() }, function(data) {
        $('#productosEditarKit').html(data);

        // inicializa DataTable si aún no existe
        if(tablaProductosEditarKit){
            tablaProductosEditarKit.destroy(); // destruye la anterior
        }

        if(document.querySelector("#tablaEditarKitProductos")){
            tablaProductosEditarKit = new simpleDatatables.DataTable("#tablaEditarKitProductos");
        }
    });

    modalEditarKit.style.display = 'flex';
    modalEditarKit.setAttribute('aria-hidden', 'false');

    // refrescar después de mostrar
    setTimeout(() => {
        if(tablaProductosEditarKit){
            tablaProductosEditarKit.refresh();
        }
    }, 200);
});

btnCerrarEditarKit.addEventListener('click', () => {
    modalEditarKit.style.display = 'none';
    modalEditarKit.setAttribute('aria-hidden', 'true');
});

// Validar productos al editar kit
document.getElementById("formEditarKit").addEventListener("submit", function(e){
    const seleccionados = [];
    document.querySelectorAll("#productosEditarKit tbody tr").forEach(row => {
        const chk = row.querySelector(".chkProd");
        const cantidad = row.querySelector(".cantidadProd")?.value || 0;
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

        // -------------------------------
        // Editar Producto
        // -------------------------------
        document.querySelectorAll('.btn-editar[data-tipo="producto"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const rowSel = document.querySelector('#TablaProductos tbody tr.row-selected');
                if(!rowSel){
                    Swal.fire({ icon:"error", title:"Oops...", text:"Selecciona un producto primero." });
                    return;
                }

                // Llenar formulario con datos de la fila
                document.getElementById('editarIdProducto').value = rowSel.cells[0].innerText.trim();
                document.getElementById('editarNombreProducto').value = rowSel.cells[1].innerText.trim();
                document.getElementById('editarPrecioProducto').value = rowSel.cells[2].innerText.replace('$','').trim();
                document.getElementById('editarStockProducto').value = rowSel.cells[3].innerText.trim();

                // Abrir modal
                const modal = document.getElementById('modalEditarProducto');
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        document.getElementById('cerrarModalEditarProducto').addEventListener('click', () => {
            const modal = document.getElementById('modalEditarProducto');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        });

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('modalEditarProducto');
            if(e.target === modal){
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }
        });

        // función que aplica la clase leyendo el flag dentro de la celda (span.kit-flag)
        function aplicarColorKits() {
          document.querySelectorAll('#TablaKits tbody tr').forEach(tr => {
            const nombreCell = tr.querySelector('td.nombre-kit') || tr.querySelector('td:nth-child(2)');
            if (!nombreCell) return;
            const flagSpan = nombreCell.querySelector('.kit-flag');
            const tiene = flagSpan && flagSpan.textContent.trim() === '1';
            if (tiene) {
              nombreCell.classList.add('kit-con-inactivo');
            } else {
              nombreCell.classList.remove('kit-con-inactivo');
            }
          });
        }

        // Llama después de inicializar DataTable (reemplaza tu bloque de inicialización o añade esto justo después)
        const tablaKits = document.querySelector("#TablaKits");
        if (tablaKits && !tablaKits.dataset._datatable) {
            new simpleDatatables.DataTable("#TablaKits", {
                searchable: true,
                fixedHeight: true,
                perPage: 5
            });
            tablaKits.dataset._datatable = "1";
        }

        // reaplicar ahora y con pequeños delays para asegurar que el datatable terminó
        aplicarColorKits();
        setTimeout(aplicarColorKits, 120);
        setTimeout(aplicarColorKits, 400);

        // y además usar un MutationObserver sobre la sección que contiene la tabla
        const kitsSection = document.querySelector('.kits');
        if (kitsSection) {
          const mo = new MutationObserver((mutations) => {
            // esperar un tick para que el datatable termine cambios
            setTimeout(aplicarColorKits, 40);
          });
          mo.observe(kitsSection, { childList: true, subtree: true });
        }

    
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
        document.addEventListener("DOMContentLoaded", function() {
          // Ya tienes inicializadas otras tablas, falta esta:
      if ($("#productosSeleccionables").length) {
              new simpleDatatables.DataTable("#productosSeleccionables", {
                  searchable: true,
                  fixedHeight: true,
                  perPage: 5,
                  labels: {
                      placeholder: "Buscar...",
                      perPage: "{select} registros por página",
                      noRows: "No hay productos disponibles",
                      info: "Mostrando {start} a {end} de {rows} productos"
                  }
              });
          }
      });
