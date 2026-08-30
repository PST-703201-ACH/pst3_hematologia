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

document.getElementById('btnCatalogo').addEventListener('click', function(catalogo) { 
    catalogo.preventDefault();
    listarEnfermedad();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnRegEnf').addEventListener('click', function(registrarEnf) {
    registrarEnf.preventDefault();
    intercambiarVista('registrar-enfermedad');
});

document.getElementById('btnSalirRegEnf').addEventListener('click', function(cancelarRegEnf) {
    cancelarRegEnf.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnSalirUpEnf').addEventListener('click', function(registrarMed) {
    cancelarUpEnf.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnRegMed').addEventListener('click', function(registrarEnf) {
    registrarMed.preventDefault();
    intercambiarVista('registrar-medicina');
});

document.getElementById('btnSalirRegMed').addEventListener('click', function(cancelarRegMed) {
    cancelarRegMed.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnSalirUpMed').addEventListener('click', function(cancelarUpMed) {
    cancelarUpMed.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnAuditoria').addEventListener('click', function(auditoria0) {
    listarAuditoria()
    auditoria0.preventDefault();
    intercambiarVista('seccion-auditoria');
});