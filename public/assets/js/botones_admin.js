function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_admin');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}


document.getElementById('btnUsuarios').addEventListener('click', function(listado0) {
    listarUsuarios();

    listado0.preventDefault();
    intercambiarVista('seccion-usuarios');
});

document.getElementById('btnRegistrarUsu').addEventListener('click', function(registrarUsu) {
    registrarUsu.preventDefault();
    intercambiarVista('registro-usuario');
});

document.getElementById('btnCancelarUp').addEventListener('click', function(cancelarUp) {
    cancelarUp.preventDefault();
    intercambiarVista('seccion-usuarios');
});

document.getElementById('btnCancelarReg').addEventListener('click', function(cancelarReg) {
    cancelarReg.preventDefault();
    intercambiarVista('seccion-usuarios');
});

document.getElementById('btnAuditoria').addEventListener('click', function(auditoria0) {
    listarAuditoria()
    auditoria0.preventDefault();
    intercambiarVista('seccion-auditoria');
});