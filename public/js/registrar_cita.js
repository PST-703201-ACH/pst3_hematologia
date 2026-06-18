document.addEventListener('DOMContentLoaded', function() {

    const formulario = document.getElementById('formReg');
    const citaModal = new bootstrap.Modal(document.getElementById('registrandoCita'));

    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            const confirmacion = confirm("¿Está seguro de agendar esta nueva cita?");
            e.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formulario);
                citaModal.show();

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
                        let alertaCita = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertaRegCita').innerHTML = alertaCita;
                        
                        setTimeout(function (){
                            document.getElementById('alertaRegCita').innerHTML = "";
                        }, 3000);

                        formulario.reset();
                    }else if(resultado.status === "error") {
                        let alertaCita = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertaRegCita').innerHTML = alertaCita;
                        setTimeout(function (){
                            document.getElementById('alertaRegCita').innerHTML = "";
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
                    citaModal.hide();
                    }, 500);
                }
            }

        });
    }
});