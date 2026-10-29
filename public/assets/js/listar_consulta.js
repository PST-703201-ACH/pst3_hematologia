document.addEventListener('DOMContentLoaded', () => {
    const limpiarConP = document.getElementById('btnLimpiar_conp');
    const busquedaConP = document.querySelector('input[name="busqueda_conp"]');
    const fechaConP = document.querySelector('input[name="busqueda_fechaP"]');

    function buscarCombinadoP() {
        const texto = busquedaConP ? busquedaConP.value.trim() : '';
        const fecha = fechaConP ? fechaConP.value.trim() : '';

        if (limpiarConP) {
            if (texto.length > 0 || fecha.length > 0) {
                limpiarConP.classList.remove('d-none');
            } else {
                limpiarConP.classList.add('d-none');
            }
        }

        listarConsultaP(texto, fecha); 
    }

    if (busquedaConP) {
        busquedaConP.addEventListener('input', buscarCombinadoP);
    }

    if (fechaConP) {
        fechaConP.addEventListener('input', buscarCombinadoP);
    }

    if (limpiarConP) {
        limpiarConP.addEventListener('click', function() {
            if (busquedaConP) busquedaConP.value = '';
            if (fechaConP) fechaConP.value = '';
            this.classList.add('d-none');
            listarConsultaP('', '');
        });
    }

    const limpiarConR = document.getElementById('btnLimpiar_conr');
    const busquedaConR = document.querySelector('input[name="busqueda_conr"]');
    const fechaConR = document.querySelector('input[name="busqueda_fechaR"]');

    function buscarCombinadoR() {
        const texto = busquedaConR ? busquedaConR.value.trim() : '';
        const fecha = fechaConR ? fechaConR.value.trim() : '';

        if (limpiarConR) {
            if (texto.length > 0 || fecha.length > 0) {
                limpiarConR.classList.remove('d-none');
            } else {
                limpiarConR.classList.add('d-none');
            }
        }

        listarConsultaR(texto, fecha); 
    }

    if (busquedaConR) {
        busquedaConR.addEventListener('input', buscarCombinadoR);
    }

    if (fechaConR) {
        fechaConR.addEventListener('input', buscarCombinadoR);
    }

    if (limpiarConR) {
        limpiarConR.addEventListener('click', function() {
            if (busquedaConR) busquedaConR.value = '';
            if (fechaConR) fechaConR.value = '';
            this.classList.add('d-none');
            listarConsultaR('', '');
        });
    }

});

let paginaActualConP = 1;
const elementosPorPaginaConP = 5; 

async function listarConsultaP(termino = '', fecha = '') {
    try {
        paginaActualConP = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (fecha) {
            parametros.push(`fecha=${encodeURIComponent(fecha)}`);
        }

        const url = parametros.length > 0 ? `/medico/obtener-consultasP?${parametros.join('&')}` : '/medico/obtener-consultasP';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const consultas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualConP - 1) * elementosPorPaginaConP;
            const fin = inicio + elementosPorPaginaConP;
            const itemsVisibles = consultas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualConP - 1) * elementosPorPaginaConP + 1;
            let paciente = '';
            let hc = '';
            let enfermedad = '';
            let tipo = '';

            itemsVisibles.forEach(con => {
                const persona = con.paciente?.persona;
                const cita = con.cita;
                paciente = persona
                    ? `${persona.nombres} ${persona.apellidos}`
                    : `${cita?.nombres_paciente ?? 'Paciente'} ${cita?.apellidos_paciente ?? 'no disponible'}`;
                hc = con.paciente?.hc ?? cita?.numero_hc ?? 'Sin HC';
                enfermedad = con.enfermedad?.descripcion;

                if (enfermedad === null || enfermedad === undefined) {
                    enfermedad = 'No definida';
                }

                if (con.tipo == 1) {
                    tipo = 'Primera consulta';
                } else if (con.tipo == 2){
                    tipo = 'Consulta de control';
                }

                const citaId = con.cita?.cita_id ?? con.consulta_id ?? '';

                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${paciente}</td>
                        <td>${hc}</td>
                        <td>${tipo}</td>
                        <td>${con.fecha_hora}</td>
                        <td>${enfermedad}</td>
                        <td class="text-center">
                            <button type="button" onclick="intercambiarVista('seccion-consulta-registrar'); precargarDatosCon(this);" class="btn btn-sm btn-warning" data-id="${citaId}">
                                <i class="fas fa-notes-medical"></i> Atender
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaConsultasP').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagConP'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(consultas.length / elementosPorPaginaConP);

            const maximoBotonesVisibles = 5; // Número máximo de botones numéricos a mostrar
            let paginaInicio = Math.max(1, paginaActualConP - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualConP = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualConP) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualConP = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualConP = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}



let paginaActualConR = 1;
const elementosPorPaginaConR = 5; 

async function listarConsultaR(termino = '', fecha = '') {
    try {
        paginaActualConR = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (fecha) {
            parametros.push(`fecha=${encodeURIComponent(fecha)}`);
        }

        const url = parametros.length > 0 ? `/medico/obtener-consultasR?${parametros.join('&')}` : '/medico/obtener-consultasR';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const consultas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualConR - 1) * elementosPorPaginaConR;
            const fin = inicio + elementosPorPaginaConR;
            const itemsVisibles = consultas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualConR - 1) * elementosPorPaginaConR + 1;
            let paciente = '';
            let hc = '';

            itemsVisibles.forEach(con => {
                const persona = con.paciente?.persona;
                const cita = con.cita;
                paciente = persona
                    ? `${persona.nombres} ${persona.apellidos}`
                    : `${cita?.nombres_paciente ?? 'Paciente'} ${cita?.apellidos_paciente ?? 'no disponible'}`;
                hc = con.paciente?.hc ?? cita?.numero_hc ?? 'Sin HC';

                const citaId = con.cita.cita_id;

                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${paciente}</td>
                        <td>${hc}</td>
                        <td>${con.fecha_hora}</td>
                        <td class="text-center">
                            <button type="button" onclick="cargarDetalleCon(this);" class="btn btn-sm btn-info" data-id="${citaId}">
                                <i class="fas fa-circle-info"></i> Detalles
                            </button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaConsultasR').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagConR'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(consultas.length / elementosPorPaginaConR);

            const maximoBotonesVisibles = 5; // Número máximo de botones numéricos a mostrar
            let paginaInicio = Math.max(1, paginaActualConR - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualConR = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualConR) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualConR = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualConR = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}