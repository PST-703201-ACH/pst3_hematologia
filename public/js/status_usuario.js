async function cambiarStatus(boton){	
	const id = boton.getAttribute('data-id');
	const actual = document.getElementById('status');
	try {
		const respuesta = await fetch(`/cambiar-status/${id}`);
		const nuevo = await respuesta.json();

		if (nuevo.status == "exito") {
			listarUsuarios();
		}

		} catch (error) { 
			console.error("Error al cambiar status:", error);
		}
}