let catalogosDeUsuarioListos = Promise.resolve(true);
let datosInicialesUsuario = null;
const camposEditablesUsuario = [
    'nombre1',
    'nombre2',
    'apellido1',
    'apellido2',
    'fecha_nac',
    'sexo',
    'telefono',
    'correo',
    'estado',
    'municipio',
    'parroquia',
    'direccion',
    'rol'
];

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formUp');
    if (!formulario) return;

    const contenedorAlerta = document.getElementById('alertUpdate');
    catalogosDeUsuarioListos = cargarCatalogosPersona(formulario)
        .then(() => true)
        .catch(error => {
            console.error('Error al cargar los catálogos de actualización:', error);
            mostrarAlertaPersona(
                contenedorAlerta,
                'danger',
                'No se pudieron cargar los datos de selección. Recargue la página e inténtelo de nuevo.'
            );
            return false;
        });

    formulario.addEventListener('submit', async function (evento) {
        evento.preventDefault();

        limpiarErroresPersona(formulario, contenedorAlerta);
        if (!validarCamposRequeridosPersona(formulario, contenedorAlerta)) return;
        if (!(await catalogosDeUsuarioListos)) return;

        const datosActuales = obtenerDatosEditablesUsuario(formulario);
        if (datosInicialesUsuario && JSON.stringify(datosActuales) === JSON.stringify(datosInicialesUsuario)) {
            mostrarAlertaPersona(contenedorAlerta, 'warning', 'No hay cambios para guardar.');
            return;
        }

        if (!confirm('¿Está seguro de actualizar este usuario?')) return;

        const botonActualizacion = formulario.querySelector('[type="submit"]');
        botonActualizacion.disabled = true;
        const cerrarModalCarga = mostrarModalCargaPersona('#actualizandoModal');
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
                datosInicialesUsuario = datosActuales;
            } else if (resultado.status === 'error') {
                mostrarAlertaPersona(contenedorAlerta, 'danger', resultado.mensaje);
            } else if (resultado.status === 'errores' && resultado.errores) {
                mostrarErroresPersona(formulario, contenedorAlerta, resultado.errores);
            } else {
                mostrarAlertaPersona(
                    contenedorAlerta,
                    'danger',
                    'No se pudo completar la actualización. Inténtelo nuevamente.'
                );
            }
        } catch (error) {
            console.error('Error al actualizar usuario:', error);
            mostrarAlertaPersona(
                contenedorAlerta,
                'danger',
                respuesta
                    ? `El servidor respondió con HTTP ${respuesta.status}. No se pudo completar la actualización.`
                    : 'No se pudo conectar con el servidor. Verifique su conexión e inténtelo nuevamente.'
            );
        } finally {
            cerrarModalCarga();
            botonActualizacion.disabled = false;
        }
    });
});

async function precargarDatos(boton) {
    const formulario = document.getElementById('formUp');
    const contenedorAlerta = document.getElementById('alertUpdate');
    datosInicialesUsuario = null;
    limpiarErroresPersona(formulario, contenedorAlerta);

    try {
        if (!(await catalogosDeUsuarioListos)) return;

        const respuesta = await fetch(`/admin/precargar-usu/${boton.dataset.id}`);
        if (!respuesta.ok) throw new Error('No se pudieron cargar los datos del usuario.');

        const usuario = await respuesta.json();
        if (!usuario.persona) throw new Error('La respuesta no contiene los datos de la persona.');

        const persona = usuario.persona;
        const [nombre1 = '', nombre2 = ''] = persona.nombres.split(' ');
        const [apellido1 = '', apellido2 = ''] = persona.apellidos.split(' ');

        asignarValorPersona(formulario, 'nombre1', nombre1);
        asignarValorPersona(formulario, 'nombre2', nombre2);
        asignarValorPersona(formulario, 'apellido1', apellido1);
        asignarValorPersona(formulario, 'apellido2', apellido2);
        asignarValorPersona(formulario, 'fecha_nac', persona.fecha_nacimiento);
        asignarValorPersona(formulario, 'sexo', persona.sexo);
        asignarValorPersona(formulario, 'telefono', persona.telefono.replace(/^0/, ''));
        asignarValorPersona(formulario, 'correo', persona.email);
        asignarValorPersona(formulario, 'estado', persona.estado_id);
        asignarValorPersona(formulario, 'municipio', persona.municipio_id);
        asignarValorPersona(formulario, 'parroquia', persona.parroquia_id);
        asignarValorPersona(formulario, 'direccion', persona.direccion_exacta);
        asignarValorPersona(formulario, 'rol', usuario.id_rol);
        asignarValorPersona(formulario, 'persona', usuario.persona_id);
        datosInicialesUsuario = obtenerDatosEditablesUsuario(formulario);
    } catch (error) {
        console.error('Error al precargar datos del usuario:', error);
        mostrarAlertaPersona(
            contenedorAlerta,
            'danger',
            'No se pudieron cargar los datos del usuario. Recargue la página e inténtelo de nuevo.'
        );
    }
}

function asignarValorPersona(formulario, nombreCampo, valor) {
    const campo = formulario.elements.namedItem(nombreCampo);
    if (campo) campo.value = valor ?? '';
}

function obtenerDatosEditablesUsuario(formulario) {
    const datos = new FormData(formulario);
    return camposEditablesUsuario.map(nombreCampo => [nombreCampo, datos.get(nombreCampo) ?? '']);
}
