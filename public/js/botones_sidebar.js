function intercambiarVista(mostrar) {
    const todas = document.querySelectorAll('.vista_admin');

    todas.forEach(vistas => {
        vistas.classList.add('d-none');
    });

    document.getElementById(mostrar).classList.remove('d-none');
}


document.getElementById('btnUsuarios').addEventListener('click', function(listado) {
    listado.preventDefault();
    intercambiarVista('seccion-usuarios');
});

document.getElementById('btnCancelarReg').addEventListener('click', function(listado) {
    listado.preventDefault();
    intercambiarVista('seccion-usuarios');
});

document.getElementById('btnRegistrarUsu').addEventListener('click', function(registro) {
    registro.preventDefault();
    intercambiarVista('registro-usuario');
});
