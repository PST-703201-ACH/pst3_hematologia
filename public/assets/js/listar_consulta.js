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

    const pestañaRealizadas = document.querySelector('[data-target="#tabConR"]');
    if (pestañaRealizadas) {
        pestañaRealizadas.addEventListener('click', listarConsultaR);
    }
});

let paginaActualCon = 1;
const elementosPorPaginaCon = 5; 

async function listarConsultaP(termino = '', fecha = '') {
    try {
        paginaActualCon = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (fecha) {
            parametros.push(`fecha=${encodeURIComponent(fecha)}`);
        }

        const url = parametros.length > 0 ? `/medico/obtener-consultas?${parametros.join('&')}` : '/medico/obtener-consultas';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const consultas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualCon - 1) * elementosPorPaginaCon;
            const fin = inicio + elementosPorPaginaCon;
            const itemsVisibles = consultas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualCon - 1) * elementosPorPaginaCon + 1;
            let paciente = '';
            let hc = '';
            let enfermedad = '';

            itemsVisibles.forEach(con => {
                const persona = con.paciente?.persona;
                const cita = con.cita;
                paciente = persona
                    ? `${persona.nombres} ${persona.apellidos}`
                    : `${cita?.nombres_paciente ?? 'Paciente'} ${cita?.apellidos_paciente ?? 'no disponible'}`;
                hc = con.paciente?.hc ?? cita?.numero_hc ?? 'Sin HC';
                enfermedad = con.enfermedad?.tipo;

                if (enfermedad === null || enfermedad === undefined) {
                    enfermedad = 'No definida';
                }

                const citaId = con.cita?.cita_id ?? con.consulta_id ?? '';

                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${paciente}</td>
                        <td>${hc}</td>
                        <td>${con.fecha_hora}</td>
                        <td>${enfermedad}</td>
                        <td class="text-center">
                            <a href="/medico/consultas/atender/${citaId}" class="btn btn-sm btn-warning js-atender-consulta" data-cita-id="${citaId}">
                                <i class="fas fa-notes-medical"></i> Atender
                            </a>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaConsultasP').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagConP'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(consultas.length / elementosPorPaginaCon);

            const maximoBotonesVisibles = 5; // Número máximo de botones numéricos a mostrar
            let paginaInicio = Math.max(1, paginaActualCon - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualCon = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualCon) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualCon = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualCon = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}

async function listarConsultaR() {
    const cuerpoTabla = document.getElementById('cuerpoTablaConsultasR');

    if (!cuerpoTabla) {
        return;
    }

    try {
        const respuesta = await fetch('/medico/obtener-consultas-realizadas');

        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }

        const consultas = await respuesta.json();
        cuerpoTabla.innerHTML = consultas.map((consulta, indice) => {
            const paciente = consulta.paciente?.persona;
            const nombre = paciente
                ? `${paciente.nombres} ${paciente.apellidos}`
                : 'Paciente no disponible';
            const enfermedad = consulta.enfermedad?.tipo ?? 'No definida';

            return `
                <tr>
                    <td>${indice + 1}</td>
                    <td>${nombre}</td>
                    <td>${consulta.fecha_hora ?? ''}</td>
                    <td>${enfermedad}</td>
                    <td></td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error('Error al listar consultas realizadas:', error);
    }
}

async function abrirFormularioConsulta(citaId) {
    const contenedor = document.getElementById('contenedor-consulta-formulario');

    if (!contenedor || !citaId) {
        return;
    }

    try {
        const respuesta = await fetch(`/medico/consultas/atender/${citaId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!respuesta.ok) {
            throw new Error(`HTTP ${respuesta.status}`);
        }

        contenedor.innerHTML = await respuesta.text();
        intercambiarVista('seccion-consulta_formulario');
    } catch (error) {
        console.error('Error al cargar el formulario de consulta:', error);
    }
}

function volverAlListadoConsultas() {
    const formulario = document.getElementById('contenedor-consulta-formulario');

    if (formulario) {
        formulario.innerHTML = '';
    }

    intercambiarVista('seccion-consultas_listadas');
    listarConsultaP();
}

document.addEventListener('click', function (evento) {
    const atender = evento.target.closest('.js-atender-consulta');
    const volver = evento.target.closest('.js-volver-consultas');

    if (atender) {
        evento.preventDefault();
        abrirFormularioConsulta(atender.dataset.citaId);
    }

    if (volver) {
        evento.preventDefault();
        volverAlListadoConsultas();
    }
});