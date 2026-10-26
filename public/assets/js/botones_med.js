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
    const btnRegistrar = document.getElementById('btnRegistrar');
    const btnCancelarReg = document.getElementById('btnCancelarReg');
    const btnListarCon = document.getElementById('btnListarCon');
    const btnProgramarTratamiento = document.getElementById('btnProgramarTratamiento');

    document.getElementById('btnListarPa').addEventListener('click', function (listarPa) {
        listarPa.preventDefault();
        intercambiarVista('seccion-pacientes_listados');
    });

    document.getElementById('btnListarCon').addEventListener('click', function(listadoCon) {
        listadoCon.preventDefault();
        intercambiarVista('seccion-consultas');
        
        if (typeof listarConsultaP === 'function') {
            listarConsultaP();
            listarConsultaR();
        }
    });

    document.getElementById('btnListarPa').addEventListener('click', function (listarPa) {
        listarPa.preventDefault();
        intercambiarVista('seccion-pacientes_listados');
    });

    document.getElementById('btnSalirConReg').addEventListener('click', function () {
        document.getElementById('formRegCon').reset();
        intercambiarVista('seccion-consultas');
    });
});