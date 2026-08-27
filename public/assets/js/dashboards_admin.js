async function cargarEstadisticas() {
    const respuesta = await fetch('/admin/dashboard-admin');
    const datos = await respuesta.json();

    document.getElementById('total-usuarios').innerText = datos.usuarios;
    document.getElementById('total-personas').innerText = datos.personas;
    document.getElementById('total-gerentes').innerText = datos.gerentes;
    document.getElementById('total-administrativos').innerText = datos.administrativos;
    document.getElementById('total-medicos').innerText = datos.medicos;
    document.getElementById('total-enfermeros').innerText = datos.enfermeros;
}

document.addEventListener('DOMContentLoaded', cargarEstadisticas);