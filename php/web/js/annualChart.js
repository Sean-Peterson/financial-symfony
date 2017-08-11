$(document).ready(function(){
  var income = $('#income').val();
  var col = ($('#col').val()*12);
  var rent = ($('#rent').val() * 12);
  var mth = $('#mth').val();
  var ath = $('#ath').val();
  var fed = $('#fed').val();
  var state = $('#state').val();
  var ctx = document.getElementById("annualChart").getContext("2d");
  ctx.canvas.height = 320;
  ctx.canvas.width = 850;
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Net Income","Cost of Living","Rent","Federal Tax","State Tax","Take Home"],
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
