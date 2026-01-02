        document.addEventListener('DOMContentLoaded', function () {
          // DataTables
          try { new simpleDatatables.DataTable("#TablaTalleres", {searchable:true,fixedHeight:true,perPage:5}); } catch(e){}
          try { new simpleDatatables.DataTable("#TablaCursos",   {searchable:true,fixedHeight:true,perPage:5}); } catch(e){}

          // Selección visual
          $(document).on('click', '#TablaTalleres tbody tr', function(){
            $('#TablaTalleres tbody tr').removeClass('row-selected'); $(this).addClass('row-selected');
          });
          $(document).on('click', '#TablaCursos tbody tr', function(){
            $('#TablaCursos tbody tr').removeClass('row-selected'); $(this).addClass('row-selected');
          });

          // EDITAR (cabecera)
          document.querySelectorAll('.btn-editar').forEach(btn=>{
            btn.addEventListener('click', ()=>{
              const tipo = btn.dataset.tipo; // 'taller'|'curso'
              const tableId = (tipo==='curso') ? '#TablaCursos' : '#TablaTalleres';
              const rowSel = document.querySelector(`${tableId} tbody tr.row-selected`);
              if (!rowSel) { 
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Selecciona una fila primero."
                });
                return;                
              }
              const id = (rowSel.querySelector('td')||{}).innerText?.trim();
              if (!id) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No se pudo leer el ID."
                });
                return;
              }
              window.location.href = `editar_agenda.php?type=${encodeURIComponent(tipo)}&id=${encodeURIComponent(id)}`;
            });
          });

          // AGREGAR (cabecera) -> abre modal vacío para ese tipo
          document.querySelectorAll('.btn-agregar').forEach(btn=>{
            btn.addEventListener('click', ()=>{
              const tipo = btn.dataset.tipo; // 'taller'|'curso'
              abrirModal(tipo, null); // sin preselección
            });
          });

          // Handler: en vez de abrir el modal de "nueva sesión", abrimos el modal de inscripción
      $(document).on('click', '.btn-agendar-row', function(e){
        e.preventDefault();
        const tipoAgenda = this.dataset.tipo; // 'taller' o 'curso'
        const idRel = this.dataset.idrel || null;
        abrirModalInscribir(tipoAgenda, idRel);
      });

      function abrirModalInscribir(tipoAgenda, idRel){
        // setear campos ocultos para el form
        $('#ins_tipo_agenda').val(tipoAgenda);
        $('#ins_id_rel').val(idRel || '');

        // limpiar selección previa
        $('#ins_id_persona').val('');
        $('#ins_selected_info').text('');
        $('#confirmarInscripcion').prop('disabled', true),
        $('#confirmarInteresada').prop('disabled', true);

        // abrir modal
        $('#modalInscribir').addClass('open');

        // Llamada AJAX a getTipo.php (siempre enviamos tipo=alumna como pediste)
        $.ajax({
          url: 'getTipo.php',
          type: 'POST',
          data: { tipo: 'alumna' },
          success: function(html){
            // injectar tabla retornada
            $('#resultadoPersonas').html(html);

            // inicializar DataTable simple (si usas simple-datatables)
            try {
              new simpleDatatables.DataTable("#personasTabla", { searchable:true, fixedHeight:true, perPage:8 });
            } catch(e){ /* si no está disponible, no se rompe */ }

            // añadir selección de filas (delegado)
            $('#personasTabla tbody').off('click','tr').on('click','tr', function(){
              $('#personasTabla tbody tr').removeClass('row-selected');
              $(this).addClass('row-selected');

              // leer id y nombre de la fila
              const id = $(this).find('td').eq(0).text().trim();
              const nombre = $(this).find('td').eq(1).text().trim();

              // setear hidden input
              $('#ins_id_persona').val(id);
              // mostrar info y habilitar boton confirmar
              $('#ins_selected_info').text('Seleccionada: #' + id + ' — ' + nombre);
              $('#confirmarInscripcion').prop('disabled', false),
              $('#confirmarInteresada').prop('disabled', false);
            });
          },
          error: function(xhr, status, err){
            $('#resultadoPersonas').html('<div style="color:crimson">Error cargando lista de alumnas.</div>');
          }
        });
      }

      // handlers cerrar/cancelar
      $('#cerrarInscribir, #cancelarInscribir').on('click', function(){ $('#modalInscribir').removeClass('open'); });

      // click fuera del modal cierra
      $('#modalInscribir').on('click', function(e){ if (e.target === this) $(this).removeClass('open'); });


          // Modal handlers
          const modal  = document.getElementById('modalAgendar');
          const cerrar = document.getElementById('cerrarModal');
          const cancel = document.getElementById('cancelarModal');
          [cerrar, cancel].forEach(el => el.addEventListener('click', ()=> modal.classList.remove('open')));
          modal.addEventListener('click', (e)=>{ if (e.target===modal) modal.classList.remove('open'); });

          function abrirModal(tipo, idRel){
            document.getElementById('typeField').value = tipo;
            const wrapT = document.getElementById('wrapSelTaller');
            const wrapC = document.getElementById('wrapSelCurso');
            const selT  = document.getElementById('id_taller');
            const selC  = document.getElementById('id_curso');

            if (tipo==='curso'){
              wrapT.style.display='none'; wrapC.style.display='block';
              if (idRel){ selC.value = idRel; } else { selC.value=''; }
            } else {
              wrapT.style.display='block'; wrapC.style.display='none';
              if (idRel){ selT.value = idRel; } else { selT.value=''; }
            }

            // limpia campos
            document.getElementById('fecha').value = '';
            document.getElementById('hora_inicio').value = '';
            document.getElementById('hora_fin').value = '';
            document.getElementById('ubicacion').value = '';
            document.getElementById('variacion').value = '';

            modal.classList.add('open');
          }

          // Validación simple antes de enviar
                document.getElementById('formAgendar').addEventListener('submit', function(e){
                e.preventDefault(); // Cortamos el submit al inicio

                const tipo = document.getElementById('typeField').value;
                const idTaller = document.getElementById('id_taller').value;
                const idCurso = document.getElementById('id_curso').value;
                const hi = document.getElementById('hora_inicio').value;
                const hf = document.getElementById('hora_fin').value;

                // Validación tipo
                if (tipo === 'taller' && !idTaller) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Selecciona un taller.",
                        confirmButtonColor: "#3085d6"
                    });
                    return;
                }

                if (tipo === 'curso' && !idCurso) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Selecciona un curso.",
                        confirmButtonColor: "#3085d6"
                    });
                    return;
                }

                // Validación horas
                if (hi && hf) {
                    const di = new Date('1970-01-01T' + hi + ':00');
                    const df = new Date('1970-01-01T' + hf + ':00');

                    if (df <= di) {
                        Swal.fire({
                            title: "Hora inválida",
                            text: "La hora de fin debe ser mayor a la de inicio.",
                            icon: "warning",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Ok"
                        });
                        return;
                    }
                }

                // Si todo está bien, enviamos el form manualmente
                e.target.submit();
            });

        });

        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".ventas-tabla tbody tr").forEach(row => {
          row.addEventListener("click", function () {
            if (this.classList.contains("selected")) {
              // si ya está seleccionada, la deseleccionamos
              this.classList.remove("selected");
            } else {
              // quitamos selección previa
              document.querySelectorAll(".ventas-tabla tbody tr")
                .forEach(r => r.classList.remove("selected"));
              // seleccionamos la actual
              this.classList.add("selected");
            }
          });
        });
      });


      let tablaInscritas; // fuera, para mantener la referencia

      document.querySelectorAll('.btn-listar').forEach(btn => {
          btn.addEventListener('click', () => {
              const tipo = btn.dataset.tipo;
              const tableId = (tipo === 'curso') ? '#TablaCursos' : '#TablaTalleres';
              const rowSel = document.querySelector(`${tableId} tbody tr.row-selected`);

              if (!rowSel) {
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "Selecciona una fila primero."
                  });
                  return;
              }

              const id = rowSel.querySelector('td').innerText.trim();

              // AJAX
              fetch('getInscritas.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: `id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`
              })
              .then(res => res.text())
              .then(data => {
                  document.getElementById('contenidoInscritas').innerHTML = data;
                  document.getElementById('modalInscritas').style.display = 'flex';

                  // Inicializar tabla de forma segura
                  if (tablaInscritas) {
                      tablaInscritas.destroy(); // destruir instancia previa
                  }
                  tablaInscritas = new simpleDatatables.DataTable("#inscritasTabla", {
                      searchable: true,
                      fixedHeight: true,
                      perPage: 5
                  });
              });
          });
      });


// Esperar a que cargue el DOM
document.addEventListener('DOMContentLoaded', function() {

  // Botón de cerrar
  document.querySelector(".close-inscritas").addEventListener("click", function(){
    document.getElementById("modalInscritas").style.display = 'none';
  });

  // Clic fuera del contenido cierra el modal
  document.getElementById("modalInscritas").addEventListener("click", function(e){
    if (e.target === this) { // si clic afuera de .modal-content-inscritas
      this.style.display = 'none';
    }
  });

});

$(document).ready(function () {
  const modalCalendario = $("#modalCalendario");
  const btnCerrar = $("#cerrarCalendario");

  // Al hacer click en cualquier ícono con la clase btn-calendario
  $(document).on("click", ".btn-calendario", function () {
    modalCalendario.addClass("open");

    // Inicializar FullCalendar si aún no existe
    if (!modalCalendario.data("initialized")) {
      const calendarEl = document.getElementById("calendar");
      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        locale: "es",
        height: 600,
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        events: "eventos.php", // <-- aquí apuntas a tu script PHP que devuelva eventos
        eventDidMount: function(info) {
        info.el.setAttribute("title", info.event.title);
        info.el.style.cursor = "pointer";
        }
      });
      calendar.render();
      modalCalendario.data("initialized", true);
    }
  });

  // Botón de cerrar
  btnCerrar.on("click", function () {
    modalCalendario.removeClass("open");
  });

  // Cerrar al hacer click fuera de la caja
  $(window).on("click", function (e) {
    if ($(e.target).is("#modalCalendario")) {
      modalCalendario.removeClass("open");
    }
  });
});
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formInscribir');
  const botones = form.querySelectorAll('button[type="submit"]');

  botones.forEach(btn => {
    btn.addEventListener('click', function(e) {
      // cambia la acción del formulario dependiendo del botón presionado
      form.action = this.dataset.action;
    });
  });
});
function verDescripcion(descripcion) {
  Swal.fire({
    title: 'Descripción del módulo',
    text: descripcion,
    icon: 'info',
    confirmButtonText: 'Cerrar'
  });
}
  document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
      CLICK GLOBAL (modales)
    =============================== */
    document.addEventListener('click', function (e) {

      /* ===== AGREGAR MÓDULO ===== */
      const btnAgregar = e.target.closest('.btn-agregar-modulo');
      if (btnAgregar) {
        const idCurso = btnAgregar.dataset.idcurso;
        if (!idCurso) return;

        document.getElementById('id_curso_modulo').value = idCurso;
        document.getElementById('formModulo').reset();
        document.getElementById('status_modulo').value = 'activo';

        document.getElementById('modalModulo').classList.add('open');
        return;
      }

      /* ===== VER MÓDULOS ===== */
      const btnVer = e.target.closest('.btn-ver-modulos');
      if (btnVer) {
        const idCurso = btnVer.dataset.idcurso;
        const contenedor = document.getElementById('contenedorModulos');

        contenedor.innerHTML = '<p style="text-align:center;">Cargando módulos...</p>';
        document.getElementById('modalVerModulos').classList.add('open');

        fetch(`get_modulos_curso.php?id_curso=${idCurso}`)
          .then(res => res.json())
          .then(data => {

            if (!data.ok || data.modulos.length === 0) {
              contenedor.innerHTML = '<p>No hay módulos registrados</p>';
              return;
            }

            let html = `
              <table class="display">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Status</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
            `;

            data.modulos.forEach(m => {
              html += `
                <tr>
                  <td>${m.nombre}</td>
                  <td>${m.fecha}</td>
                  <td>${m.hora_inicio.substr(0,5)} - ${m.hora_fin.substr(0,5)}</td>
                  <td>${m.status}</td>
                  <td>
                    <button class="btn-mini btn-info"
                      onclick="verDescripcion(${JSON.stringify(m.descripcion || 'Sin descripción').replace(/"/g,'&quot;')})">
                      Descripción
                    </button>
                  </td>
                </tr>
              `;
            });

            html += '</tbody></table>';
            contenedor.innerHTML = html;

          });

        return;
      }

      /* ===== CERRAR MODALES ===== */
      if (
        e.target.id === 'cerrarModulo' ||
        e.target.id === 'cancelarModulo' ||
        e.target.id === 'modalModulo'
      ) {
        document.getElementById('modalModulo').classList.remove('open');
      }

      if (
        e.target.id === 'cerrarVerModulos' ||
        e.target.id === 'modalVerModulos'
      ) {
        document.getElementById('modalVerModulos').classList.remove('open');
      }

    });


    /* ===============================
      SUBMIT FORM MÓDULO
    =============================== */
    document.getElementById('formModulo').addEventListener('submit', function (e) {
      e.preventDefault();

      const horaInicio = document.getElementById('hora_inicio_modulo').value;
      const horaFin    = document.getElementById('hora_fin_modulo').value;

      if (!horaInicio || !horaFin) {
        Swal.fire('Horario incompleto', 'Selecciona hora inicio y fin', 'warning');
        return;
      }

      const [hiH, hiM] = horaInicio.split(':').map(Number);
      const [hfH, hfM] = horaFin.split(':').map(Number);

      if ((hfH * 60 + hfM) <= (hiH * 60 + hiM)) {
        Swal.fire('Horario inválido', 'La hora fin debe ser mayor', 'error');
        return;
      }

      const formData = new FormData(this);

      fetch('alta_modulo.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.ok) {
          Swal.fire('Éxito', 'Módulo registrado correctamente', 'success');
          document.getElementById('modalModulo').classList.remove('open');
          this.reset();
        } else {
          Swal.fire('Error', data.error || 'No se pudo guardar', 'error');
        }
      })
      .catch(() => {
        Swal.fire('Error', 'Error de conexión con el servidor', 'error');
      });
    });
    
 });