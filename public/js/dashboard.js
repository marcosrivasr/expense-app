const newExpenseButton = document.querySelector('#new-expense-container');

newExpenseButton.addEventListener('click', () =>{
    location.href = 'expenses/create';
});

google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      async function drawChart() {
        
        /*var data = google.visualization.arrayToDataTable([
            ['Year', 'comida', 'ropa', 'juegos', 'viajes'],
            ['enero', 1000, 400, 200, 222],
            ['febrero', 1170, 460, 250, 100],
            ['marzo', 660, 1120, 300, 333],
            ['abril', 1030, 540, 350, 23]
          ]);*/

          var datos = await fetch('http://localhost/expense-app/expenses/getExpensesJSON')
          .then(json => json.json())
          .then(res => res);

          var data = google.visualization.arrayToDataTable(datos);

        var options = {
          };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }