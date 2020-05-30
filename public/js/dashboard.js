const newExpenseButton = document.querySelector('#new-expense-container');

newExpenseButton.addEventListener('click', () =>{
    location.href = 'expenses/create';
});

google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      async function drawChart() {
        const http = await fetch('http://localhost/expense-app/expenses/getExpensesJSON')
        .then(json => json.json())
        .then(res => res);

        let expenses = [...http];
        expenses.shift();

        let colors = [...http][0];
        colors.shift();
        

        var data = google.visualization.arrayToDataTable(expenses);

        var options = {
          colors: colors
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }