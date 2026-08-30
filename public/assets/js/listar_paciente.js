document.addEventListener('DOMContentLoaded', () => {
	
});

async function listarPacientes() {
		try {
			const respuesta = await fetch('medico/obtener-pacientes');
			const pacientes = await respuesta.json();

			let filas = '';
			let id = 1;

			pacientes.forEach(pa => {
				filas += `
					<tr>
						<td>${id++}</td>
						<td>${pa.hc}</td>
						<td>${pa.persona.cedula}</td>
						<td>${pa.persona.nombres} ${pa.persona.apellidos}</td>
						<td>${pa.persona.sexo}</td>
						<td>${pa.status}</td>
						<td>ACA VAN LOS BOTONES</td>
					</tr>
				`;
			});
			document.getElementById('cuerpoTablaPacientes').innerHTML = filas;

		} catch (error) {
			console.error("Error al listar:", error);
		}
	}