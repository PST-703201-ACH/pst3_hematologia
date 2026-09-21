function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_med');

    todas.forEach(vista => {
        vista.classList.add('d-none');
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
    const btnListarCon = document.getElementById('btnListarCon');

    if (btnListarPa) {
        btnListarPa.addEventListener('click', function (evento) {
            evento.preventDefault();
            intercambiarVista('seccion-pacientes_listados');
        });
    }

    if (btnRegistrar) {
        btnRegistrar.addEventListener('click', function (evento) {
            evento.preventDefault();
            intercambiarVista('seccion-registrar_paciente');
        });
    }

    if (btnCancelarReg) {
        btnCancelarReg.addEventListener('click', function (evento) {
            evento.preventDefault();
            intercambiarVista('seccion-pacientes_listados');
        });
    }

    if (btnListarCon) {
        btnListarCon.addEventListener('click', function (evento) {
            evento.preventDefault();
            intercambiarVista('seccion-consultas_listadas');

            if (typeof listarConsultaP === 'function') {
                listarConsultaP();
            }
        });
    }
});