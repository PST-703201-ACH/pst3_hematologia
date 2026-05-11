
async function listarUsuarios() {
    try {
        const respuesta = await fetch('/obtener-usuarios');
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

document.addEventListener('DOMContentLoaded', listarUsuarios);