// TEMPORAL: inicializa gráficos demostrativos con datos ficticios; retirar este archivo si se descarta la propuesta.
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') {
        console.error('No se pudo cargar Chart.js para los gráficos demostrativos del panel Médico.');
        return;
    }

    const diseaseCanvas = document.getElementById('graficoPacientesEnfermedad');
    const sexCanvas = document.getElementById('graficoPacientesSexo');
    const treatmentCanvas = document.getElementById('graficoTratamientosAsignados');

    if (!diseaseCanvas || !sexCanvas || !treatmentCanvas) {
        return;
    }

    // Los grupos reflejan entidades de estructura.sql; sus cantidades son inventadas y no representan pacientes reales.
    new Chart(diseaseCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Leucemia', 'Anemia', 'Linfoma', 'Otra enfermedad'],
            datasets: [{
                label: 'Pacientes de ejemplo',
                data: [8, 5, 3, 2],
                backgroundColor: ['#3c8dbc', '#00a65a', '#f39c12', '#605ca8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
        }
    });

    new Chart(sexCanvas.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Niños', 'Niñas'],
            datasets: [{
                data: [12, 8],
                backgroundColor: ['#3c8dbc', '#e83e8c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    generateLabels: (chart) => {
                        const labels = Chart.defaults.global.legend.labels.generateLabels(chart);
                        const values = chart.data.datasets[0].data;
                        const total = values.reduce((sum, value) => sum + value, 0);

                        return labels.map((item, index) => {
                            item.text = `${item.text} (${Math.round((values[index] / total) * 100)}%)`;
                            return item;
                        });
                    }
                }
            }
        }
    });

    new Chart(treatmentCanvas.getContext('2d'), {
        type: 'horizontalBar',
        data: {
            labels: [
                ['Protocolo A', 'Medicamento alfa'],
                ['Protocolo A', 'Medicamento beta'],
                ['Protocolo B', 'Medicamento gamma'],
                ['Protocolo B', 'Medicamento delta']
            ],
            datasets: [{
                label: 'Asignaciones de ejemplo',
                data: [7, 5, 4, 3],
                backgroundColor: ['#00a65a', '#3c8dbc', '#f39c12', '#605ca8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: { xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
        }
    });
});
