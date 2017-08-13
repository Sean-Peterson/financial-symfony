$(document).ready(function(){
  var income = $('#income1').val();
  var col = ($('#col1').val()*12);
  var rent = ($('#rent1').val() * 12);
  var mth = $('#mth1').val();
  var ath = $('#ath1').val();
  var fed = $('#fed1').val();
  var state = $('#state1').val();
  var ctx = document.getElementById("annualChart1").getContext("2d");
  ctx.canvas.height = 320;
  ctx.canvas.width = 850;
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Net Income"+income,"Cost of Living","Rent","Federal Tax","State Tax","Take Home"],
      datasets: [
        {
        label: [],
        data: [income,col,rent,fed,state,ath],
        borderColor : "#fff",
        borderWidth : "3",
        hoverBorderColor : "#000",
        backgroundColor: [
          "blue",
          "red",
          "red",
          "red",
          "red",
          "green",
        ],
      },
    ]
    },
    options: {
      legend: {
           display: false
        },
        tooltips: {
           enabled: true
        },
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
