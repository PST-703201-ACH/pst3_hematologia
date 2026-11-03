document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formReg');
    if (!formulario) return;

    const contenedorAlerta = document.getElementById('alertCreate');
    const camposRequeridos = new Set([
        'nombre1',
        'apellido1',
        'fecha_nac',
        'sexo',
        'cedula',
        'telefono',
        'correo',
        'estado',
        'municipio',
        'parroquia',
        'direccion',
        'rol'
    ]);
    let temporizadorAlerta;

    // Las alertas generales vencen en 3 segundos; los errores de campo se limpian al corregirlos.
    function mostrarAlertaRegistro(tipo, mensaje) {
        mostrarAlertaPersona(contenedorAlerta, tipo, mensaje);
        window.clearTimeout(temporizadorAlerta);

        const alerta = contenedorAlerta.querySelector('.alert');
        if (alerta) {
            temporizadorAlerta = window.setTimeout(() => alerta.remove(), 3000);
        }
    }

    function limpiarErrorDelCampo(evento) {
        const campo = evento.target;
        if (!camposRequeridos.has(campo.name) || !String(campo.value).trim() || !campo.checkValidity()) {
            return;
        }

        if (campo instanceof HTMLSelectElement && campo.selectedOptions[0]?.disabled) {
            return;
        }

        campo.classList.remove('is-invalid');
        const grupo = campo.closest('.form-group') || campo.parentElement;
        grupo.querySelectorAll('.persona-validation-feedback').forEach(mensaje => mensaje.remove());
    }

    formulario.addEventListener('input', limpiarErrorDelCampo);
    formulario.addEventListener('change', limpiarErrorDelCampo);

    const catalogosListos = cargarCatalogosPersona(formulario).catch(error => {
        console.error('Error al cargar los catálogos del registro:', error);
        mostrarAlertaRegistro(
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
                mostrarAlertaRegistro('success', resultado.mensaje);
                listarUsuarios();
                cargarEstadisticas();
                formulario.reset();
            } else if (resultado.status === 'error') {
                mostrarAlertaRegistro('danger', resultado.mensaje);
            } else if (resultado.status === 'errores' && resultado.errores) {
                mostrarErroresPersona(formulario, contenedorAlerta, resultado.errores);
            } else {
                mostrarAlertaRegistro(
                    'danger',
                    'No se pudo completar el registro. Inténtelo nuevamente.'
                );
            }
        } catch (error) {
            console.error('Error al registrar usuario:', error);
            mostrarAlertaRegistro(
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
