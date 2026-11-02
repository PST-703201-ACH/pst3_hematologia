async function cargarDetalleObs(boton){	
	const id = boton.getAttribute('data-id');
	const modalDetalle = document.getElementById('modalDetalleObs');
	try {
		const respuesta = await fetch(`/enf/obtener-detalles-obs/${id}`);
		const observacion = await respuesta.json();

		let gravedad = '';
		let fecha = observacion[0].fecha_hora;
		let enfermedad = observacion[0].tratamiento.medicina[0].consulta.enfermedad.descripcion;
		let descripcion = observacion[0].descripcion;
		let acciones = observacion[0].acciones;		
		let nombrePa = observacion[0].paciente.persona.nombres+' '+observacion[0].paciente.persona.apellidos;
		let cedulaPa = observacion[0].paciente.persona.cedula;
		let hcPa = observacion[0].paciente.hc;
		let filasMed = '';
		let numMed = 1;
		let via_admin = '';
		let omision = '';

		if (observacion[0].gravedad === 0){
			gravedad = '<p class="text-success">Baja</p>';
		}
		else if (observacion[0].gravedad === 1){
			gravedad = '<p class="text-warning">Media</p>';
		}
		else if (observacion[0].gravedad === 2){
			gravedad = '<p class="text-danger">Alta</p>';
		}

		document.getElementById('detalleObsFecha').textContent = fecha;
		document.getElementById('detalleObsGra').innerHTML = gravedad;
		document.getElementById('detalleObsEnf').textContent = enfermedad;
		document.getElementById('detalleObsDescrip').textContent = descripcion;
		document.getElementById('detalleObsAcc').textContent = acciones;
		document.getElementById('detalleObsPaHc').textContent = hcPa;
		document.getElementById('detalleObsPaNombre').textContent = nombrePa;
		document.getElementById('detalleObsPaCedula').textContent = cedulaPa;

		observacion[0].tratamiento.medicina.forEach(meds =>{

			if (meds.via_admin === 1) {
				via_admin = 'Intramuscular';
			} else if (meds.via_admin === 2) {
				via_admin = 'Indovenoso';
			} else if (meds.via_admin === 3) {
				via_admin = 'Oral';
			}

			if (meds.status === 0) {
				if (meds.omision ) {}
				omision = meds.omision;
				cumplimiento = `<p class="text-danger">Omitido</p>${omision}<p></p>`;
			} else if (meds.status === 1) {
				cumplimiento = '<p class="text-success">Cumplido</p>';
			}

			filasMed += `
				<tr>
					<td>${numMed++}</td>
					<td>${meds.medicina.descripcion}</td>
					<td>${via_admin}</td>
					<td>${cumplimiento}</td>
				<t/r>
			`;
		});

		document.getElementById('cuerpoDetalleObsTrat').innerHTML = filasMed;



		const info = new bootstrap.Modal(modalDetalle);
		info.show();
	} catch (error) { 
		console.error("Error al cargar datos:", error);
	}
}