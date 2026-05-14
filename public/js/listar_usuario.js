document.addEventListener('DOMContentLoaded', () => {
    listarUsuarios();
    const selectStatus = document.querySelector('select[name="filtroStatus"]');
    const selectRol = document.querySelector('select[name="filtroRol"]');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const inputBusqueda = document.querySelector('input[name="busqueda"]');

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



async function listarUsuarios(termino = '', status = '', rol = '') {
    try {

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

            const url = parametros.length > 0 ? `/obtener-usuarios?${parametros.join('&')}` : '/obtener-usuarios';


        const respuesta = await fetch(url);
        const usuarios = await respuesta.json();

        let filas = '';
        let id = 1;

        usuarios.forEach(usu => {
            if (usu.status == 1) {
                usu.status = '<p class="text-success">Activo</p>';
            } else if (usu.status == 0) {
                usu.status = '<p class="text-danger">Inactivo</p>';
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
                        <button class="btn btn-warning" data-id="${usu.persona_id}" onclick="cambiarStatus(this);">Cambiar status</button>
                        <button onclick="cargarDetalle(this)" class="btn btn-secondary" data-id="${usu.persona_id}">Detalles</button>
                        <button onclick="intercambiarVista('actualizar-usuario'); precargarDatos(this);" class="btn btn-primary" data-id="${usu.persona_id}">Actualizar</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('cuerpoTablaUsuarios').innerHTML = filas;

    } catch (error) {
        console.error("Error al listar:", error);
    }
}
