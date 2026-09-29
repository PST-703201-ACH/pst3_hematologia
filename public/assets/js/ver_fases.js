async function verFases(boton){	
	const id = boton.getAttribute('data-id');
	const modalFases = document.getElementById('modalFases');
	try {
		const respuesta = await fetch(`/admin/obtener-protocolo-fases/${id}`);
		const protocolo = await respuesta.json();

		var fasesAgrupadas = {};
		for (var p of protocolo) {
		    
		    var n = p.fase.numero;
		    if (!fasesAgrupadas[n]) {
		        fasesAgrupadas[n] = []; 
		    }
		    fasesAgrupadas[n].push(p);
		}

		var fases = ""; 
		var idMed = 1;

		for (var nFase in fasesAgrupadas) {
		    
		    var tablaFase = fasesAgrupadas[nFase];

		    fases += `
		        <table class="table table-striped">
		            <thead>
		              <tr>
		                <th colspan="2">Fase N°${nFase}</th>
		              </tr>
		              <tr>
		                <th>#</th>
		                <th>Medicamento</th>
		              </tr>
		            </thead>
		            <tbody>
		    `;

		    for (var i = 0; i < tablaFase.length; i++) {
		        var prot = tablaFase[i];

		        fases += `
		              <tr>
		                <td>${idMed++}</td>
		                <td>${prot.medicina.descripcion}</td>
		              </tr>
		        `;
		    }

		    fases += `
		            </tbody>
		        </table>
		    `;
		}

		document.getElementById('contenedor-fases-protocolo').innerHTML = fases;
		
		const info = new bootstrap.Modal(modalFases);
		info.show();
	} catch (error) { 
		console.error("Error al cargar datos:", error);
	}
}
