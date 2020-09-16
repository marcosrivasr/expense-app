<link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/default.css">
<link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/dashboard.css">


<div id="header">
    <ul>
        <li><a href="<?php echo constant('URL'); ?>expenses">Home</a></li>
        <li><a href="<?php echo constant('URL').'expenses/'; ?>history">History</a></li>
        <li><a href="<?php echo constant('URL'); ?>logout">Logout</a></li>
    </ul>

    <div id="profile-container">
        <a href="<?php //echo constant('URL');?>user">
            <div class="name"><?php //echo $this->user['name']; ?></div>
            <div class="photo">
                <?php /* if($this->user['photo'] == ''){?>
                        <i class="material-icons">account_circle</i>
                <?php }else{ ?>
                        <img src="<?php echo constant('URL'); ?>public/img/photos/<?php echo $this->user['photo'] ?>" width="32" />
                <?php }  */?>
            </div>
        </a>
        <div id="submenu">
            <ul>
                <li><a href="<?php echo constant('URL'); ?>user">Ver perfil</a></li>
                <li class='divisor'></li>
                <li><a href="<?php echo constant('URL'); ?>logout">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</div>
