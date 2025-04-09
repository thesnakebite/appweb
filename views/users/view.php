<section class="container p-5">
<div class="row">
    <?php
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
                echo '<h2>Listado de Usuarios</h2>';
                echo '<a class="btn btn-primary" href="index.php?views=users&action=reguser">Nuevo Usuarios</a>';
                $users = new Usuarios();
                echo $users->getTable();
                
        }
        else
        {
    ?>
    <div class="col-lg-6">
        <!-- FORMULARIO DE LOGIN -->
        <?php
            $users = new Usuarios();
            echo $users->getFormLogin();

        ?>
    </div>
    <div class="col-lg-6">
         <!-- FORMULARIO DE REGISTRO -->
         <?php
            $users = new Usuarios();
            echo $users->getFormReg();

         ?>
    </div>
    <?php

        }

    ?>        
</div>
</section>

<script src="assets/js/login.js"></script>


