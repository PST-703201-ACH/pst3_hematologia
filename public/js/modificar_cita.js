async function precargarCita(boton){    
    const id = boton.getAttribute('data-id');
    const modalReprogramar = document.getElementById('modalReprogramar');
    try {
        document.getElementById('formRep').reset();
        const respuesta = await fetch(`/precargar-cita/${id}`);
        const cita = await respuesta.json();

        const [nombre1P, nombre2P] = cita.nombres_paciente.split(" ");
        const [apellido1P, apellido2P] = cita.apellidos_paciente.split(" ");
        const [nombre1R, nombre2R] = cita.nombres_representante.split(" ");
        const [apellido1R, apellido2R] = cita.apellidos_representante.split(" ");

        document.getElementById('pacienteNombreRep1').value = nombre1P;
        
        if (nombre2P) {
            document.getElementById('pacienteNombreRep2').value = nombre2P;    
        }

        document.getElementById('pacienteApellidoRep1').value = apellido1P;
        
        if (apellido2P) {
            document.getElementById('pacienteApellidoRep2').value = apellido2P;
        }

        document.getElementById('represNombreRep1').value = nombre1R;
        
        if (nombre2R) {
            document.getElementById('represNombreRep2').value = nombre2R;
        }

        document.getElementById('represApellidoRep1').value = apellido1R;
        
        if (apellido2R) {
            document.getElementById('represApellidoRep2').value = apellido2R;
        }

        document.getElementById('hcRep').value = cita.numero_hc;

            const info = new bootstrap.Modal(modalReprogramar);
            info.show();
        } catch (error) { 
            console.error("Error al cargar datos:", error);
        }
}

const inputFecha = document.getElementById('fechaCitaRep');

const hoy = new Date().toISOString().split('T')[0];

inputFecha.min = hoy;

inputFecha.addEventListener('change', (e) => {
    const fechaSeleccionada = e.target.value;
    if (fechaSeleccionada < hoy) {
        alert("No puedes seleccionar una fecha pasada.");
        e.target.value = "";
    }
});

document.addEventListener('DOMContentLoaded', function() {

    const formulario = document.getElementById('formRep');
    const citaModal = new bootstrap.Modal(document.getElementById('reprogramandoCita'));

    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            const confirmacion = confirm("¿Está seguro de reprogramar esta cita?");
            e.preventDefault();
            if (confirmacion) {

                const datos = new FormData(formulario);
                citaModal.show();

                try {
                    const respuesta = await fetch(formulario.action, {
                        method: 'POST',
                        body: datos,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const resultado = await respuesta.json();

                    if(resultado.status === "exito") {
                        let alertaCita = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-check"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        calendar.refetchEvents();

                        document.getElementById('alertaRepCita').innerHTML = alertaCita;
                        
                        setTimeout(function (){
                            document.getElementById('alertaRepCita').innerHTML = "";
                        }, 3000);

                        formulario.reset();
                    }else if(resultado.status === "error") {
                        let alertaCita = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="icon fas fa-xmark"></i> 
                                ${resultado.mensaje}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        document.getElementById('alertaRepCita').innerHTML = alertaCita;
                        setTimeout(function (){
                            document.getElementById('alertaRepCita').innerHTML = "";
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
                    citaModal.hide();
                    }, 500);
                }
            }

        });
    }
});