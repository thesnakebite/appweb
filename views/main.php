<?php
// Incluir el menú solo si el usuario está autenticado
if(isset($_SESSION['user'])) {
    require('views/menu.php');
}

echo '<main class="container-fluid">';

if(isset($_SESSION['user'])) {
    // Vistas privadas
    if(isset($_GET['views']) && !empty($_GET['views'])) {
        if($_GET['views'] == 'users') {
            // Lógica para la vista de usuarios
            if(isset($_GET['action']) && !empty($_GET['action'])) {
                if($_GET['action'] == 'reguser' || $_GET['action'] == 'edituser') {
                    require('views/users/form.php');
                }
            } else {
                require('views/usuarios/index.php');
            }
        } else if($_GET['views'] == 'tareas') {
            require('views/tareas/view.php');
        } else if($_GET['views'] == 'dashboard') {
            require('views/dashboard.php');
        }
    } else {
        // Vista por defecto para usuarios autenticados
        require('views/dashboard.php');
    }
} else {
    // Vistas públicas
    if(isset($_GET['views']) && !empty($_GET['views'])) {
        if($_GET['views'] == 'home') {
            require('views/home.php');
        } else if($_GET['views'] == 'login') {
            require('views/login.php');
        } else if($_GET['views'] == 'tareas') {
            // Versión pública de tareas
            require('views/tareas/view.php');
        }
    } else {
        // Si no se especifica ninguna vista y el usuario no está autenticado
        require('views/login.php');
    }
}

echo '</main>';
?>