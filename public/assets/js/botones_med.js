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
    const btnListarPa = document.getElementById('btnListarPa');
    const btnRegistrar = document.getElementById('btnRegistrar');
    const btnCancelarReg = document.getElementById('btnCancelarReg');

document.getElementById('btnListarPaPa').addEventListener('click', function(listadoPa) {
    listadoPa.preventDefault();
    intercambiarVista('seccion-pacientes_listados');
    listarPacientes();
});

    if (btnListarPa) {
        btnListarPa.addEventListener('click', function (listadoPa) {
            listadoPa.preventDefault();
            intercambiarVista('seccion-pacientes_listados');
        });
    }

    if (btnRegistrar) {
        btnRegistrar.addEventListener('click', function (registrar) {
            registrar.preventDefault();
            intercambiarVista('seccion-registrar_paciente');
        });
    }

document.getElementById('btnCancelarReg').addEventListener('click', function(cancelar) {
    cancelar.preventDefault();
    intercambiarVista('seccion-pacientes_listados');
});

document.getElementById('btnListarCon').addEventListener('click', function(listadoCon) {
    listadoCon.preventDefault();
    intercambiarVista('seccion-consultas_listadas');
});

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