<?php
    session_start();
    require_once('config.php');
    require_once('vendor/autoload.php');
    require_once('controller/UserController.php');    
    require_once('models/User.php');
    
    require('views/header.php');
    require('views/menu.php');
    require('views/main.php');   
    require('views/footer.php');
?>