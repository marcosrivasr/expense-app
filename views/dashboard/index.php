
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
    
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
            
        <div id="expenses-container">

            <div id="left-container">
                <div id="expenses-summary">
                    <div class="card w-50">
                        <div class="total-expense">
                            <span class="<?php echo ($this->user['budget'] < $this->totalThisMonth)? 'broken': '' ?>">$<?php
                                echo number_format($this->totalThisMonth, 2);
                             ?>
                             </span>
                        </div>
                        <div class="total-budget">
                            de <span class="total-budget-text">
                                $<?php 
                                    echo number_format($this->user['budget'],2) . ' este mes';
                                    echo ($this->user['budget'] === 0.0)? '<div class=""><a href="user" class="btn">Configura tu presupuesto</a></div>': ''
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-button w-50">
                        <div id="new-expense-container">
                            <div class="simbolo">+</div>
                            Añadir nuevo gasto
                        </div>
                    </div>
                </div>

                <div id="columnchart_material" style="height: 300px">
                </div>

                <div id="expenses-category">
                    <h3>Gastos del mes por categoria</h3>
                    <div id="categories-container">
                        <?php 
                            foreach ($this->categories as $cat) {
                                if(number_format($cat['total'], 0) > 0){
                        ?>
                            <div class="card ws-30">
                                <div class="category-total">
                                    $<?php echo number_format($cat['total'], 2); ?>    
                                </div>
                                <div class="category-name">
                                    <?php echo $cat['name']; ?>
                                </div>
                            </div>
                        <?php
                            }
                            }
                        ?>
                    
                    </div>
                </div>
            </div>

            <div id="right-container">
                <div id="profile-container">
                    <a href="user/">
                    <div class="name">
                    <?php echo $this->user['name']; ?>
                    </div>
                
                    <div class="photo">
                        <img src="public/img/photos/<?php echo $this->user['photo'] ?>" width="48" />
                    </div>
                    </a>
                </div>
                <div id="expenses-transactions">
                    <h2>Últimos gastos</h2>
                    <?php
                        if($this->expenses === NULL){
                            echo 'Error al cargar los datos';
                        }else if($this->expenses <= 0){
                            echo 'No hay transacciones';
                        }else{
                            foreach ($this->expenses as $expense) { ?>
                            <div class='preview-expense'>
                                <div class="left">
                                    <div class="title"><?php echo $expense['expense_title'];?></div>
                                    <div class="category" style="background-color: <?php echo $expense['category_color'] . ' !important' ?>"><?php echo $expense['category_name'];?></div>
                                </div>
                                <div class="right">
                                    <div class="amount">$<?php echo number_format($expense['amount'], 2);?></div>
                                </div>
                            </div>
                            
                            <?php
                            }
                            echo '<div class="more-container"><a href="expenses/history" class="btn">Ver todos los gastos</a></div>';
                        }
                     ?>
                </div>
            </div>
            

        </div>

    </div>

    <?php require 'views/footer.php'; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="public/js/dashboard.js"></script>
</body>
</html>