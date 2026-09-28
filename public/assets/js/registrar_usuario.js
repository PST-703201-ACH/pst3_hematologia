document.addEventListener('DOMContentLoaded', function() {
    async function cargarParroquias() {
        const select = document.getElementById('9');

        try {
            const respuesta = await fetch('/obtener-parroquias');
            const parroquias = await respuesta.json();


            parroquias.forEach(parroquia => {
                const option = document.createElement('option');
                option.value = parroquia.parroquia_id;
                option.textContent = parroquia.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar parroquias:", error);
        }
    }

    async function cargarMunicipios() {
        const select = document.getElementById('8');

        try {
            const respuesta = await fetch('/obtener-municipios');
            const municipios = await respuesta.json();


            municipios.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.municipio_id;
                option.textContent = municipio.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar municipios:", error);
        }
    }

    async function cargarEstados() {
        const select = document.getElementById('7');

        try {
            const respuesta = await fetch('/obtener-estados');
            const estados = await respuesta.json();


            estados.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.estado_id;
                option.textContent = estado.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar estados:", error);
        }
    }

    async function cargarRoles() {
        const select = document.getElementById('11');

        try {
            const respuesta = await fetch('/obtener-roles');
            const roles = await respuesta.json();


            roles.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.rol_id;
                option.textContent = rol.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar roles:", error);
        }
    }
    cargarEstados();
    cargarMunicipios();
    cargarParroquias();
    cargarRoles();

    const formulario = document.getElementById('formReg');
    const registroModal = new bootstrap.Modal(document.getElementById('registrandoModal'));

    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            const confirmacion = confirm("¿Está seguro de registrar este nuevo usuario?");
            e.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formulario);
                registroModal.show();

                try {
                    const respuesta = await fetch(formulario.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaUsu = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreate').innerHTML = alertaUsu;
                        listarUsuarios();
                        cargarEstadisticas();
                        setTimeout(function (){
                            document.getElementById('alertCreate').innerHTML = "";
                        }, 3000);

                        formulario.reset();
                    }else if(resultado.status === "error") {
                        let alertaUsu = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreate').innerHTML = alertaUsu;
                        setTimeout(function (){
                            document.getElementById('alertCreate').innerHTML = "";
                        }, 3000);

                    }else if (resultado.status === "errores") {
                        const alerta = document.createElement('div');
                        alerta.className = 'alert alert-danger alert-dismissible fade show';
                        alerta.setAttribute('role', 'alert');

                        const listaErrores = document.createElement('ul');
                        listaErrores.className = 'mb-0';

                        Object.entries(resultado.errores).forEach(([campo, mensajes]) => {
                            const item = document.createElement('li');
                            const mensajesCampo = Array.isArray(mensajes) ? mensajes : [mensajes];
                            item.textContent = `${campo}: ${mensajesCampo.join(', ')}`;
                            listaErrores.appendChild(item);
                        });

                        alerta.appendChild(listaErrores);
                        document.getElementById('alertCreate').replaceChildren(alerta);
                    }     
                } catch (error) {
                    console.error("Error:", error);
                    const alerta = document.createElement('div');
                    alerta.className = 'alert alert-danger';
                    alerta.setAttribute('role', 'alert');
                    alerta.textContent = 'No se pudo completar el registro. Verifique los datos e inténtelo nuevamente.';
                    document.getElementById('alertCreate').replaceChildren(alerta);
                } finally {
                    registroModal.hide();
                }
            }

        });
    }
});
