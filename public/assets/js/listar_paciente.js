document.addEventListener('DOMContentLoaded', () => {
	const cuerpoTabla = document.querySelector('#tabla-pacientes tbody');

	if (cuerpoTabla && cuerpoTabla.children.length === 0) {
		listarPacientes();
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
						<td>${pa.status}</td>
						<td></td>
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