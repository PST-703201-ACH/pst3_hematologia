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

    const formulario = document.getElementById('formUsu');

    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            e.preventDefault();

            const datos = new FormData(formulario);

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
                    document.getElementById('alertaUsuario').innerHTML = alertaUsu;
                    formulario.reset();
                }else if(resultado.status === "error") {
                    let alertaUsu = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
<<<<<<< HEAD
                            <i class="icon fas fa-xmark"></i> 
=======
                            <i class="icon fas fa-check"></i> 
>>>>>>> 9b8cb449df078442379e25103917c7201c03974a
                            ${resultado.mensaje}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    document.getElementById('alertaUsuario').innerHTML = alertaUsu;

                }else if (resultado.status === "errores") {
                    for (let campo in resultado.errores) {
                    const elemento = document.getElementById(campo);
                        if (elemento) {
                        const originalValue = elemento.value;
                        elemento.className = 'form-control border border-danger text-danger';
                        elemento.value = resultado.errores[campo]; 
                        elemento.style.pointerEvents = 'none';

                            setTimeout(function(){
                              elemento.className = 'form-control';
                              elemento.style.pointerEvents = '';
                              elemento.value = originalValue;
                            }, 3000);
                        }
                    }
                }     
            } catch (error) {
                console.error("Error:", error);
            }
        });
    }
});
