document.addEventListener('DOMContentLoaded', function() {
	const botonOl = document.getElementById('olBtn');
	botonOl.addEventListener('click', function(){
		$('#nuevaClave').modal('show');
		const formOl = document.getElementById('modalOlvido');
		formOl.addEventListener('submit', function(e) {
			e.preventDefault();
			async function consultarUsuario() {
				const avisoOlvido = document.getElementById('olEmail');
				const formData = new FormData(event.target);

				const username = formData.get('olClave');

				try {
					const respuesta = await fetch(`/obtener-clave${username}`);
            		const clave = await respuesta.json();

            		if(clave.status === "exito") {
                        let alertaUsu = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${clave.aviso}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        avisoOlvido.innerHTML = alertaUsu;
                    }

		            clave.forEach(info => {
		                
		            });
		        } catch (error) {
		            console.error("Error:", error);
		        }
			}
		})
	});
});