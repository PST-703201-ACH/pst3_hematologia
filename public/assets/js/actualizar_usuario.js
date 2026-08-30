document.addEventListener('DOMContentLoaded', function () {
    async function cargarParroquias() {
        const select = document.getElementById('actualizar9');

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
        const select = document.getElementById('actualizar8');

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
        const select = document.getElementById('actualizar7');

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
        const select = document.getElementById('actualizar11');

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

    const formulario = document.getElementById('formUp');
    const actualizacionModal = new bootstrap.Modal(document.getElementById('actualizandoModal'));

    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            const confirmacion = confirm("¿Está seguro de actualizar este usuario?");
            e.preventDefault();
            if (confirmacion) {
                const datos = new FormData(formulario);
                actualizacionModal.show();

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
                        document.getElementById('alertUpdate').innerHTML = alertaUsu;
                        listarUsuarios();
                        cargarEstadisticas();
                        setTimeout(function (){
                            document.getElementById('alertUpdate').innerHTML = "";
                        }, 3000);
                        
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
                        document.getElementById('alertUpdate').innerHTML = alertaUsu;
                        setTimeout(function (){
                            document.getElementById('alertUpdate').innerHTML = "";
                        }, 3000);

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
                } finally {
                    setTimeout(() => {
                    actualizacionModal.hide();
                    }, 500);
                }
            }
        });
    }
})
	async function precargarDatos(boton) {
		const id = boton.getAttribute('data-id');

		try {
			const respuesta = await fetch(`/admin/precargar-usu/${id}`);
			const datos = await respuesta.json();

			const [nombre1, nombre2] = datos.persona.nombres.split(" ");
			document.getElementById('actualizar0').value = nombre1;
			document.getElementById('actualizar0.5').value = nombre2;
			const [apellido1, apellido2] = datos.persona.apellidos.split(" ");
			document.getElementById('actualizar1').value = apellido1;
			document.getElementById('actualizar1.5').value = apellido2;
			document.getElementById('actualizar2').value = datos.persona.fecha_nacimiento;
			document.getElementById('actualizar3').value = datos.persona.sexo;
			const telefono = datos.persona.telefono.replace(/^0/, "");
			document.getElementById('actualizar5').value = telefono;
			document.getElementById('actualizar6').value = datos.persona.email;
			document.getElementById('actualizar7').value = datos.persona.estado_id;
			document.getElementById('actualizar8').value = datos.persona.municipio_id;
			document.getElementById('actualizar9').value = datos.persona.parroquia_id;
			document.getElementById('actualizar10').value = datos.persona.direccion_exacta;
			document.getElementById('actualizar11').value = datos.id_rol;
            document.getElementById('X').value = datos.persona_id;

		} catch (error) { 
			console.error("Error al pre-cargar datos:", error);
		} 
	}

