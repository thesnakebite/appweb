<?php
// Verificar autenticación
$isLoggedIn = isset($_SESSION['user']);

// Incluir el menú solo si el usuario está autenticado
if($isLoggedIn) {
    require('views/menu.php');
}

echo '<main class="container-fluid">';

// Obtener la vista solicitada (o establecer por defecto)
$requestedView = isset($_GET['views']) ? $_GET['views'] : '';

if($isLoggedIn) {
    // Usuario autenticado: mostrar vistas privadas
    switch($requestedView) {
        case 'users':
            require('views/usuarios/index.php');
            break;
        case 'tasks':
            require('views/tareas/index.php');
            break;
        case 'dashboard':
        default:
            require('views/dashboard.php');
            break;
    }
} else {
    // Usuario no autenticado: mostrar solo login
    require('views/login.php');
}

echo '</main>';
?>