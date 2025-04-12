<?php
    session_start();

    require_once('config.php');
    require_once('vendor/autoload.php');
    require_once('controllers/UserController.php');
    require_once('models/UserDB.php');
    
    require('views/header.php');
    require('views/main.php');
    require('views/footer.php');
?>