const newExpenseButton = document.querySelector('#new-expense-container');

newExpenseButton.addEventListener('click', () =>{
    location.href = 'expenses/create';
});