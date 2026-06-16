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
            formCita.show();
            document.getElementById('fechaCita').value = info.dateStr;
            campoHc.addEventListener('input', (hc) => {
                const valor = parseInt(hc.target.value, 10);
                if (isNaN(valor) || valor < 1) {
                  hc.target.value = 1;
                }
            })
        }
    });

