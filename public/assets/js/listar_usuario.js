document.addEventListener('DOMContentLoaded', () => {
    const selectStatus = document.querySelector('select[name="filtroStatus_usu"]');
    const selectRol = document.querySelector('select[name="filtroRol_usu"]');
    const btnLimpiar = document.getElementById('btnLimpiar_usu');
    const inputBusqueda = document.querySelector('input[name="busqueda_usu"]');

    function buscarCombinado() {
        const texto = inputBusqueda ? inputBusqueda.value.trim() : '';
        const status = selectStatus ? selectStatus.value : '';
        const rol = selectRol ? selectRol.value : '';

        if (btnLimpiar) {
            if (texto.length > 0 || status !== '' || rol !== '') {
                btnLimpiar.classList.remove('d-none');
            } else {
                btnLimpiar.classList.add('d-none');
            }
        }

        listarUsuarios(texto, status, rol); 
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarCombinado);
    }

    if (selectStatus) {
        selectStatus.addEventListener('change', buscarCombinado);
    }

    if (selectRol) {
        selectRol.addEventListener('change', buscarCombinado);
    }

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (inputBusqueda) inputBusqueda.value = '';
            if (selectStatus) selectStatus.value = '';
            if (selectRol) selectRol.value = '';
            this.classList.add('d-none');
            listarUsuarios('', '', '');
        });
    }
});



let paginaActualUsu = 1;
const elementosPorPaginaUsu = 5; 

async function listarUsuarios(termino = '', status = '', rol = '') {
    try {
        paginaActualUsu = 1;

        let parametros = [];
                
        if (termino) {
            parametros.push(`busqueda=${encodeURIComponent(termino)}`);
        }
        
        if (status !== '') {
            parametros.push(`status=${encodeURIComponent(status)}`);
        }

        if (rol !== '') {
            parametros.push(`rol=${encodeURIComponent(rol)}`);
        }

        const url = parametros.length > 0 ? `/admin/obtener-usuarios?${parametros.join('&')}` : '/admin/obtener-usuarios';

        const respuesta = await fetch(url);
        
        const datosServidor = await respuesta.json();
        const usuarios = JSON.parse(JSON.stringify(datosServidor));

        function renderizarPaginador() {
            const inicio = (paginaActualUsu - 1) * elementosPorPaginaUsu;
            const fin = inicio + elementosPorPaginaUsu;
            const itemsVisibles = usuarios.slice(inicio, fin);

            let filas = '';
            let id = (paginaActualUsu - 1) * elementosPorPaginaUsu + 1;

            itemsVisibles.forEach(usu => {
                if (usu.status == 1) {
                    usu.status = '<p class="text-success">Activo</p>';
                } else if (usu.status == 0) {
                    usu.status = '<p class="text-danger">Inactivo</p>';
                } else if (usu.status == 2) {
                    usu.status = '<p class="text-warning">Verificar</p>';
                }
                filas += `
                    <tr>
                        <td>${id++}</td>
                        <td>${usu.persona.nombres} ${usu.persona.apellidos}</td>
                        <td>${usu.rol.nombre}</td>
                        <td>${usu.persona.cedula}</td>
                        <td>${usu.persona.telefono}</td>
                        <td class="status">${usu.status}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btnStatus" data-id="${usu.persona_id}" onclick="cambiarStatus(this);">Cambiar status</button>
                            <button onclick="cargarDetalle(this)" class="btn btn-secondary" data-id="${usu.persona_id}">Detalles</button>
                            <button onclick="intercambiarVista('actualizar-usuario'); precargarDatos(this);" class="btn btn-primary" data-id="${usu.persona_id}">Actualizar</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cuerpoTablaUsuarios').innerHTML = filas;

            const columnas = document.querySelectorAll('.status');
            columnas.forEach(col => {
                if (col.textContent == "Verificar") {
                    const fila = col.closest('tr');
                    if (fila) {
                        const boton = fila.querySelector('.btnStatus');
                        if (boton) {
                            boton.disabled = true;
                        }
                    }
                }
            });

            const contenedorBotones = document.getElementById('btnPagUsu'); 
            contenedorBotones.innerHTML = '';

            const totalPaginas = Math.ceil(usuarios.length / elementosPorPaginaUsu);

                const maximoBotonesVisibles = 5;
                let paginaInicio = Math.max(1, paginaActualUsu - Math.floor(maximoBotonesVisibles / 2));
                let paginaFin = paginaInicio + maximoBotonesVisibles - 1;

                if (paginaFin > totalPaginas) {
                    paginaFin = totalPaginas;
                    paginaInicio = Math.max(1, paginaFin - maximoBotonesVisibles + 1);
                }

                if (paginaInicio > 1) {
                    const btnPrimero = document.createElement('button');
                    btnPrimero.textContent = '«';
                    btnPrimero.className = 'btn btn-outline-primary m-1';
                    btnPrimero.addEventListener('click', () => { paginaActualUsu = 1; renderizarPaginador(); });
                    contenedorBotones.appendChild(btnPrimero);
                }

                for (let i = paginaInicio; i <= paginaFin; i++) {
                    const boton = document.createElement('button');
                    boton.textContent = i;
                    boton.className = 'btn btn-outline-primary m-1';

                    if (i === paginaActualUsu) {
                        boton.classList.replace('btn-outline-primary', 'btn-primary');
                    }

                    boton.addEventListener('click', () => {
                        paginaActualUsu = i;
                        renderizarPaginador();
                    });

                    contenedorBotones.appendChild(boton);
                }

                if (paginaFin < totalPaginas) {
                    const btnUltimo = document.createElement('button');
                    btnUltimo.textContent = '»';
                    btnUltimo.className = 'btn btn-outline-primary m-1';
                    btnUltimo.addEventListener('click', () => { paginaActualUsu = totalPaginas; renderizarPaginador(); });
                    contenedorBotones.appendChild(btnUltimo);
                }
        }

        renderizarPaginador();

    } catch (error) {
        console.error("Error al listar:", error);
    }
}

