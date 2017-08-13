$(document).ready(function(){
  var income = ($('#income0').val() / 12);
  var col = $('#col0').val();
  var rent = $('#rent0').val();
  var mth = $('#mth0').val();
  var ath = $('#ath0').val();
  var fed = ($('#fed0').val() /12);
  var state = ($('#state0').val() /12);
  var income1 = ($('#income1').val() / 12);
  var col1 = $('#col1').val();
  var rent1 = $('#rent1').val();
  var mth1 = $('#mth1').val();
  var ath1 = $('#ath1').val();
  var fed1 = ($('#fed1').val() /12);
  var state1 = ($('#state1').val() /12);
  var city = $('#city0').val();
  var stateName = $('#state_name0').val();
  var city1 = $('#city1').val();
  var stateName1 = $('#state_name1').val();
  var ctx = document.getElementById("monChart0").getContext("2d");
  ctx.canvas.height = 320;
  ctx.canvas.width = 850;
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Net Income "+city,"Net Income "+city1,"Cost of Living "+city,"Cost of Living "+city1,"Rent "+city,"Rent "+city1,"Federal Tax "+city,"Federal Tax "+city1,"State Tax "+city,"State Tax "+city1,"Take Home "+city,"Take Home "+city1],
      datasets: [
        {
        label: [],
        data: [income,income1,col,col1,rent,rent1,fed,fed1,state,state1,mth,mth1],
        borderColor : "#fff",
        borderWidth : "3",
        hoverBorderColor : "#000",
        backgroundColor: [
          "blue",
          "blue",
          "red",
          "red",
          "red",
          "red",
          "red",
          "red",
          "red",
          "red",
          "green",
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
