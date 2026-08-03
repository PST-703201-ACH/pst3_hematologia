document.addEventListener('DOMContentLoaded', function() {

    let botonClave = document.getElementById('btnClave');
    let botonNueva = document.getElementById('btnNueva');
    const formLogin = document.getElementById('formLogin');
    const avisoCedula = document.getElementById('cedula');
    const avisoClave = document.getElementById('clave');
    const nuevaClave = document.getElementById('camClave');
    const alertaLogin = document.getElementById('alertLogin');
    const formClave = document.getElementById('formClave')
    const cargandoModal = new bootstrap.Modal(document.getElementById('modalCarga'));

    botonClave.addEventListener('click', function() {
        if (this.className == "btn btn-secondary fas fa-eye-slash") {
            this.className = "btn btn-secondary fas fa-eye";
            avisoClave.type = "text";
        } else if (this.className == "btn btn-secondary fas fa-eye") {
            this.className = "btn btn-secondary fas fa-eye-slash";
            avisoClave.type = "password";
        }
    });

    botonNueva.addEventListener('click', function() {
        if (this.className == "btn btn-secondary fas fa-eye-slash") {
            this.className = "btn btn-secondary fas fa-eye";
            nuevaClave.type = "text";
        } else if (this.className == "btn btn-secondary fas fa-eye") {
            this.className = "btn btn-secondary fas fa-eye-slash";
            nuevaClave.type = "password";
        }
    });

    formLogin.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(formLogin);

        try {
            cargandoModal.show();
            const respuesta = await fetch('/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const resultado = await respuesta.json();

            if (respuesta.ok) {
                window.location.href = resultado.redirect;
                return;
            }

            if (resultado.status === "errores") {
                for (let campo in resultado.errores) {
                const elemento = document.getElementById(campo);
                    if (elemento) {
                    const originalValue = elemento.value;
                    const originalType = elemento.type;
                    elemento.className = 'form-control border border-danger text-danger';
                    elemento.type = "text";
                    elemento.value = resultado.errores[campo]; 
                    elemento.style.pointerEvents = 'none';

                        setTimeout(function(){
                          elemento.className = 'form-control';
                          elemento.style.pointerEvents = '';
                          elemento.value = originalValue;
                          elemento.type = originalType;
                        }, 3000);
                    }
                }
            } else if (resultado.status === "error"){
                alertaLogin.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-xmark"></i> 
                    ${resultado.mensaje}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            } else if (resultado.status === "verificar"){
                $('#cambioClave').modal('show');
                document.getElementById('camCedula').value = avisoCedula.value;
                formClave.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const confirmCam = confirm("¿Está seguro de actualizar la contraseña a la nueva ingresada?");
                    
                    if (!confirmCam) {
                        return;
                    }

                    let dataClave = new FormData(formClave);

                    camClave(dataClave);

                    async function camClave(datosForm) {
                        try{
                            const traductor = new URLSearchParams(datosForm);

                            const textoUrl = traductor.toString();

                            const urlFinal = "/nueva-clave?" + textoUrl;

                        
                            cargandoModal.show();
                            const respuesta = await fetch(urlFinal);
                            const resultado = await respuesta.json();

                            if (resultado.status === "errores") {
                                for (let campo in resultado.errores) {
                                const elemento = document.getElementById(campo);
                                    if (elemento) {
                                    let originalValue = elemento.value;
                                    let originalType = elemento.type;
                                    elemento.className = 'form-control border border-danger text-danger';
                                    elemento.type = "text";
                                    elemento.value = resultado.errores[campo]; 
                                    elemento.style.pointerEvents = 'none';

                                        setTimeout(function(){
                                          elemento.className = 'form-control';
                                          elemento.style.pointerEvents = '';
                                          elemento.value = originalValue;
                                          elemento.type = originalType;
                                        }, 3000);
                                    }
                                }
                            }

                            if (resultado.status === "exito") {
                                $('#cambioClave').modal('hide');
                                formClave.reset();
                                avisoClave.value = "";
                                alertaLogin.innerHTML = `
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="icon fas fa-xmark"></i> 
                                        ${resultado.mensaje}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                `;
                                setTimeout(function(){
                                    alertaLogin.innerHTML = "";
                                }, 3000);
                            }
                        } catch (error) {
                            console.error("Error al cambiar la contraseña:", error);
                        } finally {
                            setTimeout(() => {
                                cargandoModal.hide();
                            }, 500);                
                        }
                    }
                });
            }

        } catch (error) {
            console.error("Error en la petición de login:", error);
        } finally {
            setTimeout(() => {
                cargandoModal.hide();
            }, 500);
        }
    });

    const botonOl = document.getElementById('olBtn');
    const formOl = document.getElementById('formOlvido');
    const avisoOlvido = document.getElementById('olEmail');
    const campoCedula = document.getElementById('olCedula');

    botonOl.addEventListener('click', function(){
        $('#nuevaClave').modal('show');
    });

    formOl.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const confirmacion = confirm("¿Está seguro de que quiere recuperar la contraseña?");
        
        if (!confirmacion) {
            return;
        }

        const formData = new FormData(formOl);
        const username = formData.get('olClave');

        if (!username || username.trim() === "") {
            let valorOriginal = campoCedula.value;
            let tipoOriginal = campoCedula.type;
            campoCedula.className = "form-control border border-danger text-danger";
            campoCedula.style.pointerEvents = 'none';
            campoCedula.value = "Por favor, ingrese su cédula";

            setTimeout(function() {
                campoCedula.className = "form-control";
                campoCedula.style.pointerEvents = '';
                campoCedula.value = valorOriginal;
                campoCedula.type = tipoOriginal;    
            }, 3000);

            return;
        }

        consultarUsuario(username);
    });

    async function consultarUsuario(username) {
        try {
            cargandoModal.show();
            const respuesta = await fetch(`/olvide-clave/${username}`);
            const clave = await respuesta.json();

            if (clave.status == "exito"){
                avisoOlvido.textContent = clave.aviso;
            }
            if (clave.status == "alerta") {
                let valorOriginal = campoCedula.value;
                let tipoOriginal = campoCedula.type;
                campoCedula.className = "form-control border border-danger text-danger";
                campoCedula.style.pointerEvents = 'none';
                campoCedula.value = clave.aviso;
                setTimeout(function() {
                    campoCedula.className = "form-control";
                    campoCedula.style.pointerEvents = '';
                    campoCedula.value = valorOriginal;
                    campoCedula.type = tipoOriginal;    
                }, 3000);
            }

        } catch (error) {
            console.error("Error en JS:", error);
            avisoOlvido.textContent = "Error al conectar con el servidor.";
        } finally {
            setTimeout(() => {
                cargandoModal.hide();
            }, 500);
        }
    }
});
