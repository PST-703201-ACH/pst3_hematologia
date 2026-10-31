document.addEventListener('DOMContentLoaded', () => {
    const selectStatus = document.querySelector('select[name="busqueda_status"]');
    const inputBusqueda = document.querySelector('input[name="busqueda_pac"]');
    const btnLimpiar = document.getElementById('btnLimpiar_jor');

    function buscarCombinado() {
        const paciente = inputBusqueda ? inputBusqueda.value.trim() : '';
        const status = selectStatus ? selectStatus.value : '';

        if (btnLimpiar) {
            if (paciente.length > 0 || status !== '') {
                btnLimpiar.classList.remove('d-none');
            } else {
                btnLimpiar.classList.add('d-none');
            }
        }

        listarJornada(paciente, status); 
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarCombinado);
    }

    if (selectStatus) {
        selectStatus.addEventListener('change', buscarCombinado);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (inputBusqueda) inputBusqueda.value = '';
            if (selectStatus) selectStatus.value = '';
            this.classList.add('d-none');
            listarJornada('', '');
        });
    }

    listarJornada();
});

let paginaActualJor = 1;
const elementosPorPaginaJor = 5; 

async function listarJornada(paciente = '', status = '') {
    try {
        paginaActualJor = 1;

        let parametros = [];
            
        if (paciente) {
            parametros.push(`busqueda=${encodeURIComponent(paciente)}`);
        }
        
        if (status !== '') {
            parametros.push(`status=${encodeURIComponent(status)}`);
        }

        const url = parametros.length > 0 ? `/enf/obtener-jornada?${parametros.join('&')}` : '/enf/obtener-jornada';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const jornadas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualJor - 1) * elementosPorPaginaJor;
            const fin = inicio + elementosPorPaginaJor;
            const itemsVisibles = jornadas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualJor - 1) * elementosPorPaginaJor + 1;
            let paciente = '';
            let status = '';
            
            itemsVisibles.forEach(jor => {
                paciente = jor.medicina[0].paciente.persona.nombres+' '+jor.medicina[0].paciente.persona.apellidos;
                hc = jor.medicina[0].paciente.hc;

                if (jor.status === 1) {
                    status = '<p class="text-success">Observado</p>';
                }

                if (jor.status === 0) {
                    status = '<p class="text-danger">No observado</p>';
                }
                
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${paciente} / H.C: ${hc}</td>
                        <td>${status}</td>
                        <td><button type="button" onclick="intercambiarVista('seccion-registrar-obs'); precargarDatosTrat(this);" class="btn btn-sm btn-warning" data-id="${jor.tratamiento_id}">
                                <i class="fas fa-notes-medical"></i> Registrar
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaJornada').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagJor'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(jornadas.length / elementosPorPaginaJor);

            const maximoBotonesVisibles = 5;
            let paginaInicio = Math.max(1, paginaActualJor - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualJor = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualJor) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualJor = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualJor = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}