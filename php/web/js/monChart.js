$(document).ready(function(){
  var income = ($('#income').val() / 12);
  var col = $('#col').val();
  var rent = $('#rent').val();
  var mth = $('#mth').val();
  var ath = $('#ath').val();
  var fed = ($('#fed').val() /12);
  var state = ($('#state').val() /12);
  var ctx = document.getElementById("monChart").getContext("2d");
  ctx.canvas.height = 320;
  ctx.canvas.width = 850;
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Net Income","Cost of Living","Rent","Federal Tax","State Tax","Take Home"],
      datasets: [
        {
        label: [],
        data: [income,col,rent,fed,state,mth],
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
            beginAtZero:true,
          }
        }]
      }
    }
  });
});
