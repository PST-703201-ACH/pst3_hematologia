document.addEventListener('DOMContentLoaded', function() {
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
    const id = boton.getAttribute('data-id');

    try {
        var respuesta = await fetch(`/medico/obtener-enfermedades`);
        const datosEnf = await respuesta.json();
        const selectEnf = document.getElementById('enfConReg');

        datosEnf.forEach(enf => {
            const option = document.createElement('option');
            option.value = enf.enfermedad_id;
            option.textContent = enf.descripcion;
            selectEnf.appendChild(option);
        });

        respuesta = await fetch(`/medico/precargar-con/${id}`);
        const datosCon = await respuesta.json();

        let nombresPa = datosCon.consulta.paciente.persona.nombres;
        let apellidosPa = datosCon.consulta.paciente.persona.apellidos;

        let paciente = nombresPa+' '+apellidosPa;
        let representante = datosCon.nombres_representante+' '+datosCon.apellidos_representante;
        let hc = datosCon.consulta.paciente.hc;
        let nombrePa = paciente.split(" ");

        let tipo = '';
        let boton1 = document.createElement('button');
        let boton2 = document.createElement('button');
        let boton3 = document.createElement('button');
        const seccion1 = document.getElementById('seccion-consulta-registrar1');
        const seccion2 = document.getElementById('seccion-consulta-registrar2');
        const seccion3 = document.getElementById('seccion-consulta-registrar3');

        boton1.className = 'btn btn-outline-primary m-1';
        boton1.textContent = '1';
        boton1.type = 'button';
        boton2.className = 'btn btn-outline-primary m-1';
        boton2.textContent = '2';
        boton2.type = 'button';
        boton3.className = 'btn btn-outline-primary m-1';
        boton3.textContent = '3';
        boton3.type = 'button';
        
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

        }else if (datosCon.consulta.tipo == 2){
            tipo = 'Consulta de control';

            boton1.addEventListener('click', function (boton) {
                seccion4.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion1.classList.remove('d-none');
                actualizarBotones();
            });

            boton2.addEventListener('click', function (boton) {
                seccion1.classList.add('d-none');
                seccion3.classList.add('d-none');
                seccion4.classList.add('d-none');
                seccion2.classList.remove('d-none');
                actualizarBotones();
            });

            boton3.addEventListener('click', function (boton) {
                seccion1.classList.add('d-none');
                seccion2.classList.add('d-none');
                seccion4.classList.add('d-none');
                seccion3.classList.remove('d-none');
                actualizarBotones();
            });

            boton4.addEventListener('click', function (boton) {
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

    } catch (error) { 
        console.error("Error al pre-cargar datos:", error);
    }
}