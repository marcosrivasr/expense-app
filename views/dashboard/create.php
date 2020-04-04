
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
                <form action="newExpense" method="POST">
                    <div class="section">
                        <div class="title">Cantidad</div>
                        <div><input type="number" name="amount" id="amount" autocomplete="off" required></div>
                    </div>
                    <div class="section">
                        <div class="title">Descripcion</div>
                        <div><input type="text" name="title" autocomplete="off" required></div>
                    </div>
                    
                    <div class="section">
                        <div class="title">Fecha de transacción</div>
                        <div>
                            <input type="date" name="date" id="" required>
                        </div>
                    </div>    

                    <div class="section">
                        <div class="title">Categoria</div>
                        <div>
                            <select name="category" id="" required>
                            <?php 
                                foreach ($this->categories as $cat) {
                            ?>
                                <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                                    <?php
                                }
                            ?>
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