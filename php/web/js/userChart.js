$(document).ready(function(){
    var sleepGoal = $("#sleepGoal").val();
    var sleepProgress = $("#sleepProgress").val();
    var familyGoal = $("#familyGoal").val();
    var familyProgress = $("#familyProgress").val();
    var groupSleepGoal = $('#groupSleepGoal').val();
    var groupFamilyGoal = $('#groupFamilyGoal').val();
    var groupSleepProgress = $('#groupSleepProgress').val();
    var groupFamilyProgress = $('#groupFamilyProgress').val();
    var ctx = document.getElementById("userChart").getContext("2d");
    ctx.canvas.height = 320;
    ctx.canvas.width = 850;
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Sleep", "Family", "Group Sleep Total", "Group Family Total"],
            datasets: [{
                label: 'Goal',
                data: [sleepGoal, familyGoal, groupSleepGoal, groupFamilyGoal],
                backgroundColor:
                    'rgba(240, 173, 78, 1)',
            }, {
                label: 'Progress Towards Goal',
                data: [sleepProgress, familyProgress, groupSleepProgress, groupFamilyProgress],
                backgroundColor:
                    'rgba(25, 86, 139, 1)',
            }]
        },
        options: {
                responsive: true,
                maintainAspectRatio: false,
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
