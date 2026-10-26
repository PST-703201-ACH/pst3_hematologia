document.addEventListener('DOMContentLoaded', function() {
    async function cargarParroquias() {
        const select = document.getElementById('parroquiaPaConReg');

        try {
            const respuesta = await fetch('/obtener-parroquias');
            const parroquias = await respuesta.json();


            parroquias.forEach(parroquia => {
                const option = document.createElement('option');
                option.value = parroquia.parroquia_id;
                option.textContent = parroquia.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar parroquias:", error);
        }
    }

    async function cargarMunicipios() {
        const select = document.getElementById('muniPaConReg');

        try {
            const respuesta = await fetch('/obtener-municipios');
            const municipios = await respuesta.json();


            municipios.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.municipio_id;
                option.textContent = municipio.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar municipios:", error);
        }
    }

    async function cargarEstados() {
        const select = document.getElementById('estadoPaConReg');

        try {
            const respuesta = await fetch('/obtener-estados');
            const estados = await respuesta.json();


            estados.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.estado_id;
                option.textContent = estado.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar estados:", error);
        }
    }

    cargarEstados();
    cargarMunicipios();
    cargarParroquias();

    const formularioCon = document.getElementById('formRegCon');
    const registroModal = new bootstrap.Modal(document.getElementById('registrandoModalCon'));

    if (formularioCon) {
        formularioCon.addEventListener('submit', async function(con) {
            const confirmacion = confirm("¿Está seguro de registrar esta consulta?");
            con.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formularioCon);
                registroModal.show();

                try {
                    const respuesta = await fetch(formularioCon.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaCon = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreateCon').innerHTML = alertaCon;
                        listarConsultaP();
                        setTimeout(function (){
                            document.getElementById('alertCreateCon').innerHTML = "";
                        }, 3000);

                        formularioCon.reset();
                    }else if(resultado.status === "error") {
                        let alertaCon = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertCreateCon').innerHTML = alertaCon;
                        setTimeout(function (){
                            document.getElementById('alertCreateCon').innerHTML = "";
                        }, 3000);

                    }else if (resultado.status === "errores") {
                        for (let campo in resultado.errores) {
                        const elemento = document.getElementById(campo);
                            if (elemento) {
                            const originalValue = elemento.value;
                            elemento.className = 'form-control border border-danger text-danger';
                            elemento.value = resultado.errores[campo]; 
                            elemento.style.pointerEvents = 'none';

                                setTimeout(function(){
                                  elemento.className = 'form-control';
                                  elemento.style.pointerEvents = '';
                                  elemento.value = originalValue;
                                }, 3000);
                            }
                        }
                    }     
                } catch (error) {
                    console.error("Error:", error);
                } finally {
                    setTimeout(() => {
                    registroModal.hide();
                    }, 500);
                }
            }
        });
    }
});

async function precargarDatosCon(boton) {
    document.getElementById('formRegCon').reset();
    const id = boton.getAttribute('data-id');

    try {
        var respuesta = await fetch(`/medico/obtener-enfermedades`);
        const datosEnf = await respuesta.json();
        const selectEnf = document.getElementById('enfConReg');
        selectEnf.innerHTML = '';
        selectEnf.innerHTML = '<option value="" disabled selected>Seleccione la enfermedad</option>';

        datosEnf.forEach(enf => {
            const option = document.createElement('option');
            option.value = enf.enfermedad_id;
            option.textContent = enf.descripcion;
            selectEnf.appendChild(option);
        });

        respuesta = await fetch(`/medico/precargar-con/${id}`);
        const datosCon = await respuesta.json();

        let enfCon = datosCon.enfermedad_id;
        let nombresPa = datosCon.consulta.paciente.persona.nombres;
        let apellidosPa = datosCon.consulta.paciente.persona.apellidos;

        let paciente = nombresPa+' '+apellidosPa;
        let representante = datosCon.nombres_representante+' '+datosCon.apellidos_representante;
        let hc = datosCon.consulta.paciente.hc;
        let nombrePa = paciente.split(" ");
        let cedula = datosCon.consulta.paciente.persona.cedula;
        let cedulaPa = cedula.split("-");
        let naciPa = datosCon.consulta.paciente.persona.fecha_nacimiento;
        let sexoPa = datosCon.consulta.paciente.persona.sexo;
        let emailPa = datosCon.consulta.paciente.persona.email;
        let telfPa = datosCon.consulta.paciente.persona.telefono;
        let dirPa = datosCon.consulta.paciente.persona.direccion_exacta;
        let estPa = datosCon.consulta.paciente.persona.estado_id;
        let muniPa = datosCon.consulta.paciente.persona.municipio_id;
        let parroquiaPa = datosCon.consulta.paciente.persona.parroquia_id;

        let tipo = '';
        let boton1 = document.createElement('button');
        let boton2 = document.createElement('button');
        let boton3 = document.createElement('button');
        let boton4 = document.createElement('button');
        const seccion1 = document.getElementById('seccion-consulta-registrar1');
        const seccion2 = document.getElementById('seccion-consulta-registrar2');
        const seccion3 = document.getElementById('seccion-consulta-registrar3');
        const seccion4 = document.getElementById('seccion-consulta-registrar4');

        boton1.className = 'btn btn-outline-primary m-1';
        boton1.textContent = '1';
        boton1.type = 'button';
        boton2.className = 'btn btn-outline-primary m-1';
        boton2.textContent = '2';
        boton2.type = 'button';
        boton3.className = 'btn btn-outline-primary m-1';
        boton3.textContent = '3';
        boton3.type = 'button';
        boton4.className = 'btn btn-outline-primary m-1';
        boton4.textContent = '4';
        boton4.type = 'button';
        
        if (datosCon.consulta.tipo == 1){
            tipo = 'Primera consulta';

            document.getElementById('btnPagsConReg').innerHTML = '';
            document.getElementById('btnPagsConReg').appendChild(boton1);
            document.getElementById('btnPagsConReg').appendChild(boton2);
            document.getElementById('btnPagsConReg').appendChild(boton3);

            actualizarBotones();

            boton1.addEventListener('click', function (boton) {
                seccion3.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion1.classList.remove('d-none');
                actualizarBotones();
            });

            boton2.addEventListener('click', function (boton) {
                seccion1.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion2.classList.remove('d-none');
                actualizarBotones();
            });

            boton3.addEventListener('click', function (boton) {
                seccion1.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion3.classList.remove('d-none');
                actualizarBotones();
            });

            function actualizarBotones() {
                if (seccion1.classList.contains('d-none')) {
                    boton1.className = 'btn btn-outline-primary m-1';
                } else {
                    boton1.className = 'btn btn-primary m-1';
                }

                if (seccion2.classList.contains('d-none')) {
                    boton2.className = 'btn btn-outline-primary m-1';
                } else {
                    boton2.className = 'btn btn-primary m-1';
                }

                if (seccion3.classList.contains('d-none')) {
                    boton3.className = 'btn btn-outline-primary m-1';
                } else {
                    boton3.className = 'btn btn-primary m-1';
                }
            }

            document.getElementById('nombrePaConReg').textContent = paciente;
            document.getElementById('nombreRepConReg').textContent = representante;
            document.getElementById('hcConReg').textContent = hc;
            document.getElementById('tipoConReg').textContent = tipo;
            document.getElementById('consultaReg').value = datosCon.consulta.consulta_id;
            document.getElementById('usuConReg').value = document.getElementById('usuario').textContent;
            document.getElementById('nombrePa1ConReg').value = nombrePa[0];
            document.getElementById('nombrePa2ConReg').value = nombrePa[1];
            document.getElementById('apellidoPa1ConReg').value = nombrePa[2];
            document.getElementById('apellidoPa2ConReg').value = nombrePa[3];
            document.getElementById('hcPaConReg').value = hc;
            document.getElementById('nacPaConReg').value = cedulaPa[0];
            document.getElementById('cedulaPaConReg').value = cedulaPa[1];


        }else if (datosCon.consulta.tipo == 2){
            tipo = 'Consulta de control';
            document.getElementById('formRegCon').action = 'medico.consultas.registrar2';

            document.getElementById('btnPagsConReg').innerHTML = '';
            document.getElementById('btnPagsConReg').appendChild(boton1);
            document.getElementById('btnPagsConReg').appendChild(boton2);
            document.getElementById('btnPagsConReg').appendChild(boton3);
            document.getElementById('btnPagsConReg').appendChild(boton4);

            boton1.addEventListener('click', function () {
                seccion4.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion1.classList.remove('d-none');
                actualizarBotones();
            });

            boton2.addEventListener('click', function () {
                seccion1.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion4.classList.add('d-none');
                seccion2.classList.remove('d-none');
                actualizarBotones();
            });

            boton3.addEventListener('click', function () {
                seccion1.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion4.classList.add('d-none');
                seccion3.classList.remove('d-none');
                actualizarBotones();
            });

            boton4.addEventListener('click', function () {
                seccion1.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion4.classList.remove('d-none');
                actualizarBotones();
            });


            function actualizarBotones() {
                if (seccion1.classList.contains('d-none')) {
                    boton1.className = 'btn btn-outline-primary m-1';
                } else {
                    boton1.className = 'btn btn-primary m-1';
                }

                if (seccion2.classList.contains('d-none')) {
                    boton2.className = 'btn btn-outline-primary m-1';
                } else {
                    boton2.className = 'btn btn-primary m-1';
                }

                if (seccion3.classList.contains('d-none')) {
                    boton3.className = 'btn btn-outline-primary m-1';
                } else {
                    boton3.className = 'btn btn-primary m-1';
                }

                if (seccion4.classList.contains('d-none')) {
                    boton4.className = 'btn btn-outline-primary m-1';
                } else {
                    boton4.className = 'btn btn-primary m-1';
                }
            }

            document.getElementById('enfConReg').value = enfCon;
            document.getElementById('antePersoConReg').value = datosCon.consulta.ante_personal;
            document.getElementById('anteFamiConReg').value = datosCon.consulta.ante_familiar;
            document.getElementById('signosConReg').value = datosCon.consulta.sintomas;

            document.getElementById('nombrePaConReg').textContent = paciente;
            document.getElementById('nombreRepConReg').textContent = representante;
            document.getElementById('hcConReg').textContent = hc;
            document.getElementById('tipoConReg').textContent = tipo;
            document.getElementById('consultaReg').value = datosCon.consulta.consulta_id;
            document.getElementById('usuConReg').value = document.getElementById('usuario').textContent;
            document.getElementById('nombrePa1ConReg').value = nombrePa[0];
            document.getElementById('nombrePa2ConReg').value = nombrePa[1];
            document.getElementById('apellidoPa1ConReg').value = nombrePa[2];
            document.getElementById('apellidoPa2ConReg').value = nombrePa[3];
            document.getElementById('hcPaConReg').value = hc;
            document.getElementById('naciPaConReg').value = naciPa;
            document.getElementById('sexoPaConReg').value = sexoPa;
            document.getElementById('emailPaConReg').value = emailPa;
            document.getElementById('tlfPaConReg').value = telfPa;
            document.getElementById('dirPaConReg').value = dirPa;
            document.getElementById('estadoPaConReg').value = estPa;
            document.getElementById('muniPaConReg').value = muniPa;
            document.getElementById('parroquiaPaConReg').value = parroquiaPa;
            document.getElementById('nacPaConReg').value = cedulaPa[0];
            document.getElementById('cedulaPaConReg').value = cedulaPa[1];
        }

    } catch (error) { 
        console.error("Error al pre-cargar datos:", error);
    }
}


// Precargar protocolos
let protocolos = [];
async function precargarProts() {
    try {
        const respuesta = await fetch('/medico/obtener-protocolos');
        
        protocolos = await respuesta.json(); 
    } catch (error) {
        console.error("Error al cargar:", error);
    }
}

precargarProts();

const protocolosSeleccionables = [...protocolos];

function asignarProt() {
    // Creacion de tabla
    const contenedorTabla = document.getElementById('medsTrat');

    const tablaProt = document.createElement('table');
    tablaProt.className = 'table table-striped';

    // Creacion de encabezado
    const theadTabla = document.createElement('thead');

    // Primera fila del encabezado
    const trProt0 = document.createElement('tr');
    const thProt0 = document.createElement('th');
    thProt0.colSpan = 3;
    const selectProt = document.createElement('select');








    selectProt.className = 'form-control seleccion-prot';
    selectProt.innerHTML = '<option value="" disabled selected>Seleccione un protocolo</option>';

    thProt0.appendChild(selectProt);


    const thProt1 = document.createElement('th');
    const btnEliminarProt = document.createElement('button');
    btnEliminarProt.type = "button";
    btnEliminarProt.className = "btn btn-danger elimProt";
    const iconoBoton0 = document.createElement('i');
    iconoBoton0.className = "fas fa-solid fa-minus";
    btnEliminarProt.appendChild(iconoBoton0);
    thProt1.appendChild(btnEliminarProt);

    // Boton para eliminar un protocolo
    btnEliminarProt.addEventListener('click', function() {
        const protocolo = btnEliminarProt.closest('table');
        if (protocolo) {
            protocolo.remove();
            if (typeof enumerarFases === 'function') {
                enumerarFases();
            }
        }
        return;
    });
    
    trProt0.appendChild(thProt0);
    trProt0.appendChild(thProt1);

    const tbodyProt = document.createElement('tbody');

    selectProt.addEventListener('change', () => {
        selectProt.disabled = true;

        const id = selectProt.value;

        async function precargarProts() {
            const respuesta = await fetch(`/medico/obtener-medicinasProt/${id}`);
            let medicinas = await respuesta.json();

            const trProt1 = document.createElement('tr');
            const thProt2 = document.createElement('th');
            thProt2.textContent = '#';
            const thProt3 = document.createElement('th');
            thProt3.textContent = 'Medicina';
            const thProt4 = document.createElement('th');
            thProt4.textContent = 'Via de administracion';
            trProt1.appendChild(thProt2);
            trProt1.appendChild(thProt3);
            trProt1.appendChild(thProt4);
            theadTabla.appendChild(trProt1);

            medicinas.forEach(med => {
                const trProt2 = document.createElement('tr');
                const tdProt1 = document.createElement('td');
                tdProt1.textContent = '1';
                const tdProt2 = document.createElement('td');
                tdProt2.textContent = med.medicina.descripcion;
                const tdProt3 = document.createElement('td');
                const selectVia = document.createElement('select');
                selectVia.className = 'form-control seleccion-via';
                selectVia.innerHTML = '<option value="" disabled selected>Seleccione una via de administracion</option>'
                +'<option value="1">Intramuscular</option>'
                +'<option value="2">Indovenoso</option>'
                +'<option value="3">Oral</option>';
                tdProt3.appendChild(selectVia);

                const tdProt4 = document.createElement('td');
                const btnEliminarMed = document.createElement('button');
                btnEliminarMed.type = "button";
                btnEliminarMed.className = "btn btn-danger elimMed";
                const iconoBoton1 = document.createElement('i');
                iconoBoton1.className = "fas fa-solid fa-minus";
                btnEliminarMed.appendChild(iconoBoton1);
                tdProt4.appendChild(btnEliminarMed);

                trProt2.appendChild(tdProt1);
                trProt2.appendChild(tdProt2);
                trProt2.appendChild(tdProt3);
                trProt2.appendChild(tdProt4);

                tbodyProt.appendChild(trProt2);
                tablaProt.appendChild(tbodyProt);

                btnEliminarMed.addEventListener('click', function () {
                    const medicina = btnEliminarMed.closest('tr');
                    
                    if (medicina) {
                        medicina.remove();
                    }
                    return;
                });
            });
        }

        precargarProts();
    });

    protocolos.forEach(prot => {
        selectProt.innerHTML += `<option value="${prot.protocolo_id}">${prot.nombre}</option>`;
    });

    // Union de elementos del cuerpo
    theadTabla.appendChild(trProt0);
    tablaProt.appendChild(theadTabla);

    tablaProt.appendChild(tbodyProt);
    
    // Union de la tabla al contenedor
    contenedorTabla.appendChild(tablaProt);

}

// Boton de crear nueva fase
document.getElementById('btnNuevoProt').addEventListener('click', () => {
    asignarProt();

});



