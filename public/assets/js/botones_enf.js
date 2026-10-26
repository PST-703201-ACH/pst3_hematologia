function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_enf');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}

document.getElementById('btnJornada').addEventListener('click', function(jornada) {
    listarJornada();

    jornada.preventDefault();
    intercambiarVista('seccion-jornada');
});