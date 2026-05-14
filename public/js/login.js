document.addEventListener('DOMContentLoaded', function() {
	const botonOl = document.getElementById('olBtn');
	botonOl.addEventListener('click', function(){
		$('#nuevaClave').modal('show');
		const formOl = document.getElementById('modalOlvido');
		formOl.addEventListener('submit', function() {
			async function consultarUsuario() {
				const avisoOlvido = document.getElementById('olEmail');

				try {
					const respuesta = await fetch('/obtener-clave');
            		const clave = await respuesta.json();

		            clave.forEach(info => {
		                
		            });
		        } catch (error) {
		            console.error("Error:", error);
		        }
			}
		})
	});
});