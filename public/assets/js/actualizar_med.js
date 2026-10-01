document.addEventListener('DOMContentLoaded', function () {
    const formularioUpMed = document.getElementById('formUpMed');
    const actualizacionModalMed = new bootstrap.Modal(document.getElementById('actualizandoModalMed'));

    if (formularioUpMed) {
        formularioUpMed.addEventListener('submit', async function(medUp) {
            const confirmacion = confirm("¿Está seguro de actualizar los datos de esta medicina?");
            medUp.preventDefault();
            if (confirmacion) {
                const datos = new FormData(formularioUpMed);
                actualizacionModalMed.show();

                try {
                    const respuesta = await fetch(formularioUpMed.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaMed = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertUpdateMed').innerHTML = alertaMed;
                        listarMedicina();
                        setTimeout(function (){
                            document.getElementById('alertUpdateMed').innerHTML = "";
                        }, 3000);
                        
                    }else if(resultado.status === "error") {
                        let alertaMed = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertUpdateMed').innerHTML = alertaMed;
                        setTimeout(function (){
                            document.getElementById('alertUpdateMed').innerHTML = "";
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
                    actualizacionModalMed.hide();
                    }, 500);
                }
            }
        });
    }
})

async function precargarDatosMed(boton) {
	const id = boton.getAttribute('data-id');

	try {
		const respuesta = await fetch(`/admin/precargar-med/${id}`);
		const datos = await respuesta.json();

		document.getElementById('nombreMedUp').value = datos.descripcion;
        document.getElementById('medicinaUp').value = datos.medicina_id;

	} catch (error) { 
		console.error("Error al pre-cargar datos:", error);
	} 
}