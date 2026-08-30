function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_med');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}


document.getElementById('btnListarPa').addEventListener('click', function(listadoPa) {
    listadoPa.preventDefault();
    intercambiarVista('seccion-pacientes_listados');
    listarPacientes();
});

document.getElementById('btnRegistrar').addEventListener('click', function(registrar) {
    registrar.preventDefault();
    intercambiarVista('seccion-registrar_paciente');
});

document.getElementById('btnCancelarReg').addEventListener('click', function(cancelar) {
    cancelar.preventDefault();
    intercambiarVista('seccion-pacientes_listados');
});

document.getElementById('btnListarCon').addEventListener('click', function(listadoCon) {
    listadoCon.preventDefault();
    intercambiarVista('seccion-consultas_listadas');
    //listarConsultas();
});
