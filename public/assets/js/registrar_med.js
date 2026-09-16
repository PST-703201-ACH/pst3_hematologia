document.addEventListener('DOMContentLoaded', function() {
    const formularioMed = document.getElementById('formRegMed');
    const registroModal = new bootstrap.Modal(document.getElementById('registrandoModalMed'));

    if (formularioMed) {
        formularioMed.addEventListener('submit', async function(med) {
            const confirmacion = confirm("¿Está seguro de registrar esta nueva medicina?");
            med.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formularioMed);
                registroModal.show();

                try {
                    const respuesta = await fetch(formularioMed.action, {
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
                        document.getElementById('alertCreateMed').innerHTML = alertaMed;
                        listarMedicina();
                        setTimeout(function (){
                            document.getElementById('alertCreateMed').innerHTML = "";
                        }, 3000);

                        formularioMed.reset();
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
                        document.getElementById('alertCreateMed').innerHTML = alertaMed;
                        setTimeout(function (){
                            document.getElementById('alertCreateMed').innerHTML = "";
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
                    registroModal.hide();
                    }, 500);
                }
            }

        });
    }
});
