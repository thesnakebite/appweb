<?php
$users = new Usuarios();
if(isset($_GET['views']) and !empty($_GET['views']) && $_GET['views'] == 'users'){
    if($_GET['action'] == 'reguser'){
        echo $users->getFormReg();
    }
    if($_GET['action'] == 'edituser'){
        
        echo $users->getFormRegEdit();
    }
}

?>