
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
    <link rel="stylesheet" href="<?php echo constant('URL') ?>public/css/expense.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
            
        <div id="expenses-container">
            <div id="form-expense-container">
                <form action="expenses/newExpense" method="POST">
                    <div class="section">
                        <div class="title">Cantidad</div>
                        <div><input type="number" name="amount" id="amount"></div>
                    </div>
                    <div class="section">
                        <div class="title">Descripcion</div>
                        <div><input type="text" name="title"></div>
                    </div>
                    
                    <div class="section">
                        <div class="title">Categoria</div>
                        <?php var_dump($this->categories); ?>
                        <div>
                            <select name="category" id="">
                            
                            </select>
                        </div>
                    </div>    

                    <div>
                        <input type="submit" value="Nuevo expense">
                        <input type="button" value="Regresar">
                    </div>
                </form>
            </div>
        </div>

    </div>

    <?php require 'views/footer.php'; ?>
</body>
</html>