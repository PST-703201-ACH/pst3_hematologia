async function listarUsuarios() {
    try {
        const respuesta = await fetch('/obtener-usuarios');
        const usuarios = await respuesta.json();

        let filas = '';
        let id = 1;

        usuarios.forEach(usu => {
            filas += `
                <tr>
                    <td>${id++}</td>
                    <td>${usu.persona.nombres} ${usu.persona.apellidos}</td>
                    <td>${usu.rol.nombre}</td>
                    <td>${usu.username}</td>
                    <td>${usu.persona.telefono}</td>
                    <td>${usu.persona.email}</td>
                    <td><button class="btn btn-info">Detalles</button></td>
                </tr>
            `;
        });

        document.getElementById('cuerpoTablaUsuarios').innerHTML = filas;

    } catch (error) {
        console.error("Error al listar:", error);
    }
}

document.addEventListener('DOMContentLoaded', listarUsuarios);