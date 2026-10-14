document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filtros-enfermeria');
    const body = document.getElementById('cuerpo-sesiones-enfermeria');
    const errorBox = document.getElementById('error-agenda-enfermeria');

    if (!form || !body || !errorBox) return;

    const addCell = (row, value) => {
        const cell = document.createElement('td');
        cell.textContent = value ?? '';
        row.appendChild(cell);
        return cell;
    };

    const renderSession = session => {
        const row = document.createElement('tr');
        addCell(row, session.fecha_hora);

        const patientCell = addCell(row, `${session.nombres ?? ''} ${session.apellidos ?? ''}`.trim());
        const record = document.createElement('small');
        record.className = 'd-block';
        record.textContent = `H.C. ${session.hc ?? ''}`;
        patientCell.appendChild(record);

        const treatmentCell = addCell(row, session.protocolo ?? '');
        const medicine = document.createElement('small');
        medicine.className = 'd-block';
        medicine.textContent = `${session.medicamento ?? ''} — Ciclo ${session.numero_ciclo ?? ''}`;
        treatmentCell.appendChild(medicine);

        const doseCell = addCell(row, session.dosis ?? '');
        const route = document.createElement('small');
        route.className = 'd-block';
        route.textContent = session.via_administracion ?? '';
        doseCell.appendChild(route);

        addCell(row, session.sala);
        const actionCell = addCell(row, '');
        const badge = document.createElement('span');
        badge.className = `badge ${session.status === 'Realizada' ? 'badge-success' : 'badge-info'}`;
        badge.textContent = session.status;
        actionCell.appendChild(badge);

        if (session.status === 'Programada') {
            const wrapper = document.createElement('form');
            wrapper.method = 'POST';
            wrapper.action = form.dataset.applyTemplate.replace('__ID__', encodeURIComponent(session.sesion_id));
            wrapper.className = 'mt-2';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            wrapper.appendChild(csrf);

            const appliedAt = document.createElement('input');
            appliedAt.className = 'form-control form-control-sm mb-1';
            appliedAt.type = 'datetime-local';
            appliedAt.name = 'fecha_hora_aplicacion';
            appliedAt.required = true;
            appliedAt.value = new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                .toISOString()
                .slice(0, 16);
            wrapper.appendChild(appliedAt);

            const label = document.createElement('label');
            label.className = 'd-block';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'identidad_confirmada';
            checkbox.value = '1';
            checkbox.required = true;
            label.append(checkbox, document.createTextNode(' Identidad verificada'));
            wrapper.appendChild(label);

            const notes = document.createElement('input');
            notes.className = 'form-control form-control-sm mb-1';
            notes.type = 'text';
            notes.name = 'observaciones_enfermeria';
            notes.maxLength = 5000;
            notes.placeholder = 'Observación (opcional)';
            wrapper.appendChild(notes);

            const submit = document.createElement('button');
            submit.className = 'btn btn-sm btn-success';
            submit.type = 'submit';
            submit.textContent = 'Registrar aplicación';
            wrapper.appendChild(submit);
            actionCell.appendChild(wrapper);
        }

        return row;
    };

    const refresh = async () => {
        const url = new URL(form.dataset.url, window.location.origin);
        new URLSearchParams(window.location.search).forEach((value, key) => {
            url.searchParams.set(key, value);
        });

        try {
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error(`No se pudo actualizar la agenda (HTTP ${response.status}).`);

            const sessions = await response.json();
            body.replaceChildren();

            if (!sessions.length) {
                const row = document.createElement('tr');
                const emptyCell = addCell(row, 'No hay sesiones para los filtros seleccionados.');
                emptyCell.colSpan = 6;
                emptyCell.className = 'text-center';
                body.appendChild(row);
            } else {
                sessions.forEach(session => body.appendChild(renderSession(session)));
            }

            errorBox.classList.add('d-none');
            errorBox.textContent = '';
        } catch (error) {
            errorBox.textContent = error.message;
            errorBox.classList.remove('d-none');
            console.error(error);
        }
    };

    refresh();
    window.setInterval(refresh, 30000);
});
