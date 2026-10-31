document.addEventListener('DOMContentLoaded', function () {
    const formularioRegObs = document.getElementById('formRegObs');
    const registrandoModalObs = new bootstrap.Modal(document.getElementById('registrandoModalObs'));

    if (formularioRegObs) {
        formularioRegObs.addEventListener('submit', async function(obsReg) {
            const confirmacion = confirm("¿Está seguro de registrar esta observacion?");
            obsReg.preventDefault();
            if (confirmacion) {
                const datos = new FormData(formularioRegObs);
                registrandoModalObs.show();

                try {
                    console.log("URL detectada por JS:", formularioRegObs.action);

                    const respuesta = await fetch(formularioRegObs.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaObs = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertRegJor').innerHTML = alertaObs;
                        formularioRegObs.reset();
                        document.getElementById('medsObs').innerHTML = '';
                        listarJornada();
                        intercambiarVista('seccion-jornada');
                        setTimeout(function (){
                            document.getElementById('alertRegJor').innerHTML = "";
                        }, 3000);
                        
                    }else if(resultado.status === "error") {
                        let alertaObs = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertRegObs').innerHTML = alertaObs;
                        setTimeout(function (){
                            document.getElementById('alertRegObs').innerHTML = "";
                        }, 3000);

                    }else if (resultado.errores) {
                        for (let campo in resultado.errores) {
                        let elemento = document.getElementById(campo);
                            if (elemento) {
                            elemento.textContent = resultado.errores[campo]; 
                            elemento.style.pointerEvents = 'none';

                                setTimeout(function(){
                                  elemento.style.pointerEvents = '';
                                  elemento.textContent = "";
                                }, 3000);
                            }
                        }
                    }     
                } catch (error) {
                    console.error("Error:", error);
                } finally {
                    setTimeout(() => {
                    registrandoModalObs.hide();
                    }, 500);
                }
            }
        });
    }
})
async function precargarDatosTrat(boton) {
	const id = boton.getAttribute('data-id');

	try {
		const respuesta = await fetch(`/enf/precargar-trat/${id}`);
		const datos = await respuesta.json();

        document.getElementById('medsObs').innerHTML = '';

        const table = document.createElement('table');
        table.className = 'table table-striped table-hover';
        const thead = document.createElement('thead');;
        const trThead0 = document.createElement('tr');
        const thThead0 = document.createElement('th');
        thThead0.colSpan = '4';
        thThead0.textContent = 'Tratamiento';
        trThead0.appendChild(thThead0);
        thead.appendChild(trThead0);

        const trThead1 = document.createElement('tr');
        const thThead1 = document.createElement('th');
        thThead1.textContent = '#';
        const thThead2 = document.createElement('th');
        thThead2.textContent = 'Medicamento';
        const thThead3 = document.createElement('th');
        thThead3.textContent = 'Via de administracion';
        const thThead4 = document.createElement('th');
        thThead4.textContent = 'Cumplimiento';
        thThead4.colSpan = '2';
        trThead1.appendChild(thThead1);
        trThead1.appendChild(thThead2);
        trThead1.appendChild(thThead3);
        trThead1.appendChild(thThead4);
        thead.appendChild(trThead1);

        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        document.getElementById('medsObs').appendChild(table);

        let filas = '';
        let numMed = 1;
        let viaAdmin = '';

        let paciente = datos.medicina[0].paciente.persona.nombres+' '+datos.medicina[0].paciente.persona.apellidos;
        document.getElementById('paObs').textContent = paciente;
        document.querySelector('input[name="paObsReg"]').value = datos.medicina[0].paciente.paciente_id;
        document.querySelector('input[name="tratObsReg"]').value = datos.tratamiento_id;
        document.querySelector('input[name="usuObsReg"]').value = document.getElementById('usuario').textContent;

        datos.medicina.forEach(meds =>{
            if (meds.via_admin === 1) {
                viaAdmin = 'Intramuscular';
            } else if (meds.via_admin === 2) {
                viaAdmin = 'Indovenoso';
            } else if (meds.via_admin === 3) {
                viaAdmin = 'Oral';
            }

            filas += `
                <tr>
                    <td>${numMed++}</td>
                    <td>${meds.medicina.descripcion}</td>
                    <td>${viaAdmin}</td>
                    <td>
                        <div class="d-flex gap-5 justify-content-center">
                            
                            <div class="form-check mx-1">
                                <input class="form-check-input border-success" type="radio" name="medicinas[${meds.id}][status]" value="1">
                                <label class="form-check-label text-success">Sí</label>
                            </div>
                            
                            <div class="form-check mx-1">
                                <input class="form-check-input border-danger" type="radio" name="medicinas[${meds.id}][status]" value="0">
                                <label class="form-check-label text-danger">No</label>
                            </div>
                            
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = filas;
        table.appendChild(tbody);

    datos.medicina.forEach(meds =>{
        const opciones = document.querySelectorAll(`input[name="medicinas[${meds.id}][status]"]`);
        const inputOmision = document.getElementById('omiObsReg');

        opciones.forEach(opcion => {
            opcion.addEventListener('change', function(event) {
                const valor = event.target.value;
                
                const fila = event.target.closest('tr');
                if (!fila) return;

                const tdExistente = fila.querySelector('.td-omi');

                if (valor === "0") {

                    if (!tdExistente) {
                        const nuevoTd = document.createElement('td');
                        nuevoTd.className = 'td-omi';
                        
                        nuevoTd.innerHTML = `
                            <input type="text" 
                            name="medicinas[${meds.id}][omision]" 
                            class="form-control" 
                            placeholder="Motivo de omision" 
                            required>
                        `;
                        
                        fila.appendChild(nuevoTd);
                    }
                } else {

                    if (tdExistente) {
                        tdExistente.remove();
                    }
                }
            });
        });
    });
        




	} catch (error) { 
		console.error("Error al pre-cargar datos:", error);
	}
}