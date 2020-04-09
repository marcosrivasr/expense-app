
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
    <link rel="stylesheet" href="<?php echo constant('URL') ?>public/css/user.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
            
        <div id="user-container">
            <div id="user-section-container">

                <div id="data-user-container">
                    <form action=<?php echo constant('URL'). 'user/updateName' ?> method="POST">
                        <div class="section">
                            <div class="title">Nombre</div>
                            <div><input type="text" name="name" id="name" autocomplete="off" required value="<?php echo $this->name ?>"></div>
                            <div><input type="submit" value="Cambiar nombre" /></div>
                        </div>
                    </form>


                    <form action="<?php echo constant('URL'). 'user/updatePhoto' ?>" method="POST" enctype="multipart/form-data">
                        <div class="section">
                            <div class="title">Foto de perfil</div>
                            
                            <?php
                                if(!empty($this->photo)){
                            ?>
                                <img src="public/img/photos/<?php echo $this->photo ?>" width="50" height="50" />
                            <?php
                                }
                            ?>
                            <div><input type="file" name="photo" id="photo" autocomplete="off" required></div>
                            <div><input type="submit" value="Cambiar foto de perfil" /></div>
                        </div>
                    </form>

                    <form action="<?php echo constant('URL'). 'user/updatePassword' ?>" method="POST">

                        <div class="section">
                            <div class="title">Password actual</div>
                            <div><input type="text" name="current_password" id="current_password" autocomplete="off" required></div>
                            <div class="title">Password nuevo</div>
                            <div><input type="text" name="new_password" id="new_password" autocomplete="off" required></div>
                            <div><input type="submit" value="Cambiar password" /></div>
                        </div>

                    </form>
                </div>
                <div id="budget-user-container">
                <form action="user/updateBudget" method="POST">
                    <div class="section">
                        <div class="title">Definir presupuesto</div>
                        <div><input type="number" name="budget" id="budget" autocomplete="off" required value="<?php echo $this->budget ?>"></div>
                        <div><input type="submit" value="Actualizar presupuesto" /></div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <?php require 'views/footer.php'; ?>
</body>
</html>