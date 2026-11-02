// Comparte la carga de catálogos y la presentación de validaciones entre los formularios de usuarios.
const catalogosPersona = [
    { campo: 'estado', ruta: '/obtener-estados', clave: 'estado_id' },
    { campo: 'municipio', ruta: '/obtener-municipios', clave: 'municipio_id' },
    { campo: 'parroquia', ruta: '/obtener-parroquias', clave: 'parroquia_id' },
    { campo: 'rol', ruta: '/obtener-roles', clave: 'rol_id' }
];

function validarCamposRequeridosPersona(formulario, contenedorAlerta, camposAdicionales = []) {
    const camposRequeridos = [
        'nombre1',
        'apellido1',
        'fecha_nac',
        'sexo',
        'telefono',
        'correo',
        'estado',
        'municipio',
        'parroquia',
        'direccion',
        'rol',
        ...camposAdicionales
    ];
    const errores = {};

    camposRequeridos.forEach(nombreCampo => {
        const campo = formulario.elements.namedItem(nombreCampo);
        const opcion = campo instanceof HTMLSelectElement ? campo.selectedOptions[0] : null;
        if (!campo || !String(campo.value).trim() || opcion?.disabled) {
            errores[nombreCampo] = ['Este campo es obligatorio.'];
        }
    });

    mostrarErroresPersona(formulario, contenedorAlerta, errores);
    return Object.keys(errores).length === 0;
}

function cargarCatalogosPersona(formulario) {
    return Promise.all(catalogosPersona.map(async catalogo => {
        const select = formulario.elements.namedItem(catalogo.campo);
        if (!(select instanceof HTMLSelectElement)) {
            throw new Error(`No se encontró el selector de ${catalogo.campo}.`);
        }

        const respuesta = await fetch(catalogo.ruta);
        if (!respuesta.ok) {
            throw new Error(`No se pudo cargar el catálogo de ${catalogo.campo}.`);
        }

        const registros = await respuesta.json();
        if (!Array.isArray(registros)) {
            throw new Error(`La respuesta del catálogo de ${catalogo.campo} no es válida.`);
        }

        registros.forEach(registro => {
            const opcion = document.createElement('option');
            opcion.value = registro[catalogo.clave];
            opcion.textContent = registro.nombre;
            select.appendChild(opcion);
        });
    }));
}

function limpiarErroresPersona(formulario, contenedor) {
    formulario.querySelectorAll('.is-invalid').forEach(elemento => {
        elemento.classList.remove('is-invalid');
    });
    formulario.querySelectorAll('.persona-validation-feedback').forEach(mensaje => {
        mensaje.remove();
    });
    contenedor.replaceChildren();
}

function crearAlertaPersona(tipo) {
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.setAttribute('role', 'alert');

    const cerrar = document.createElement('button');
    cerrar.type = 'button';
    cerrar.className = 'close';
    cerrar.setAttribute('data-dismiss', 'alert');
    cerrar.setAttribute('aria-label', 'Cerrar');
    cerrar.innerHTML = '<span aria-hidden="true">&times;</span>';
    alerta.appendChild(cerrar);

    return alerta;
}

function mostrarErroresPersona(formulario, contenedor, errores) {
    limpiarErroresPersona(formulario, contenedor);

    Object.entries(errores).forEach(([nombreCampo, mensajes]) => {
        const textoMensajes = (Array.isArray(mensajes) ? mensajes : [mensajes]).join(' ');
        const campo = Array.from(formulario.elements).find(elemento => elemento.name === nombreCampo);
        if (campo && campo.type !== 'hidden') {
            campo.classList.add('is-invalid');
            const grupo = campo.closest('.form-group') || campo.parentElement;
            const mensaje = document.createElement('div');
            mensaje.className = 'invalid-feedback d-block persona-validation-feedback';
            mensaje.textContent = textoMensajes;
            grupo.appendChild(mensaje);
        }
    });
}

function mostrarAlertaPersona(contenedor, tipo, mensaje) {
    contenedor.replaceChildren();

    const alerta = crearAlertaPersona(tipo);
    alerta.insertBefore(document.createTextNode(mensaje), alerta.firstChild);
    contenedor.appendChild(alerta);
}

function mostrarModalCargaPersona(selector) {
    const modal = $(selector);
    let mostrado = false;
    let finalizado = false;

    modal.one('shown.bs.modal', function () {
        mostrado = true;
        if (finalizado) modal.modal('hide');
    });
    modal.modal('show');

    return function cerrarModalCarga() {
        finalizado = true;
        if (mostrado) modal.modal('hide');
    };
}
