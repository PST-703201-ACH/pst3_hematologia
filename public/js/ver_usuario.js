async function cargarDetalle(boton){	
	const id = boton.getAttribute('data-id');
	const modalDetalle = document.getElementById('modalDetalle');
	try {
		const respuesta = await fetch(`/obtener-detalles/${id}`);
		const detalle = await respuesta.json();
		if (detalle.status == 1) {
			detalle.status = '<p class="text-success">Activo</p>';
		}

			document.getElementById('detalleStatus').innerHTML = detalle.status;
			document.getElementById('detalleNombre').innerHTML = detalle.persona.nombres+' '+detalle.persona.apellidos;
			document.getElementById('detalleCedula').innerHTML = detalle.username;
			document.getElementById('detalleCorreo').innerHTML = detalle.persona.email;
			document.getElementById('detalleTelefono').innerHTML = detalle.persona.telefono;
			document.getElementById('detalleDireccion').innerHTML = detalle.persona.direccion_exacta;
			document.getElementById('detalleEstado').innerHTML = detalle.persona.estado.nombre;
			document.getElementById('detalleMunicipio').innerHTML = detalle.persona.municipio.nombre;
			document.getElementById('detalleParroquia').innerHTML = detalle.persona.parroquia.nombre;

			const info = new bootstrap.Modal(modalDetalle);
			info.show();
		} catch (error) { 
			console.error("Error al cargar datos:", error);
		}
}
