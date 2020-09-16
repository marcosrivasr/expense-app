<?php
    $expenses       = $this->d['expenses'];
    $totalThisMonth = $this->d['totalAmountThisMonth'];
    $totalExpensesThisMonth = $this->d['totalExpensesThisMonth'];
    $user           = $this->d['user'];

    /* $user           = $this->user;
    
    
    $categories     = $this->categories; */

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
                    <div class="card">
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
                        <div class="total-budget">
                            de <span class="total-budget-text">
                                $<?php 
                                    echo number_format($user->getBudget(),2) . ' este mes';
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="card">
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
                        <div class="total-budget">
                            de <span class="total-budget-text">
                                $<?php 
                                    echo number_format($user->getBudget(),2) . ' este mesasdasdasdasdsa';
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
                           /*  $totalPerCategory = 0;
                            if($categories === NULL){
                                showError('Datos no disponibles por el momento.');
                            }else{
                                foreach ($categories as $cat) {
                                    if(number_format($cat['total'], 0) > 0){
                                        $totalPerCategory++;
                    
                             */
                        ?>
                            <div class="card ws-30">
                                <div class="category-total">
                                    $<?php /* echo number_format($cat['total'], 2); ?>    
                                </div>
                                <div class="category-name">
                                    <?php echo $cat['name']; ?>
                                </div>
                            </div>
                        <?php
                                    }
                                }
                                if($totalPerCategory === 0) showInfo('No hay transacciones en este periodo. Empieza a registrar operaciones para categorizarlas');
                            }
                             */
                        ?>
                                </div>
                            </div>
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
                         if($expenses === NULL){
                            showError('Error al cargar los datos');
                        }else if(count($expenses) == 0){
                            showInfo('No hay transacciones');
                        }else{
                            foreach ($expenses as $expense) { ?>
                            <div class='preview-expense'>
                                <div class="left">
                                    <div class="title"><?php echo $expense->getTitle(); ?></div>
                                    
                                </div>
                                <div class="right">
                                    <div class="amount">$<?php echo number_format($expense->getAmount(), 2);?></div>
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