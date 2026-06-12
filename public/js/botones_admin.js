function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_admin');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}


document.getElementById('btnListPacientes').addEventListener('click', function(listado) {
    listado.preventDefault();
    intercambiarVista('seccion-pacientes_listados');
});