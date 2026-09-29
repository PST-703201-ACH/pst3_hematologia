function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_admin');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}

function addClickListener(elementId, handler) {
    const element = document.getElementById(elementId);

    if (element) {
        element.addEventListener('click', handler);
    }
}

addClickListener('btnUsuarios', function(listado0) {
    listarUsuarios();

    listado0.preventDefault();
    intercambiarVista('seccion-usuarios');
});

addClickListener('btnRegistrarUsu', function(registrarUsu) {
    registrarUsu.preventDefault();
    intercambiarVista('registro-usuario');
});

addClickListener('btnCancelarUp', function(cancelarUp) {
    cancelarUp.preventDefault();
    intercambiarVista('seccion-usuarios');
});

addClickListener('btnCancelarReg', function(cancelarReg) {
    cancelarReg.preventDefault();
    intercambiarVista('seccion-usuarios');
});

addClickListener('btnCatalogo', function(catalogo) {
    catalogo.preventDefault();
    listarProtocolo();
    listarEnfermedad();
    listarMedicina();
    intercambiarVista('seccion-catalogo');
});


document.getElementById('btnRegProt').addEventListener('click', function(registrarProt) {
    registrarProt.preventDefault();
    intercambiarVista('registrar-protocolo');
});

document.getElementById('btnSalirRegProt').addEventListener('click', function(cancelarRegProt) {
    cancelarRegProt.preventDefault();
    intercambiarVista('seccion-catalogo');
});

addClickListener('btnRegEnf', function(registrarEnf) {
    registrarEnf.preventDefault();
    intercambiarVista('registrar-enfermedad');
});

addClickListener('btnSalirRegEnf', function(cancelarRegEnf) {
    cancelarRegEnf.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnSalirUpEnf').addEventListener('click', function(cancelarUpEnf) {
    cancelarUpEnf.preventDefault();
    intercambiarVista('seccion-catalogo');
});

document.getElementById('btnRegMed').addEventListener('click', function(registrarMed) {
    registrarMed.preventDefault();
    intercambiarVista('registrar-medicina');
});

addClickListener('btnSalirRegMed', function(cancelarRegMed) {
    cancelarRegMed.preventDefault();
    intercambiarVista('seccion-catalogo');
});

addClickListener('btnSalirUpMed', function(cancelarUpMed) {
    cancelarUpMed.preventDefault();
    intercambiarVista('seccion-catalogo');
});

addClickListener('btnAuditoria', function(auditoria0) {
    listarAuditoria()
    auditoria0.preventDefault();
    intercambiarVista('seccion-auditoria');
});