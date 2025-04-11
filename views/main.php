<?php
echo '<main class="container-fluid">';

if(isset($_SESSION['user'])){
    // vistas privadas
    if(isset($_GET['views']) && !empty($_GET['views'])) {
        if($_GET['views'] == 'users'){
            // ... código para users ...
        }
        else if($_GET['views'] == 'dashboard'){
            require('views/dashboard.php');
        }
        // otras vistas
    } else {
        // Vista por defecto para usuarios autenticados
        require('views/dashboard.php');
    }
}
else {
    // vistas públicas
    if(isset($_GET['views']) && !empty($_GET['views'])) {
        if($_GET['views'] == 'home'){
            require('views/home.php');
        }
        // NO incluir login.php aquí, ya está incluido en index.php
        // if($_GET['views'] == 'login'){
        //     require('views/login.php');
        // }
    }
    // No incluir una vista por defecto aquí para evitar duplicación
}

echo '</main>';