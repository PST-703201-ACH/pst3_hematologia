async function cargarEstadisticas() {
    const respuesta = await fetch('/admvo/dashboard-admvo');
    const datos = await respuesta.json();

    document.getElementById('total-citas').innerText = datos.citas;
}

document.addEventListener('DOMContentLoaded', cargarEstadisticas);