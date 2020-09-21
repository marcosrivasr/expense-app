<?php
    $expenses               = $this->d['expenses'];
    $totalThisMonth         = $this->d['totalAmountThisMonth'];
    $maxExpensesThisMonth   = $this->d['maxExpensesThisMonth'];
    $user                   = $this->d['user'];
    $categories             = $this->d['categories'];

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
        <?php $this->showMessages();?>
        <div id="expenses-container" class="container">
        
            <div id="left-container">
                
                <div id="expenses-summary">
                    <div>
                        <h2>Bienvenido <?php echo $user->getName() ?></h2>
                    </div>
                    <div class="cards-container">
                        <div class="card w-100">
                            <div class="total-budget">
                                <span class="total-budget-text">
                                    Balance General del Mes    
                                </span>
                            </div>
                            <div class="total-expense">
                                <?php
                                    if($totalThisMonth === NULL){
                                        showError('Hubo un problema al cargar la información');
                                    }else{?>
                                        <span class="<?php echo ($user->getBudget() < $totalThisMonth)? 'broken': '' ?>">$<?php
                                        echo number_format($totalThisMonth, 2);?>
                                        </span>
                                <?php }?>
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="cards-container">
                        <div class="card w-50">
                            <div class="total-budget">
                                <span class="total-budget-text">
                                    de
                                    $<?php 
                                        echo number_format($user->getBudget(),2) . ' mensuales te restan';
                                    ?>
                                </span>
                            </div>
                            <div class="total-expense">
                                <?php
                                    if($totalThisMonth === NULL){
                                        showError('Hubo un problema al cargar la información');
                                    }else{?>
                                        <span>
                                            <?php
                                                $gap = $user->getBudget() - $totalThisMonth;
                                                if($gap < 0){
                                                    echo "-$" . number_format(abs($user->getBudget() - $totalThisMonth), 2);
                                                }else{
                                                    echo "$" . number_format($user->getBudget() - $totalThisMonth, 2);
                                                }
                                            
                                        ?>
                                        </span>
                                <?php }?>
                            </div>
                        </div>
                        
                        <div class="card w-50">
                            <div class="total-budget">
                            <span class="total-budget-text">Tu gasto más grande este mes</span>
                            
                            </div>
                            <div class="total-expense">
                                <?php
                                    if($totalThisMonth === NULL){
                                        showError('Hubo un problema al cargar la información');
                                    }else{?>
                                        <span>$<?php
                                        echo number_format($maxExpensesThisMonth, 2);?>
                                        </span>
                                <?php }?>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="chart-container" >
                    <div id="chart" >

                    </div>
                </div>

                <div id="expenses-category">
                    <h2>Gastos del mes por categoria</h2>
                    <div id="categories-container">
                        <?php
                            if($categories === NULL){
                                showError('Datos no disponibles por el momento.');
                            }else{
                                foreach ($categories as $category ) { ?>
                                    <div class="card w-30 bs-1" style="background-color: <?php echo $category['category']->getColor() ?>">
                                        <div class="content category-name">
                                            <?php echo $category['category']->getName() ?>
                                        </div>
                                        <div class="title category-total">$<?php echo $category['total'] ?></div>
                                        <div class="content category-count">
                                            <p><?php
                                                $count = $category['count'];
                                                if($count == 1){
                                                    echo $count . " transacción";
                                                }else{
                                                    echo $count . " transacciones";
                                                }
                                            ?></p>
                                        </div>
                                    </div>
                        <?php   }
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div id="right-container">
                <div class="transactions-container">
                    <section class="operations-container">
                        <h2>Operaciones</h2>  
                        
                        <button class="btn-main" id="new-expense">
                            <i class="material-icons">add</i>
                            <span>Registrar nuevo gasto</span>
                        </button>
                        <a href="<?php echo constant('URL'); ?>user#budget-user-container">Definir presupuesto<i class="material-icons">keyboard_arrow_right</i></a>
                    </section>

                    <section id="expenses-recents">
                    <h2>Registros más recientes</h2>
                    <?php
                         if($expenses === NULL){
                            showError('Error al cargar los datos');
                        }else if(count($expenses) == 0){
                            showInfo('No hay transacciones');
                        }else{
                            foreach ($expenses as $expense) { ?>
                            <div class='preview-expense'>
                                <div class="left">
                                    <div class="expense-date"><?php echo $expense->getDate(); ?></div>
                                    <div class="expense-title"><?php echo $expense->getTitle(); ?></div>
                                </div>
                                <div class="right">
                                    <div class="expense-amount">$<?php echo number_format($expense->getAmount(), 2);?></div>
                                </div>
                            </div>
                            
                            <?php
                            }
                            echo '<div class="more-container"><a href="expenses">Ver todos los gastos<i class="material-icons">keyboard_arrow_right</i></a></div>';
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