document.addEventListener('DOMContentLoaded', () => {
    const selectGra = document.querySelector('select[name="filtroGra_obs"]');
    const inputFecha = document.querySelector('input[name="bus_fecha_obs"]');
    const inputBusqueda = document.querySelector('input[name="bus_obs"]');
    const btnLimpiar = document.getElementById('btnLimpiar_obs');

    function buscarCombinado() {
        const busqueda = inputBusqueda ? inputBusqueda.value.trim() : '';
        const fecha = inputFecha ? inputFecha.value.trim() : '';
        const gravedad = selectGra ? selectGra.value : '';

        if (btnLimpiar) {
            if (busqueda.length > 0 || gravedad !== '' || fecha.length > 0) {
                btnLimpiar.classList.remove('d-none');
            } else {
                btnLimpiar.classList.add('d-none');
            }
        }

        listarObservacion(busqueda, gravedad, fecha); 
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarCombinado);
    }

    if (inputFecha) {
        inputFecha.addEventListener('input', buscarCombinado);
    }

    if (selectGra) {
        selectGra.addEventListener('change', buscarCombinado);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (inputBusqueda) inputBusqueda.value = '';
            if (selectGra) selectGra.value = '';
            if (inputFecha) inputFecha.value = '';
            this.classList.add('d-none');
            listarObservacion('', '', '');
        });
    }
});

let paginaActualObs = 1;
const elementosPorPaginaObs = 5; 

async function listarObservacion(busqueda = '', gravedad = '', fecha = '') {
    try {
        paginaActualObs = 1; 

        let parametros = [];
            
        if (busqueda) {
            parametros.push(`busqueda=${encodeURIComponent(busqueda)}`);
        }

        if (fecha) {
            parametros.push(`fecha=${encodeURIComponent(fecha)}`);
        }
        
        if (gravedad !== '') {
            parametros.push(`gravedad=${encodeURIComponent(gravedad)}`);
        }

        const url = parametros.length > 0 ? `/enf/obtener-observacion?${parametros.join('&')}` : '/enf/obtener-observacion';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const jornadas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualObs - 1) * elementosPorPaginaObs;
            const fin = inicio + elementosPorPaginaObs;
            const itemsVisibles = jornadas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualObs - 1) * elementosPorPaginaObs + 1;
            let paciente = '';
            let enfermero = '';
            let gra = '';
            
            itemsVisibles.forEach(obs => {
                paciente = obs.paciente.persona.nombres+' '+obs.paciente.persona.apellidos+' H.C. '+obs.paciente.hc;
                enfermero = obs.enfermero.persona.nombres+' '+obs.enfermero.persona.apellidos;
                if (obs.gravedad === 0) {
                    gra = '<p class="text-success">Baja</p>';
                } else if (obs.gravedad === 1) {
                    gra = '<p class="text-warning">Media</p>';
                } else if (obs.gravedad === 2) {
                    gra = '<p class="text-danger">Alta</p>';
                }
                
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${obs.fecha_hora}</td>
                        <td>${enfermero}</td>
                        <td>${paciente}</td>
                        <td>${gra}</td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaObservacion').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagJor'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(jornadas.length / elementosPorPaginaObs);

            const maximoBotonesVisibles = 5;
            let paginaInicio = Math.max(1, paginaActualObs - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualObs = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualObs) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualObs = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualObs = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}