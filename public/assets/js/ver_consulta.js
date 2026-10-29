async function cargarDetalleCon(boton){	
	const id = boton.getAttribute('data-id');
	const modalDetalle = document.getElementById('modalDetalleCon');
	try {
		const respuesta = await fetch(`/medico/obtener-detalles-consulta/${id}`);
		const detalle = await respuesta.json();

		let tipo = '';
		let nombrePa = detalle.consulta.paciente.persona.nombres+' '+detalle.consulta.paciente.persona.apellidos;
		let cedulaPa = detalle.consulta.paciente.persona.cedula;

		if (detalle.consulta.tipo === 1){
			tipo = 'Primera consulta';
		}
		else if (detalle.consulta.tipo === 2){
			tipo = 'Consulta de control';
		}

		document.getElementById('detalleConFecha').textContent = detalle.fecha_hora;
		document.getElementById('detalleConTipo').textContent = tipo;
		document.getElementById('detalleConEnf').textContent = detalle.consulta.enfermedad.descripcion;
		document.getElementById('detalleConPeso').textContent = detalle.consulta.peso;
		document.getElementById('detalleConTalla').textContent = detalle.consulta.talla;
		document.getElementById('detalleConSc').textContent = detalle.consulta.sc;
		document.getElementById('detalleConFc').textContent = detalle.consulta.fc;
		document.getElementById('detalleConFr').textContent = detalle.consulta.fr;
		document.getElementById('detalleConAntePerso').textContent = detalle.consulta.ante_personal;
		document.getElementById('detalleConAnteFami').textContent = detalle.consulta.ante_familiar;
		document.getElementById('detalleConSintomas').textContent = detalle.consulta.sintomas;
		document.getElementById('detalleConSubj').textContent = detalle.consulta.subjetivo;
		document.getElementById('detalleConPlan').textContent = detalle.consulta.plan_trabajo;
		document.getElementById('detalleConProx').textContent = detalle.consulta.proxima_cita;
		document.getElementById('detalleConPaHc').textContent = detalle.consulta.paciente.hc;
		document.getElementById('detalleConPaNombre').textContent = nombrePa;
		document.getElementById('detalleConPaCedula').textContent = cedulaPa;

			const info = new bootstrap.Modal(modalDetalle);
			info.show();
		} catch (error) { 
			console.error("Error al cargar datos:", error);
		}
}