document.addEventListener('DOMContentLoaded', () => {
    // Protocolo
    const busquedaProt = document.querySelector('input[name="busqueda_prot"]');

    function buscarProt() {
        const texto = busquedaProt ? busquedaProt.value.trim() : '';
        
        if (texto.length > 0) {
            listarProtocolo(texto);
        } else {
            listarProtocolo('');
        }         
    }

    if (busquedaProt) {
        busquedaProt.addEventListener('input', buscarProt);
    }

    // Enfermedad
    const tipoEnf = document.querySelector('select[name="filtroTip_enf"]');
    const btnLimpiarEnf = document.getElementById('btnLimpiar_enf');
    const busquedaEnf = document.querySelector('input[name="busqueda_enf"]');

    function buscarCombinadoEnf() {
        const texto = busquedaEnf ? busquedaEnf.value.trim() : '';
        const tipo = tipoEnf ? tipoEnf.value : '';
        
        if (btnLimpiarEnf) {
            if (texto.length > 0 || tipo !== '') {
                btnLimpiarEnf.classList.remove('d-none');
            } else {
                btnLimpiarEnf.classList.add('d-none');
            }
        }

        listarEnfermedad(texto, tipo); 
    }

    if (busquedaEnf) {
        busquedaEnf.addEventListener('input', buscarCombinadoEnf);
    }

    if (tipoEnf) {
        tipoEnf.addEventListener('change', buscarCombinadoEnf);
    }

    if (btnLimpiarEnf) {
        btnLimpiarEnf.addEventListener('click', function() {
            if (busquedaEnf) busquedaEnf.value = '';
            if (tipoEnf) tipoEnf.value = '';
            this.classList.add('d-none');
            listarEnfermedad('', '');
        });
    }

    // Medicina
    const btnLimpiarMed = document.getElementById('btnLimpiar_med');
    const busquedaMed = document.querySelector('input[name="busqueda_med"]');

    function buscarCombinadoMed() {
        const texto = busquedaMed ? busquedaMed.value.trim() : '';
        
        if (btnLimpiarMed) {
            if (texto.length > 0) {
                btnLimpiarMed.classList.remove('d-none');
            } else {
                btnLimpiarMed.classList.add('d-none');
            }
        }

        listarMedicina(texto); 
    }

    if (busquedaMed) {
        busquedaMed.addEventListener('input', buscarCombinadoMed);
    }

    if (btnLimpiarMed) {
        btnLimpiarMed.addEventListener('click', function() {
            if (busquedaMed) busquedaMed.value = '';
            this.classList.add('d-none');
            listarMedicina('');
        });
    }
});

let paginaActualMed = 1;
const elementosPorPaginaMed = 5; 

async function listarMedicina(termino = '') {
    try {
        paginaActualMed = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }

        const url = parametros.length > 0 ? `/admin/obtener-medicinas?${parametros.join('&')}` : '/admin/obtener-medicinas';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const medicinas = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualMed - 1) * elementosPorPaginaMed;
            const fin = inicio + elementosPorPaginaMed;
            const itemsVisibles = medicinas.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualMed - 1) * elementosPorPaginaMed + 1;
            let nombre = '';
            let tipo = '';
            let status = '';

            itemsVisibles.forEach(med => {
                nombre = med.descripcion;
                
                if (med.tipo == 1) {
                    tipo = `<h6 class="text-warning">Benigna</h6>`;
                }
                else if (med.tipo == 2) {
                    tipo = `<h6 class="text-danger">Maligna</h6>`;
                }
                
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${nombre}</td>
                        <td class="text-center">
                            <button class="btn btn-primary" onclick="intercambiarVista('actualizar-medicina'); precargarDatosMed(this);" data-id="${med.medicina_id}">Editar</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaMedicinas').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagMed'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(medicinas.length / elementosPorPaginaMed);

            const maximoBotonesVisibles = 5;
            let paginaInicio = Math.max(1, paginaActualMed - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualMed = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualMed) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualMed = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualMed = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}

let paginaActualEnf = 1;
const elementosPorPaginaEnf = 5;

async function listarEnfermedad(termino = '', tip = '') {
    try {
        paginaActualEnf = 1; 

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (tip !== '') {
            parametros.push(`tip=${encodeURIComponent(tip)}`);
        }

        const url = parametros.length > 0 ? `/admin/obtener-enfermedades?${parametros.join('&')}` : '/admin/obtener-enfermedades';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const enfermedades = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualEnf - 1) * elementosPorPaginaEnf;
            const fin = inicio + elementosPorPaginaEnf;
            const itemsVisibles = enfermedades.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualEnf - 1) * elementosPorPaginaEnf + 1;
            let nombre = '';
            let tipo = '';
            let status = '';

            itemsVisibles.forEach(enf => {
                nombre = enf.descripcion;
                
                if (enf.tipo == 1) {
                    tipo = `<h6 class="text-warning">Benigna</h6>`;
                }
                else if (enf.tipo == 2) {
                    tipo = `<h6 class="text-danger">Maligna</h6>`;
                }
                
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${nombre}</td>
                        <td>${tipo}</td>
                        <td class="text-center">
                            <button class="btn btn-primary" onclick="intercambiarVista('actualizar-enfermedad'); precargarDatosEnf(this);" data-id="${enf.enfermedad_id}">Editar</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaEnfermedades').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagEnf'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(enfermedades.length / elementosPorPaginaEnf);

            const maximoBotonesVisibles = 5;
            let paginaInicio = Math.max(1, paginaActualEnf - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualEnf = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualEnf) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualEnf = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualEnf = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}


let paginaActualProt = 1;
const elementosPorPaginaProt = 5;

async function listarProtocolo(termino = '') {
    try {
        paginaActualProt = 1;

        let parametros = [];
            
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        const url = parametros.length > 0 ? `/admin/obtener-protocolos?${parametros.join('&')}` : '/admin/obtener-protocolos';

        const respuesta = await fetch(url);

        const datosServidor = await respuesta.json();
        const protocolos = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualProt - 1) * elementosPorPaginaProt;
            const fin = inicio + elementosPorPaginaProt;
            const itemsVisibles = protocolos.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualProt - 1) * elementosPorPaginaProt + 1;
            let nombre = '';

            itemsVisibles.forEach(prot => {
                nombre = prot.nombre;
                
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${nombre}</td>
                        <td class="text-center">
                            <button class="btn btn-primary" data-id="${prot.protocolo_id}" onclick="verFases(this);">Fases</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaProtocolos').innerHTML = filas;

            const contenedorBotones = document.getElementById('btnPagProt'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(protocolos.length / elementosPorPaginaProt);

            const maximoBotonesVisibles = 5;
            let paginaInicio = Math.max(1, paginaActualProt - Math.floor(maximoBotonesVisibles / 2));
            let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

            if (paginaFin > totalPaginas) {
                paginaFin = totalPaginas;
                paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
            }

            if (paginaInicio > 1) {
                const btnPrimero = document.createElement('button');
                btnPrimero.textContent = '«';
                btnPrimero.className = 'btn btn-outline-primary m-1';
                btnPrimero.addEventListener('click', () => { paginaActualProt = 1; renderizarPaginador(); });
                contenedorBotones.appendChild(btnPrimero);
            }

            for (let i = paginaInicio; i <= paginaFin; i++) {
                const boton = document.createElement('button');
                boton.textContent = i;
                boton.className = 'btn btn-outline-primary m-1';

                if (i === paginaActualProt) {
                    boton.classList.replace('btn-outline-primary', 'btn-primary');
                }

                boton.addEventListener('click', () => {
                    paginaActualProt = i;
                    renderizarPaginador();
                });

                contenedorBotones.appendChild(boton);
            }

            if (paginaFin < totalPaginas) {
                const btnUltimo = document.createElement('button');
                btnUltimo.textContent = '»';
                btnUltimo.className = 'btn btn-outline-primary m-1';
                btnUltimo.addEventListener('click', () => { paginaActualProt = totalPaginas; renderizarPaginador(); });
                contenedorBotones.appendChild(btnUltimo);
            }
        }
        renderizarPaginador();
    } catch (error) {
        console.error("Error al listar:", error);
    }
}