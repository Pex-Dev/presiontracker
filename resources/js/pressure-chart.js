Livewire.on('loadChart', (e) => {                    
    // A veces esto se llama antes de que el DOM esté listo asi que va revisando cada 50ms que se carguen los elementos
    const interval = setInterval(() => {
        if (document.querySelectorAll('LI').length > 0) {
            clearInterval(interval);
            initializeChart(e[0]);
        }
    }, 50);              
});

// Se destruye el chart anterior y se crea uno nuevo
let pressureChart = null;          

function initializeChart(params){
    const canvas = document.getElementById('pressureChart');
    if(!canvas){
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    if(pressureChart){
        //Si el chart ya esta creado anteriormente, se destruye
        pressureChart.destroy();
    }

    const labels = params.labels.reverse();
    const systolicData = params.systolic.reverse();
    const diastolicData = params.diastolic.reverse();

    pressureChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Sistólica',
                    data: systolicData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Diastólica',
                    data: diastolicData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        }
    });
}