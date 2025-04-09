<?php
    echo '<main class="container-fluid">';
   
    if(isset($_SESSION['user'])){             
        // vistas privadas
        if($_GET['views'] == 'users'){                
            if(isset($_GET['action']) && !empty($_GET['action'])){
                if($_GET['action'] == 'reguser' or $_GET['action'] = 'edituser'){
                    require('views/users/form.php');
                }
            }
            else
            {
                require('views/users/view.php');
            }
        }
        if($_GET['views'] == 'dashboard'){           
          
            require('views/dashboard/view.php');
        }
         /*
            if($_GET['views'] == 'tareas'){
                if(isset($_SESSION['user'])){
                    require('views/tareas/view.php');
                }
                else
                {
                    echo '<div class="alert alert-danger">Zona restringida</div>'; 
                }
            }
                */

        require('views/dashboard/view.php');
    }
    else
    {
        // vistas públicas
        if(isset($_GET['views']) && !empty($_GET['views'])) {
            if($_GET['views'] == 'home'){
                require('views/home.php');
            }
            if($_GET['views'] == 'login'){
                require('views/login.php');
            }
        }
    }

    echo '</main>';