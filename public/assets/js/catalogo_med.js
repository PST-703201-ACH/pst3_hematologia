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

// Precargar medicinas
let medicinas = [];
async function cargarMedicinas() {
    try {
        const respuesta = await fetch('/admin/obtener-medicinas-disponibles');
        
        medicinas = await respuesta.json(); 
    } catch (error) {
        console.error("Error al cargar:", error);
    }
}

cargarMedicinas();

const medicinasSeleccionables = [...medicinas];

function enumerarFases() {
    const numeroFase = document.querySelectorAll('.numFa');
    
    numeroFase.forEach((fase, index) => {
        fase.textContent = `Fase №${index + 1}`;
        
        const tablaPadre = fase.closest('table');
        
        if (tablaPadre) {
            const tbodyFa = tablaPadre.querySelector('tbody');
            
            if (tbodyFa) {
                tbodyFa.setAttribute('id', `${index + 1}`);
            }
        }
    });
}

function enumerarMed(tbodyFa){
    const numeroMed = tbodyFa.querySelectorAll('.numMed');
    const selectMed = tbodyFa.querySelectorAll('.seleccion-med');
    const numFase = tbodyFa.id;
    contador = 1;
    numeroMed.forEach((med, index) => {
        med.textContent = contador++;
        selectMed[index].setAttribute('name', `fase-${numFase}[]`);
   });
}


function agregarMed(tbody){
    try {

    // Creacion de nueva fila
    const trFa2 = document.createElement('tr');
    const tdMedNum = document.createElement('td');
    tdMedNum.className = "numMed";
    trFa2.appendChild(tdMedNum);
    const tdMed = document.createElement('td');
    const select = document.createElement('select');
    select.className = 'form-control seleccion-med';
    select.innerHTML = '<option value="" disabled selected>Seleccione un medicamento</option>';
    tdMed.appendChild(select);
    trFa2.appendChild(tdMed);
    var tdEliminar = document.createElement('td');
    var btnEliminar = document.createElement('button');
    btnEliminar.className = "btn btn-danger";
    btnEliminar.type = "button";
    const iconoBoton = document.createElement('i');
    iconoBoton.className = "fas fa-solid fa-minus";
    btnEliminar.appendChild(iconoBoton);
    tdEliminar.appendChild(btnEliminar);
    trFa2.appendChild(tdEliminar);

    let selectAnterior = null;

    function refrescarFiltros() {
        const idsOcupados = Array.from(tbody.querySelectorAll('.seleccion-med'))
        .map(sel => parseInt(sel.value))
        .filter(id => !isNaN(id));

        tbody.querySelectorAll('.seleccion-med').forEach(otroSelect => {
            const valorDeEsteSelect = parseInt(otroSelect.value);

            Array.from(otroSelect.options).forEach(opcion => {
                const idOpcion = parseInt(opcion.value);
                if (isNaN(idOpcion)) return;

                if (idsOcupados.includes(idOpcion) && idOpcion !== valorDeEsteSelect) {
                    opcion.style.display = 'none';
                } else {
                    opcion.style.display = '';
                }
            });
        });
    };

    select.addEventListener('change', () => {
        const idMed = parseInt(select.value);

        if (isNaN(idMed) || !idMed) {
            selectAnterior = null;
            refrescarFiltros();
            return; 
        }

        selectAnterior = idMed;
        
        refrescarFiltros();
    });


    btnEliminar.addEventListener('click', () => {
        trFa2.remove();
        enumerarMed(tbody);
        refrescarFiltros();
    });

    
    medicinas.forEach(med => {
        select.innerHTML += `<option value="${med.medicina_id}">${med.descripcion}</option>`;
    });

    tbody.appendChild(trFa2);
    enumerarMed(tbody);
    
    refrescarFiltros();
    
    } catch (error) {
        console.error("Error:", error);
    }
}

function crearFase() {
    // Creacion de tabla
    const contenedorTabla = document.getElementById('fasesProt');
    const tablaFa = document.createElement('table');
    tablaFa.className = 'table table-striped';

    // Creacion de encabezado
    const theadTabla = document.createElement('thead');

    // Primera fila del encabezado
    const trFa0 = document.createElement('tr');
    const thFa0 = document.createElement('th');
    thFa0.colSpan = 2;
    const pNumero = document.createElement('p');
    pNumero.className = "numFa";
    pNumero.textContent = "Fase №";
    pNumero.style.textAlign = "center"; 
    thFa0.appendChild(pNumero);
    const thFa1 = document.createElement('th');
    const btnEliminarFa = document.createElement('button');
    btnEliminarFa.type = "button";
    btnEliminarFa.className = "btn btn-danger elimFa";
    const iconoBoton0 = document.createElement('i');
    iconoBoton0.className = "fas fa-solid fa-minus";
    btnEliminarFa.appendChild(iconoBoton0);
    thFa1.appendChild(btnEliminarFa);

    // Boton para eliminar una fase
    btnEliminarFa.addEventListener('click', function() {
        const fase = btnEliminarFa.closest('table');
        if (fase) {
            fase.remove();
            if (typeof enumerarFases === 'function') {
                enumerarFases();
            }
        }
        return;
    })    

    //Segunda fila del encabezado
    const trFa1 = document.createElement('tr');
    const thFa2 = document.createElement('td');
    thFa2.textContent = "#";
    const thFa3 = document.createElement('td');
    thFa3.textContent = "Medicamento";
    thFa3.style.textAlign = "center";
    const thFa4 = document.createElement('th');
    const btnCrearFila = document.createElement('button');
    btnCrearFila.type = "button";
    btnCrearFila.className = "btn btn-success aggMed";
    const iconoBoton1 = document.createElement('i');
    iconoBoton1.className = "fas fa-solid fa-plus";
    btnCrearFila.appendChild(iconoBoton1);
    thFa4.appendChild(btnCrearFila);

    // Union de elementos del encabezado
    trFa0.appendChild(thFa0);
    trFa0.appendChild(thFa1);
    trFa1.appendChild(thFa2);
    trFa1.appendChild(thFa3);
    trFa1.appendChild(thFa4);
    theadTabla.appendChild(trFa0);
    theadTabla.appendChild(trFa1);
    tablaFa.appendChild(theadTabla);

    // Creacion del cuerpo de la tabla
    const tbodyFa = document.createElement('tbody');

    // Creacion de fila inicial para la seleccion de medicamento
    agregarMed(tbodyFa);
    
    // Union de elementos del cuerpo
    tablaFa.appendChild(tbodyFa);
    
    // Union de la tabla al contenedor
    contenedorTabla.appendChild(tablaFa);
    enumerarFases();
    enumerarMed(tbodyFa);

    // Boton para la creacion de nueva fila para la seleccion de medicamento
    btnCrearFila.addEventListener('click', function() {
        agregarMed(tbodyFa);
    });

}

// Boton de crear nueva fase
document.getElementById('btnNuevaFase').addEventListener('click', () => {
    crearFase();
    enumerarFases();
});