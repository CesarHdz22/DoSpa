document.addEventListener('DOMContentLoaded', () => {
  const openModal = (id) => document.querySelector(id)?.classList.add('open');
  const closeModal = (id) => document.querySelector(id)?.classList.remove('open');

  // Botones para abrir modales
  document.getElementById('btnNuevaAlumna')?.addEventListener('click', () => openModal('#modalAlumna'));
  document.getElementById('btnNuevaMaestra')?.addEventListener('click', () => openModal('#modalMaestra'));
  document.getElementById('btnNuevoCliente')?.addEventListener('click', () => openModal('#modalClientes'));
  document.getElementById('btnNuevoUsuario')?.addEventListener('click', () => openModal('#modalUsuarios'));
// Toggle Alumnas
document.getElementById('btnToggleAlumnas')?.addEventListener('click', () => {
    const url = new URL(window.location.href);
    if (url.searchParams.get('alumnas') === '0') {
        url.searchParams.set('alumnas', '1'); // Mostrar activos
    } else {
        url.searchParams.set('alumnas', '0'); // Mostrar inactivos
    }
    window.location.href = url.toString();
});

// Toggle Maestras
document.getElementById('btnToggleMaestras')?.addEventListener('click', () => {
    const url = new URL(window.location.href);
    if (url.searchParams.get('maestras') === '0') {
        url.searchParams.set('maestras', '1'); // Mostrar activos
    } else {
        url.searchParams.set('maestras', '0'); // Mostrar inactivos
    }
    window.location.href = url.toString();
});

// Toggle Clientes
document.getElementById('btnToggleClientes')?.addEventListener('click', () => {
    const url = new URL(window.location.href);
    if (url.searchParams.get('clientes') === '0') {
        url.searchParams.set('clientes', '1'); // Mostrar activos
    } else {
        url.searchParams.set('clientes', '0'); // Mostrar inactivos
    }
    window.location.href = url.toString();
});


  // Botones cerrar (X)
  document.querySelectorAll('.modal .close').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-close');
      closeModal(target);
    });
  });

  // Cerrar al hacer clic fuera del contenido
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.remove('open');
    });
  });
});

// Inicializar DataTables
document.addEventListener("DOMContentLoaded", () => {
  new simpleDatatables.DataTable("#tablaAlumnas");
  new simpleDatatables.DataTable("#tablaMaestras");
  new simpleDatatables.DataTable("#tablaClientes");
  new simpleDatatables.DataTable("#tablaUsuarios");
});

// Delegación para botones de edición
document.addEventListener("DOMContentLoaded", () => {
  function delegarEdicion(tbodySelector, btnClass, modalSelector, mapping) {
    const tbody = document.querySelector(tbodySelector);
    if (!tbody) return;

    tbody.addEventListener("click", (e) => {
      const btn = e.target.closest(btnClass);
      if (!btn) return;

      for (const [datasetKey, inputId] of Object.entries(mapping)) {
        const input = document.getElementById(inputId);
        if (input) {
          input.value = btn.dataset[datasetKey] ?? '';
        }
      }

      document.querySelector(modalSelector)?.classList.add("open");
    });
  }

  // Usuarios
  delegarEdicion("#tablaUsuarios tbody", ".btn-edit", "#modalEditarUsuario", {
    id: "edit_Id_Usuario",
    nombre: "edit_Nombre",
    apat: "edit_Apat",
    amat: "edit_Amat",
    correo: "edit_Correo",
    user: "edit_User",
    cargo: "edit_Cargo",
    pass: "edit_Pass"
  });

  // Clientes
  delegarEdicion("#tablaClientes tbody", ".btn-edit-cliente", "#modalEditarCliente", {
    id: "edit_id_cliente",
    nombre: "edit_nombre",
    apat: "edit_apat",
    amat: "edit_amat",
    correo: "edit_correo",
    telefono: "edit_telefono",
    direccion: "edit_direccion"
  });

  // Maestras
  delegarEdicion("#tablaMaestras tbody", ".btn-edit-maestra", "#modalEditarMaestra", {
    id: "edit_id_maestra",
    nombre: "edit_nombre_maestra",
    base: "edit_base_maestra",
    acuerdo: "edit_acuerdo_maestra",
    gastos: "edit_gastos_maestra",
    porcentaje: "edit_porcentaje_maestra"
  });
  // Alumnas
  delegarEdicion("#tablaAlumnas tbody", ".btn-edit-alumna", "#modalEditarAlumna", {
    id: "edit_alumna_id",
    nombre: "edit_alumna_nombre",
    apat: "edit_alumna_apat",
    amat: "edit_alumna_amat",
    telefono: "edit_alumna_telefono",
    correo: "edit_alumna_correo",
    direccion: "edit_alumna_direccion",
    descuento: "edit_alumna_descuento",
    tipo: "edit_alumna_tipo"
  });

});

// Configurar botones de eliminar con SweetAlert
function setupDeleteButtons(tablaSelector, claseBoton, actionName, textoSingular) {
  const tabla = document.querySelector(tablaSelector + " tbody");
  if (!tabla) return;

  tabla.addEventListener("click", (e) => {
    const btn = e.target.closest(claseBoton);
    if (!btn) return;

    const id = btn.dataset.id;

    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("", {
          method: "POST",
          headers: {"Content-Type": "application/x-www-form-urlencoded"},
          body: `action=${actionName}&id=${id}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire('Eliminado!', `El ${textoSingular} ha sido eliminado.`, 'success');
            btn.closest("tr").remove();
          } else {
            Swal.fire('Error', `No se pudo eliminar el ${textoSingular}.`, 'error');
          }
        });
      }
    });
  });
}

// Ejecutar configuración de eliminación
document.addEventListener("DOMContentLoaded", () => {
  setupDeleteButtons("#tablaUsuarios", ".btn-delete", "delete_usuario", "usuario");
  setupDeleteButtons("#tablaClientes", ".btn-delete-cliente", "delete_cliente", "cliente");
  setupDeleteButtons("#tablaMaestras", ".btn-delete-maestra", "delete_maestra", "maestra");
  setupDeleteButtons("#tablaAlumnas", ".btn-delete-alumna", "delete_alumna", "alumna");
});

function setupActivateButtons(tablaSelector, claseBoton, actionName, textoSingular) {
  const tabla = document.querySelector(tablaSelector + " tbody");
  if (!tabla) return;

  tabla.addEventListener("click", (e) => {
    const btn = e.target.closest(claseBoton);
    if (!btn) return;

    const id = btn.dataset.id;

    Swal.fire({
      title: `¿Deseas activar este ${textoSingular}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, activar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirigimos a PHP para activar y recargar la página
        fetch("", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=${actionName}&id_${textoSingular}=${id}`
        })
        .then(() => {
        // Recargar la página manteniendo filtros
        const url = new URL(window.location.href);
        window.location.href = url.toString();
        });
      }
    });
  });
}

// Ejecutar configuración de activación
document.addEventListener("DOMContentLoaded", () => {
  setupActivateButtons("#tablaAlumnas", ".btn-activate-alumna", "activate_alumna", "alumna");
  setupActivateButtons("#tablaMaestras", ".btn-activate-maestra", "activate_maestra", "maestra");
  setupActivateButtons("#tablaClientes", ".btn-activate-cliente", "activate_cliente", "cliente");
});
