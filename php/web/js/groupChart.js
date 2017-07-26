$(document).ready(function(){
    var sleepGoal = $('#sleepGoal').val();
    var familyGoal = $('#familyGoal').val();
    var sleepProgress = $('#sleepProgress').val();
    var familyProgress = $('#familyProgress').val();
    var ctx = document.getElementById("groupChart").getContext("2d");
    ctx.canvas.height = 320;
    ctx.canvas.width = 850;
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Sleep", "Family"],
            datasets: [{
                label: 'Current Group Goal',
                data: [sleepGoal, familyGoal],
                backgroundColor:
                    'rgba(240, 173, 78, 1)',
            }, {
                label: 'Current Group Progress',
                data: [sleepProgress, familyProgress],
                backgroundColor:
                    'rgba(25, 86, 139, 1)',
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
});


// 'rgba(54, 162, 235, 0.2)',
// 'rgba(255, 206, 86, 0.2)',
// 'rgba(75, 192, 192, 0.2)',
// ],
// borderColor: [
//     'rgba(255,99,132,1)',
//     'rgba(54, 162, 235, 1)',
//     'rgba(255, 206, 86, 1)',
//     'rgba(75, 192, 192, 1)',
// ],
// borderWidth: 3
