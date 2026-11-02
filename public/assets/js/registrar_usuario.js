document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formReg');
    if (!formulario) return;

    const contenedorAlerta = document.getElementById('alertCreate');
    const catalogosListos = cargarCatalogosPersona(formulario).catch(error => {
        console.error('Error al cargar los catálogos del registro:', error);
        mostrarAlertaPersona(
            contenedorAlerta,
            'danger',
            'No se pudieron cargar los datos de selección. Recargue la página e inténtelo de nuevo.'
        );
        return false;
    }).then(resultado => resultado !== false);

    formulario.addEventListener('submit', async function(e) {
        e.preventDefault();

        limpiarErroresPersona(formulario, contenedorAlerta);
        if (!validarCamposRequeridosPersona(formulario, contenedorAlerta, ['cedula'])) return;

        if (!confirm('¿Está seguro de registrar este nuevo usuario?')) return;

        if (!(await catalogosListos)) return;

        const botonRegistro = formulario.querySelector('[type="submit"]');
        botonRegistro.disabled = true;
        const cerrarModalCarga = mostrarModalCargaPersona('#registrandoModal');
        let respuesta;
        try {
            respuesta = await fetch(formulario.action, {
                method: 'POST',
                body: new FormData(formulario),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const resultado = await respuesta.json();

            if (resultado.status === 'exito') {
                mostrarAlertaPersona(contenedorAlerta, 'success', resultado.mensaje);
                listarUsuarios();
                cargarEstadisticas();
                formulario.reset();
            } else if (resultado.status === 'error') {
                mostrarAlertaPersona(contenedorAlerta, 'danger', resultado.mensaje);
            } else if (resultado.status === 'errores' && resultado.errores) {
                mostrarErroresPersona(formulario, contenedorAlerta, resultado.errores);
            } else {
                mostrarAlertaPersona(
                    contenedorAlerta,
                    'danger',
                    'No se pudo completar el registro. Inténtelo nuevamente.'
                );
            }
        } catch (error) {
            console.error('Error al registrar usuario:', error);
            mostrarAlertaPersona(
                contenedorAlerta,
                'danger',
                respuesta
                    ? `El servidor respondió con HTTP ${respuesta.status}. No se pudo completar el registro.`
                    : 'No se pudo conectar con el servidor. Verifique su conexión e inténtelo nuevamente.'
            );
        } finally {
            cerrarModalCarga();
            botonRegistro.disabled = false;
        }
    });
});
