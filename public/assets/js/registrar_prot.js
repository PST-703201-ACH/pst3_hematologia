document.addEventListener('DOMContentLoaded', function() {
    const formularioProt = document.getElementById('formRegProt');
    const registroModal = new bootstrap.Modal(document.getElementById('registrandoModalProt'));

    if (formularioProt) {
        formularioProt.addEventListener('submit', async function(prot) {
            const confirmacion = confirm("¿Está seguro de registrar este nuevo protocolo?");
            prot.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formularioProt);
                registroModal.show();

                try {
                    const respuesta = await fetch(formularioProt.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaProt = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreateProt').innerHTML = alertaProt;
                        listarProtocolo();
                        setTimeout(function (){
                            document.getElementById('alertCreateProt').innerHTML = "";
                        }, 3000);

                        formularioProt.reset();
                    }else if(resultado.status === "error") {
                        let alertaProt = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreateProt').innerHTML = alertaProt;
                        setTimeout(function (){
                            document.getElementById('alertCreateProt').innerHTML = "";
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