document.addEventListener('DOMContentLoaded', function () {
    const formularioUpEnf = document.getElementById('formUpEnf');
    const actualizacionModalEnf = new bootstrap.Modal(document.getElementById('actualizandoModalEnf'));

    if (formularioUpEnf) {
        formularioUpEnf.addEventListener('submit', async function(enfUp) {
            const confirmacion = confirm("¿Está seguro de actualizar los datos de esta enfermedad?");
            enfUp.preventDefault();
            if (confirmacion) {
                const datos = new FormData(formularioUpEnf);
                actualizacionModalEnf.show();

                try {
                    console.log("URL detectada por JS:", formularioUpEnf.action);

                    const respuesta = await fetch(formularioUpEnf.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaEnf = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertUpdateEnf').innerHTML = alertaEnf;
                        listarEnfermedad();
                        setTimeout(function (){
                            document.getElementById('alertUpdateEnf').innerHTML = "";
                        }, 3000);
                        
                    }else if(resultado.status === "error") {
                        let alertaEnf = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertUpdateEnf').innerHTML = alertaEnf;
                        setTimeout(function (){
                            document.getElementById('alertUpdateEnf').innerHTML = "";
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
                    actualizacionModalEnf.hide();
                    }, 500);
                }
            }
        });
    }
})
	async function precargarDatosEnf(boton) {
		const id = boton.getAttribute('data-id');

		try {
			const respuesta = await fetch(`/admin/precargar-enf/${id}`);
			const datos = await respuesta.json();

			document.getElementById('nombreEnfUp').value = datos.descripcion;
			document.getElementById('tipEnfUp').value = datos.tipo;
            document.getElementById('enfermedadUp').value = datos.enfermedad_id;

		} catch (error) { 
			console.error("Error al pre-cargar datos:", error);
		} 
	}

