<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App</title>
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
            
        <div id="expenses-container">

            <div id="left-container">
                <div id="expenses-summary">
                    <div class="card w-50">
                        <div class="total-expense">
                            $5,698.00
                        </div>
                        <div class="total-budget">
                            de <span class="total-budget-text">$6,700.00</span>
                        </div>
                    </div>
                </div>

                <div id="expenses-category">
                    <h3>Categories</h3>
                    <div id="categories-container">
                        <div class="card ws-30">
                            Hogar
                        </div>
                        <div class="card ws-30">
                            Ropa
                        </div>
                        <div class="card ws-30">
                            Comida
                        </div>
                        <div class="card ws-30">
                            Ocio
                        </div>
                        <div class="card ws-30">
                            Hogar
                        </div>
                    </div>
                </div>
            </div>

            <div id="right-container">
                <div id="expenses-transactions">
                    sdsd
                </div>
            </div>
            

        </div>

    </div>

    <?php require 'views/footer.php'; ?>
</body>
</html>