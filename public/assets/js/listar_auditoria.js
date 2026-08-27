document.addEventListener('DOMContentLoaded', () => {
    const selectMod = document.querySelector('select[name="filtroMod_aud"]');
    const selectAcc = document.querySelector('select[name="filtroAcc_aud"]');
    const btnLimpiar = document.getElementById('btnLimpiar_aud');
    const inputBusqueda = document.querySelector('input[name="busqueda_aud"]');
    const inputFecha = document.querySelector('input[name="busqueda_fecha"]');

    function buscarCombinado() {
        const texto = inputBusqueda ? inputBusqueda.value.trim() : '';
        const mod = selectMod ? selectMod.value : '';
        const acc = selectAcc ? selectAcc.value : '';
        const fecha = inputFecha ? inputFecha.value.trim() : '';

        if (btnLimpiar) {
            if (texto.length > 0 || mod !== '' || acc !== '' || fecha.length > 0) {
                btnLimpiar.classList.remove('d-none');
            } else {
                btnLimpiar.classList.add('d-none');
            }
        }

        listarAuditoria(texto, mod, acc, fecha); 
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarCombinado);
    }

    if (selectMod) {
        selectMod.addEventListener('change', buscarCombinado);
    }

    if (selectAcc) {
        selectAcc.addEventListener('change', buscarCombinado);
    }

    if (inputFecha) {
        inputFecha.addEventListener('input', buscarCombinado);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (inputBusqueda) inputBusqueda.value = '';
            if (selectMod) selectMod.value = '';
            if (selectAcc) selectAcc.value = '';
            if (inputFecha) inputFecha.value = '';
            this.classList.add('d-none');
            listarAuditoria('', '', '', '');
        });
    }
});

let paginaActualAud = 1;
const elementosPorPaginaAud = 5; 

async function listarAuditoria(termino = '', mod = '', acc = '', fecha = '') {
    try {
        paginaActualAud = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (mod !== '') {
            parametros.push(`mod=${encodeURIComponent(mod)}`);
        }

        if (acc !== '') {
            parametros.push(`acc=${encodeURIComponent(acc)}`);
        }

        if (fecha) {
            parametros.push(`fecha=${encodeURIComponent(fecha)}`);
        }

        const url = parametros.length > 0 ? `/admin/obtener-auditoria?${parametros.join('&')}` : '/admin/obtener-auditoria';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const auditorias = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualAud - 1) * elementosPorPaginaAud;
            const fin = inicio + elementosPorPaginaAud;
            const itemsVisibles = auditorias.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualAud - 1) * elementosPorPaginaAud + 1;
            let nombre = '';
            let modulo = '';
            let accion = '';

            itemsVisibles.forEach(aud => {
                nombre = aud.usuario.persona.nombres+' '+aud.usuario.persona.apellidos;
                modulo = `<h4 class="badge badge-light">${aud.modulo}</h4>`;
                

                if (aud.accion == 'Registro') {
                    accion = `<h4 class="badge badge-success">${aud.accion}</h4>`;
                }
                else if (aud.accion == 'Listado') {
                    accion = `<h4 class="badge badge-info">${aud.accion}</h4>`;
                }
                else if (aud.accion == 'Consulta') {
                    accion = `<h4 class="badge badge-secondary">${aud.accion}</h4>`;
                }
                else if (aud.accion == 'Actualizacion') {
                    accion = `<h4 class="badge badge-primary">${aud.accion}</h4>`;
                }
                else if (aud.accion == 'Habilitacion/Inhabilitacion') {
                    accion = `<h4 class="badge badge-danger">${aud.accion}</h4>`;
                }
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${aud.fecha_hora}</td>
                        <td>${nombre}</td>
                        <td>${modulo}</td>
                        <td>${accion}</td>
                        <td>${aud.descripcion}</td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaAuditoria').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagAud'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(auditorias.length / elementosPorPaginaAud);

            const maximoBotonesVisibles = 5; // Número máximo de botones numéricos a mostrar
            let paginaInicio = Math.max(1, paginaActualAud - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualAud = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualAud) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualAud = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualAud = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}