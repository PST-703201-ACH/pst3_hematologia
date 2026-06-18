    var calendarEl = document.getElementById('calendar');
    const modalCita = document.getElementById('modalCita');
    const campoHc = document.getElementById('hc');

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
        events: [
            
        ],
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

