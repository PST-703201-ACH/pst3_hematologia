function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_admvo');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}


document.getElementById('btnCitas').addEventListener('click', function(citas) {
    citas.preventDefault();
    intercambiarVista('seccion-citas');

        calendar.render();

        setTimeout(function() {
        calendar.updateSize();
    }, 50);
});
