<?php
    function showError($message){
        echo "<span class='error'>$message</span>";
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Expense App - Dashboard</title>
    
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
            
        <div id="expenses-container">
        
            <div id="left-container">
                <h2>Resumen</h2>
                <div id="expenses-summary">
                    <div class="card w-50">
                        <div class="total-expense">
                            <?php
                                if($this->totalThisMonth === NULL){
                                    showError('Hubo un problema al cargar la información');
                                }else{?>
                                    <span class="<?php echo ($this->user['budget'] < $this->totalThisMonth)? 'broken': '' ?>">$<?php
                                    echo number_format($this->totalThisMonth, 2);?>
                             </span>
                            <?php }?>
                            
                            
                        </div>
                        <div class="total-budget">
                            de <span class="total-budget-text">
                                $<?php 
                                    echo number_format($this->user['budget'],2) . ' este mes';
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div id="columnchart_material">
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
                <div id="expenses-transactions">
                    <section>
                        <h2>Operaciones</h2>  
                        
                        <button class="btn-main" id="new-expense">
                            <i class="material-icons">add</i>
                            <span>Registrar nuevo gasto</span>
                        </button>
                        <a href="<?php echo constant('URL'); ?>user#budget-user-container" class="secondary">Definir presupuesto</a>
                    </section>

                    <section>
                    <h2>Últimos gastos</h2>
                    <?php
                        if($this->expenses === NULL){
                            echo 'Error al cargar los datos';
                        }else if(count($this->expenses) == 0){
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
                            echo '<div class="more-container"><a href="expenses/history">Ver todos los gastos<i class="material-icons">keyboard_arrow_right</i></a></div>';
                        }
                     ?>
                    </section>
                </div>
            </div>
            

        </div>

    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="public/js/dashboard.js"></script>
    
</body>
</html>