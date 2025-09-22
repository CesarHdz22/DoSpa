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

    // Abrir modal: ahora valida stock antes de abrir
    if (btnConfirm) {
      btnConfirm.addEventListener('click', () => {
        if (carrito.length === 0) {
          alert('No hay productos seleccionados.');
          return;
        }

        // Buscar faltantes (cantidad > stock)
        const faltantes = carrito
          .filter(p => Number(p.cantidad) > Number(p.stock))
          .map(p => `• ${p.nombre} — pedido: ${p.cantidad}, stock: ${p.stock}`);

        if (faltantes.length > 0) {
          alert(
            'No hay stock suficiente para:\n\n' +
            faltantes.join('\n') +
            '\n\nEdita el/los productos para reponer existencias y vuelve a confirmar.'
          );
          return; // no abre el modal si hay faltantes
        }

        // OK: abrir modal
        if (modal) {
          modal.style.display = 'flex';
        } else {
          alert('Modal no encontrado.');
        }
      });
    }

    // Cerrar modal
    if (modal) {
      const cerrarBtn = modal.querySelector('.cerrar-modal') || modal.querySelector('#cerrarModal');
      if (cerrarBtn) {
        cerrarBtn.addEventListener('click', () => { modal.style.display = 'none'; });
      }
      window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
      });
    }

    // Inicializar selección/click por filas (Productos/Kits)
    initRowSelection('#TablaProductos', 'producto');
    initRowSelection('#TablaKits', 'kit');

    // Delegación global para botones de acción dentro del body (se mantiene tu patrón)
    document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.accion-btn');
      if (!btn) return;

      e.stopPropagation();
      e.preventDefault();

      const type = btn.getAttribute('data-type') || btn.closest('tr')?.getAttribute('data-type') || '';
      const id = btn.getAttribute('data-id') || btn.closest('tr')?.getAttribute('data-id') || '';

      // Editar -> redirección simple
      if (btn.classList.contains('btn-edit')) {
        window.location.href = `editar.php?type=${encodeURIComponent(type)}&id=${encodeURIComponent(id)}`;
        return;
      }

      // Eliminar -> petición AJAX
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

      // Agregar -> usa data-attributes del botón
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
        if (confirm('¿Vaciar carrito?')) vaciarCarrito();
      });
    }
  }); // DOMContentLoaded end

  // ------------------------------
  // Funciones: selección filas
  // ------------------------------
  function initRowSelection(selector, type) {
    const tabla = document.querySelector(selector);
    if (!tabla) return;
    const filas = tabla.querySelectorAll('tbody tr');
    filas.forEach(row => {
      if (!row.hasAttribute('data-type') && type) row.setAttribute('data-type', type);
      row.style.cursor = 'pointer';
      row.addEventListener('click', function(ev) {
        if (ev.target.closest('.accion-btn')) return; // botón acción -> no seleccionar
        const cells  = row.children;
        const id     = row.getAttribute('data-id') || (cells[0]?.innerText.trim()) || '';
        const nombre = (cells[1]?.innerText.trim()) || '';
        const precioRaw = (cells[2]?.innerText.trim() || '').replace(/[^0-9\.,\-]/g,'') || '0';
        const precio = parseFloat(precioRaw.replace(',', '.')) || 0;
        const stock  = parseInt((cells[3]?.innerText.trim() || '').replace(/\D/g,'')) || 0;
        const tipo   = row.getAttribute('data-type') || type || 'producto';

        // ✅ Ya NO bloqueamos por stock 0: se puede agregar al carrito
        agregarAlCarrito({ id, nombre, precio, stock, type: tipo });
      });
    });
  }

  // ------------------------------
  // Funciones del carrito
  // ------------------------------
  function generarKey(type, id) { return `${type}-${id}`; }

  function agregarAlCarrito(item) {
    if (!item || !item.id) return;
    const key = generarKey(item.type, item.id);
    const existente = carrito.find(p => p.key === key);

    // ✅ Permitir agregar sin límite respecto al stock (validamos al confirmar)
    if (existente) {
      existente.cantidad++;
    } else {
      carrito.push({
        key: key,
        type: item.type,
        id: item.id,
        nombre: item.nombre || '',
        precio: Number(item.precio) || 0,
        stock: Number(item.stock) || 0, // lo guardamos para validar al confirmar
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

  // Exponer métodos globales para uso fuera del IIFE
  window.agregarAlCarrito = agregarAlCarrito;
  window.quitarDelCarrito = quitarDelCarrito;
  window.vaciarCarrito = vaciarCarrito;
  window.renderCarritoResumen = renderCarritoResumen;

})();
