document.addEventListener('DOMContentLoaded', () => {
	const cuerpoTabla = document.querySelector('#tabla-pacientes tbody');

	if (cuerpoTabla && cuerpoTabla.children.length === 0) {
		listarPacientes();
	}
});

async function abrirDetallePaciente(pacienteId) {
	const contenedor = document.getElementById('contenedor-paciente-detalle');

	if (!contenedor || !pacienteId) {
		return;
	}

	try {
		const respuesta = await fetch(`/medico/pacientes/${pacienteId}`, {
			headers: { 'X-Requested-With': 'XMLHttpRequest' }
		});

		if (!respuesta.ok) {
			throw new Error(`HTTP ${respuesta.status}`);
		}

		contenedor.innerHTML = await respuesta.text();
		intercambiarVista('seccion-paciente_detalle');
	} catch (error) {
		console.error('Error al cargar la información del paciente:', error);
	}
}

function volverAlListadoPacientes() {
	const contenedor = document.getElementById('contenedor-paciente-detalle');

	if (contenedor) {
		contenedor.innerHTML = '';
	}

	intercambiarVista('seccion-pacientes_listados');
}

document.addEventListener('click', function (evento) {
	const botonVer = evento.target.closest('.js-ver-paciente');
	const botonVolver = evento.target.closest('.js-volver-pacientes');

	if (botonVer) {
		evento.preventDefault();
		abrirDetallePaciente(botonVer.dataset.pacienteId);
	}

	if (botonVolver) {
		evento.preventDefault();
		volverAlListadoPacientes();
	}
});

async function listarPacientes() {
		try {
			const respuesta = await fetch('/medico/obtener-pacientes');
			if (!respuesta.ok) {
				throw new Error(`HTTP ${respuesta.status}`);
			}
			const pacientes = await respuesta.json();

			let filas = '';

			pacientes.forEach(pa => {
				filas += `
					<tr>
						<td>${pa.hc}</td>
						<td>${pa.persona.cedula}</td>
						<td>${pa.persona.nombres} ${pa.persona.apellidos}</td>
						<td>${pa.persona.sexo}</td>
						<td><span class="badge ${Number(pa.status) === 1 ? 'badge-success' : 'badge-danger'}">${Number(pa.status) === 1 ? 'Activo' : 'Inactivo'}</span></td>
						<td>
							<a href="/medico/pacientes/${pa.paciente_id}" class="btn btn-primary btn-sm js-ver-paciente" data-paciente-id="${pa.paciente_id}" title="Ver Información">
								<i class="fas fa-eye"></i>
							</a>
						</td>
					</tr>
				`;
			});
			const cuerpoTabla = document.querySelector('#tabla-pacientes tbody');
			if (cuerpoTabla) {
				cuerpoTabla.innerHTML = filas;
			}

		} catch (error) {
			console.error("Error al listar:", error);
		}
	}