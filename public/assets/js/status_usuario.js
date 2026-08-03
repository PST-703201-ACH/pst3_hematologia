async function cambiarStatus(boton){	
	const id = boton.getAttribute('data-id');
	const fila = boton.closest('tr');
	const celdaStatus = fila.querySelector('.status');


	try {
		const respuesta = await fetch(`/cambiar-status/${id}`);
		const nuevo = await respuesta.json();

		if (nuevo.status == "exito") {
			celdaStatus.innerHTML = nuevo.contenido;
		}

		} catch (error) { 
			console.error("Error al cambiar status:", error);
		}
}