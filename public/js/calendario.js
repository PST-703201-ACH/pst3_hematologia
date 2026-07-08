var calendarEl = document.getElementById('calendar');
const modalCita = document.getElementById('modalCita');
const campoHc = document.getElementById('hc');

async function precargarEventos(){
    const respuesta = await fetch('/obtener-citas');
    const citas = await respuesta.json();

    let id = 1;
    let eventos = [];

    citas.forEach(cita => {
        let fechaCita = new Date(cita.fecha_hora.replace(" ", "T"));
        let fechaActual = new Date();
        let Status = "";

        let difFecha = fechaCita - fechaActual;
        if (difFecha < 0) {
            Status = "Incumplida"; 
        } else {
            Status = "Programada";
        }
        let nombre1 = cita.nombres_paciente.split(' ');
        let apellido1 = cita.apellidos_paciente.split(' ');
        let titulo = nombre1[0] + ' ' + apellido1[0];
        eventos.push({
            id: String(id++),
            title: titulo,
            start: cita.fecha_hora,
            color: '#3788d8',

            extendedProps: {
                status: Status
            }

        });

    });

    return eventos;
}



var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'es',
    themeSystem: 'bootstrap',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },

    height: 'auto',

    
    events: async function(info, successCallback, failureCallback) {
        try {
            const eventosCargados = await precargarEventos();
            successCallback(eventosCargados);
        } catch (error) {
            console.error("Error cargando eventos:", error);
            failureCallback(error);
        }
    },
    eventContent: function(cita) {
    let statusCita = cita.event.extendedProps.status;
    let claseStatus = "";

    if (statusCita == "Programada") {
        claseStatus = "badge badge-success";
    }
    else if (statusCita == "Incumplida") {
        claseStatus = "badge badge-danger";
    }

    let contenedor = document.createElement('div');
    contenedor.className = 'evento-personalizado';
    
    contenedor.innerHTML = `
        <span class="fc-event-title">${cita.event.title}</span>
        <span class="fc-event-title ${claseStatus}">${statusCita}</span>
    `;

    return { domNodes: [contenedor] };
    },
    dateClick: function(info) {
        const formCita = new bootstrap.Modal(modalCita);
        let horaExtraida = info.date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let fechaExtraida = info.date.toLocaleDateString('sv');
        let vistaActual = info.view.type;
        document.getElementById('fechaHora').readOnly = false;
        document.getElementById('formReg').reset();
        if (vistaActual === 'dayGridMonth') {
            document.getElementById('fechaCita').value = fechaExtraida;
            document.getElementById('fechaHora').value = horaExtraida;
        } else if (vistaActual === 'timeGridWeek') {
            document.getElementById('fechaCita').value = fechaExtraida;
            document.getElementById('fechaHora').value = horaExtraida;
            document.getElementById('fechaHora').readOnly = true;
        } else if (vistaActual === 'timeGridDay') {
            document.getElementById('fechaCita').value = fechaExtraida;
            document.getElementById('fechaHora').value = horaExtraida;
            document.getElementById('fechaHora').readOnly = true;
        }
        
        formCita.show();

        campoHc.addEventListener('input', (hc) => {
            const valor = parseInt(hc.target.value, 10);
            if (isNaN(valor) || valor < 1) {
              hc.target.value = 1;
            }
        })
    }
});