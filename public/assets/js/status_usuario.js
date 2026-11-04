async function cambiarStatus(boton){	
	const id = boton.getAttribute('data-id');
	const fila = boton.closest('tr');
	const celdaStatus = fila.querySelector('.status');

	const usuario = document.getElementById('usuario').textContent;

    let url = `/admin/cambiar-status/${id}`;
    
    if (usuario) {
        url += `?usuario=${encodeURIComponent(usuario)}`;
    }

	try {
		const respuesta = await fetch(url);
		const nuevo = await respuesta.json();

		if (nuevo.status == "exito") {
			celdaStatus.innerHTML = nuevo.contenido;
		}

		} catch (error) { 
			console.error("Error al cambiar status:", error);
		}
}