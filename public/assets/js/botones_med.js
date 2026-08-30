function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_med');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    const vista = document.getElementById(mostrar);
    if (vista) {
        vista.classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const btnListar = document.getElementById('btnListar');
    const btnRegistrar = document.getElementById('btnRegistrar');
    const btnCancelarReg = document.getElementById('btnCancelarReg');

    if (btnListar) {
        btnListar.addEventListener('click', function (listado) {
            listado.preventDefault();
            intercambiarVista('seccion-pacientes_listados');
        });
    }

    if (btnRegistrar) {
        btnRegistrar.addEventListener('click', function (registrar) {
            registrar.preventDefault();
            intercambiarVista('seccion-registrar_paciente');
        });
    }

    if (btnCancelarReg) {
        btnCancelarReg.addEventListener('click', function (cancelar) {
            cancelar.preventDefault();
            intercambiarVista('seccion-pacientes_listados');
        });
    }

    const vistaListado = document.getElementById('seccion-pacientes_listados');
    if (vistaListado && !vistaListado.classList.contains('d-none')) {
        vistaListado.classList.remove('d-none');
    }
});