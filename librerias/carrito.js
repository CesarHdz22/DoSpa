(function() {
  // ------------------------------
  // Estado y utilidades globales
  // ------------------------------
  let carrito = [];

  // Helper escape
  function escapeHtml(str) {
    return String(str || '')
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // Exponer lectura del carrito (útil para otras partes)
  window.getCarrito = () => carrito;

  // ------------------------------
  // Inicio: DOM ready
  // ------------------------------
  document.addEventListener('DOMContentLoaded', () => {

    const tipoSelect = document.getElementById('tipo');
const contenedorTabla = document.getElementById('contenedor-tabla');

if (tipoSelect) {
  tipoSelect.addEventListener('change', function() {
    const tipo = this.value;
    contenedorTabla.innerHTML = '<p>Cargando...</p>';

    if (!tipo) {
      contenedorTabla.innerHTML = '';
      return;
    }
    
  });
}

    // Inicializar Simple-DataTables si existe
    if (window.simpleDatatables && typeof simpleDatatables.DataTable === 'function') {
      try {
        const t1 = document.querySelector('#TablaProductos');
        if (t1 && !t1.dataset._datatable) {
          new simpleDatatables.DataTable("#TablaProductos", { searchable: true, fixedHeight: true, perPage: 5 });
          t1.dataset._datatable = '1';
        }
      } catch(e) { console.warn('init tabla productos:', e); }
      try {
        const t2 = document.querySelector('#TablaKits');
        if (t2 && !t2.dataset._datatable) {
          new simpleDatatables.DataTable("#TablaKits", { searchable: true, fixedHeight: true, perPage: 5 });
          t2.dataset._datatable = '1';
        }
      } catch(e) { console.warn('init tabla kits:', e); }
    }

    // Botón Confirmar + modal
    const btnConfirm = document.querySelector('.btn-confirmar');
    const modal = document.getElementById("modalComprador"); // tu modal (puede ser null)

    if (btnConfirm) {
      btnConfirm.addEventListener('click', () => {
        if (carrito.length === 0) {
          alert('No hay productos seleccionados.');
          return;
        }

        const faltantes = carrito
          .filter(p => Number(p.cantidad) > Number(p.stock))
          .map(p => `• ${p.nombre} — pedido: ${p.cantidad}, stock: ${p.stock}`);

        if (faltantes.length > 0) {
          alert(
            'No hay stock suficiente para:\n\n' +
            faltantes.join('\n') +
            '\n\nEdita el/los productos para reponer existencias y vuelve a confirmar.'
          );
          return;
        }

        if (modal) {
          modal.style.display = 'flex';
        } else {
          alert('Modal no encontrado.');
        }
      });
    }

    if (modal) {
      const cerrarBtn = modal.querySelector('.cerrar-modal') || modal.querySelector('#cerrarModal');
      if (cerrarBtn) {
        cerrarBtn.addEventListener('click', () => { modal.style.display = 'none'; });
      }
      window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
      });
    }

    // ------------------------------
    // Delegación de eventos para selección de filas
    // ------------------------------

    // Productos
    document.addEventListener('click', function (ev) {
      const row = ev.target.closest('#TablaProductos tbody tr');
      if (!row) return;
      if (ev.target.closest('.btn-ver-productos, .accion-btn')) return;

      const cells  = row.children;
      const id     = row.getAttribute('data-id') || (cells[0]?.innerText.trim()) || '';
      const nombre = (cells[1]?.innerText.trim()) || '';
      const precioRaw = (cells[2]?.innerText.trim() || '').replace(/[^0-9\.,\-]/g,'') || '0';
      const precio = parseFloat(precioRaw.replace(',', '.')) || 0;
      const stock  = parseInt((cells[3]?.innerText.trim() || '').replace(/\D/g,'')) || 0;

      agregarAlCarrito({ id, nombre, precio, stock, type: 'producto' });
    });

    // Kits
    document.addEventListener('click', function (ev) {
      const row = ev.target.closest('#TablaKits tbody tr');
      if (!row) return;
      if (ev.target.closest('.btn-ver-productos, .accion-btn')) return;

      const cells  = row.children;
      const id     = row.getAttribute('data-id') || (cells[0]?.innerText.trim()) || '';
      const nombre = (cells[1]?.innerText.trim()) || '';
      const precioRaw = (cells[2]?.innerText.trim() || '').replace(/[^0-9\.,\-]/g,'') || '0';
      const precio = parseFloat(precioRaw.replace(',', '.')) || 0;
      const stock  = parseInt((cells[3]?.innerText.trim() || '').replace(/\D/g,'')) || 0;

      agregarAlCarrito({ id, nombre, precio, stock, type: 'kit' });
    });

    // ------------------------------
    // Delegación global para botones de acción
    // ------------------------------
    document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.accion-btn');
      if (!btn) return;

      e.stopPropagation();
      e.preventDefault();

      const type = btn.getAttribute('data-type') || btn.closest('tr')?.getAttribute('data-type') || '';
      const id = btn.getAttribute('data-id') || btn.closest('tr')?.getAttribute('data-id') || '';

      if (btn.classList.contains('btn-edit')) {
        window.location.href = `editar.php?type=${encodeURIComponent(type)}&id=${encodeURIComponent(id)}`;
        return;
      }

      if (btn.classList.contains('btn-delete')) {
        if (!confirm('¿Eliminar este registro? Esta acción no se puede deshacer.')) return;
        fetch('eliminar_item.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ type: type, id: id })
        })
        .then(r => r.json())
        .then(json => {
          if (json.success) {
            alert('Registro eliminado.');
            const row = btn.closest('tr');
            if (row) row.remove();
            const key = generarKey(type, id);
            quitarDelCarrito(key);
          } else {
            alert('No se pudo eliminar: ' + (json.message || 'error'));
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error en la solicitud de eliminación.');
        });
        return;
      }

      if (btn.classList.contains('btn-add')) {
        const nombre = btn.getAttribute('data-nombre') || '';
        const precio = parseFloat(btn.getAttribute('data-precio') || '0') || 0;
        const stock = parseInt(btn.getAttribute('data-stock') || '0') || 0;
        agregarAlCarrito({ id, nombre, precio, stock, type });
        return;
      }
    }, true);

    // Vaciar carrito
    const vacBtn = document.getElementById('vaciarCarritoBtn');
    if (vacBtn) {
      vacBtn.addEventListener('click', () => {
        if (carrito.length === 0) return;

        Swal.fire({
          title: '¿Vaciar carrito?',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Sí, vaciar',
          denyButtonText: `No vaciar`
        }).then((result) => {
          if (result.isConfirmed) {
            vaciarCarrito();
            Swal.fire('¡Carrito vaciado!', '', 'success');
          } else if (result.isDenied) {
            Swal.fire('No se vació el carrito', '', 'info');
          }
        });
      });
    }
  }); // DOMContentLoaded end

  // ------------------------------
  // Funciones del carrito
  // ------------------------------
  function generarKey(type, id) { return `${type}-${id}`; }

  function agregarAlCarrito(item) {
    if (!item || !item.id) return;
    const key = generarKey(item.type, item.id);
    const existente = carrito.find(p => p.key === key);

    if (existente) {
      existente.cantidad++;
    } else {
      carrito.push({
        key: key,
        type: item.type,
        id: item.id,
        nombre: item.nombre || '',
        precio: Number(item.precio) || 0,
        stock: Number(item.stock) || 0,
        cantidad: 1,
      });
    }
    actualizarConfirmBtn();
    renderCarritoResumen();
  }

  function quitarDelCarrito(key) {
    if (!key) return;
    carrito = carrito.filter(p => p.key !== key);
    actualizarConfirmBtn();
    renderCarritoResumen();
  }

  function vaciarCarrito() {
    carrito = [];
    actualizarConfirmBtn();
    renderCarritoResumen();
  }

  function actualizarConfirmBtn() {
    const btn = document.querySelector('.btn-confirmar');
    if (!btn) return;
    const totalItems = carrito.reduce((s,p) => s + p.cantidad, 0);
    btn.textContent = totalItems > 0 ? `Confirmar Compra (${totalItems})` : 'Confirmar Compra';
    btn.disabled = totalItems === 0;

    const vacBtn = document.getElementById('vaciarCarritoBtn');
    if (vacBtn) vacBtn.disabled = totalItems === 0;
  }

  function renderCarritoResumen(expandir = false) {
    const cont = document.getElementById('carritoResumen');
    const detalle = document.getElementById('detalleCarrito');
    if (!cont || !detalle) {
      console.log('Carrito (sin contenedor):', carrito);
      return;
    }
    if (carrito.length === 0) {
      cont.style.display = 'none';
      detalle.innerHTML = '';
      return;
    }
    cont.style.display = 'block';
    let html = '<table style="width:100%; border-collapse:collapse;">';
    html += '<tr><th style="text-align:left; padding:6px;">Producto/Kit</th><th style="padding:6px;">Cant</th><th style="padding:6px;">Precio</th><th style="padding:6px;">Subtotal</th><th style="padding:6px;">Acción</th></tr>';
    carrito.forEach(p => {
      html += `<tr>
        <td style="padding:6px;">${escapeHtml(p.nombre)} <small style="color:#666">(${escapeHtml(p.type)})</small></td>
        <td style="padding:6px; text-align:center;">${p.cantidad}</td>
        <td style="padding:6px; text-align:center;">$${Number(p.precio).toFixed(2)}</td>
        <td style="padding:6px; text-align:center;">$${(p.precio * p.cantidad).toFixed(2)}</td>
        <td style="padding:6px; text-align:center;"><button onclick="quitarDelCarrito('${p.key}')" style="padding:6px 8px; border-radius:6px; cursor:pointer;">Eliminar</button></td>
      </tr>`;
    });
    const total = carrito.reduce((s,p) => s + p.precio*p.cantidad, 0);
    html += `<tr><td colspan="3" style="padding:8px; text-align:right;"><strong>Total</strong></td><td style="padding:8px; text-align:center;"><strong>$${total.toFixed(2)}</strong></td><td></td></tr>`;
    html += '</table>';
    detalle.innerHTML = html;
    if (expandir) cont.scrollIntoView({ behavior: 'smooth' });
  }

  // Exponer métodos globales
  window.agregarAlCarrito = agregarAlCarrito;
  window.quitarDelCarrito = quitarDelCarrito;
  window.vaciarCarrito = vaciarCarrito;
  window.renderCarritoResumen = renderCarritoResumen;

})();
// ------------------------------
// Función global para confirmar compra
// ------------------------------
function confirmarCompra(idComprador) {
  const tipoSelect = document.getElementById("tipo");
  const tipo = tipoSelect ? tipoSelect.value.trim().toLowerCase() : '';
  
  if(!tipo){
    Swal.fire({
      title: "Atención",
      text: "Selecciona un tipo de comprador",
      icon: "warning",
      confirmButtonText: "Entendido"
    });
    return;
  }

  console.log("Tipo que se enviará:", tipo, "idComprador:", idComprador, "carrito:", window.getCarrito());

  $.post('confirmar_compra.php', {
    idComprador: idComprador,
    carrito: JSON.stringify(window.getCarrito()),
    tipoCliente: tipo  // coincide con el POST real
  }, function(resp){
    Swal.fire({
      title: "Compra realizada",
      text: resp,
      icon: "success",
      confirmButtonText: "Aceptar"
    }).then(() => {
      location.replace('ventas.php');
    });
  });
}

window.confirmarCompra = confirmarCompra;
