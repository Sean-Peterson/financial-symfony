$(document).ready(function(){
  var income = $('#income0').val();
  var col = ($('#col0').val()*12);
  var rent = ($('#rent0').val() * 12);
  var mth = $('#mth0').val();
  var ath = $('#ath0').val();
  var fed = $('#fed0').val();
  var state = $('#state0').val();
  var ctx = document.getElementById("annualChart0").getContext("2d");
  ctx.canvas.height = 320;
  ctx.canvas.width = 850;
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Net Income","Cost of Living","Rent","Federal Tax","State Tax","Take Home"],
      datasets: [
        {
        label: [],
        data: [[income,500],col,rent,fed,state,ath],
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
