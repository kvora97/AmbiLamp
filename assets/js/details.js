var tempCtx;
var soundCtx;
var soundChart;
var tempChart;
var tempCounter = 0;
var soundcounter = 0;


function drawTemp() {
        tempCounter++;
        tempCtx = document.getElementById("temp-chart-long").getContext('2d');

        if ((tempCounter = tempCounter % 2) == 0){
            tempChart.destroy();
        }

        else{ 

            tempChart = new Chart(tempCtx, 
        {
            type: 'bar',
            data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                responsive: false
            }
        });
    }
}

function drawSound() {
    soundcounter++;

    if ((soundcounter = soundcounter % 2) == 0) {
        soundChart.destroy();
    }

    else {

        soundCtx = document.getElementById("sound-chart-long").getContext('2d');
        soundChart = new Chart(soundCtx, {
            type: 'bar',
            data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                responsive: false
            }
        });
    }
}
