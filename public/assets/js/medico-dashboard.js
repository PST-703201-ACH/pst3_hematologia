document.addEventListener('DOMContentLoaded', () => {
    const metrics = window.PST3MedicoDashboardData;
    if (!metrics) {
        console.error('No se recibieron las métricas del dashboard Médico.');
        return;
    }

    const diseaseCanvas = document.getElementById('graficoPacientesEnfermedad');
    const sexCanvas = document.getElementById('graficoPacientesSexo');
    const treatmentCanvas = document.getElementById('graficoTratamientosAsignados');
    if (!diseaseCanvas || !sexCanvas || !treatmentCanvas) {
        return;
    }

    const protocolUnavailable = document.getElementById('protocolosNoDisponibles');
    const noDiseaseData = document.getElementById('sinDatosEnfermedad');
    const noSexData = document.getElementById('sinDatosSexo');
    const noProtocolData = document.getElementById('sinDatosProtocolos');

    if (typeof Chart === 'undefined') {
        console.error('No se pudo cargar Chart.js para los gráficos del panel Médico.');
        [noDiseaseData, noSexData, noProtocolData].forEach((message) => {
            message.textContent = 'No se pudieron cargar los gráficos. Recarga el panel e inténtalo nuevamente.';
            message.classList.remove('d-none');
        });
        return;
    }

    const hasValues = (rows) => rows.length > 0 && rows.some((row) => row.total > 0);
    const palette = ['#3c8dbc', '#00a65a', '#f39c12', '#605ca8', '#e83e8c', '#17a2b8', '#6c757d'];
    const sexColors = {
        femenino: '#e83e8c',
        masculino: '#3c8dbc'
    };

    if (hasValues(metrics.pacientes_por_enfermedad)) {
        new Chart(diseaseCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: metrics.pacientes_por_enfermedad.map((row) => row.etiqueta),
                datasets: [{
                    label: 'Pacientes',
                    data: metrics.pacientes_por_enfermedad.map((row) => row.total),
                    backgroundColor: metrics.pacientes_por_enfermedad.map((_, index) => palette[index % palette.length])
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
            }
        });
    } else {
        diseaseCanvas.classList.add('d-none');
        noDiseaseData.classList.remove('d-none');
    }

    // Normalize legacy F/M values and full labels so sex colors never depend on row order.
    const sexRows = metrics.pacientes_por_sexo.reduce((rows, row) => {
        const sourceLabel = String(row.etiqueta || '').trim();
        const normalizedLabel = sourceLabel.toLocaleLowerCase('es').normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        let category;

        if (['f', 'femenino', 'femenina', 'mujer', 'mujeres', 'hembra', 'female'].includes(normalizedLabel)) {
            category = { label: 'Femenino', color: sexColors.femenino };
        } else if (['m', 'masculino', 'masculina', 'hombre', 'hombres', 'varon', 'varones', 'male'].includes(normalizedLabel)) {
            category = { label: 'Masculino', color: sexColors.masculino };
        } else {
            return rows;
        }

        const existingRow = rows.find((item) => item.label === category.label);
        if (existingRow) {
            existingRow.total += row.total;
        } else {
            rows.push({ ...category, total: row.total });
        }

        return rows;
    }, []);

    if (hasValues(sexRows)) {
        const sexTotal = sexRows.reduce((total, row) => total + row.total, 0);

        new Chart(sexCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: sexRows.map((row) => row.label),
                datasets: [{
                    data: sexRows.map((row) => row.total),
                    backgroundColor: sexRows.map((row) => row.color)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels: {
                        generateLabels: () => sexRows.map((row, index) => ({
                            text: `${row.label} (${Math.round((row.total / sexTotal) * 100)}%)`,
                            fillStyle: row.color,
                            strokeStyle: row.color,
                            hidden: false,
                            index
                        }))
                    }
                }
            }
        });
    } else {
        sexCanvas.classList.add('d-none');
        noSexData.classList.remove('d-none');
    }

    if (!metrics.protocolos_disponibles) {
        treatmentCanvas.classList.add('d-none');
        protocolUnavailable.classList.remove('d-none');
    } else if (hasValues(metrics.usos_por_protocolo_medicina)) {
        new Chart(treatmentCanvas.getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: metrics.usos_por_protocolo_medicina.map((row) => [row.etiqueta, row.medicina]),
                datasets: [{
                    label: 'Usos en tratamientos activos',
                    data: metrics.usos_por_protocolo_medicina.map((row) => row.total),
                    backgroundColor: metrics.usos_por_protocolo_medicina.map((_, index) => palette[index % palette.length])
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: { xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
            }
        });
    } else {
        treatmentCanvas.classList.add('d-none');
        noProtocolData.classList.remove('d-none');
    }
});
