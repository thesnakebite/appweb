<div class="text-center mb-4">
    <div class="mb-3">
        <i class="bi bi-app text-white display-1"></i>
    </div>
    
    <h1 class="display-4 text-white fw-bold">AppWeb</h1>
    <p class="lead text-white">
        Desarrollada por estudiantes hartos de las correcciones de Toni
    </p>
</div>

<!-- Contenedor para mensajes -->
<div class="container" id="salidas">
    <?php
        if(isset($_GET['msn']) && !empty($_GET['msn'])) {
            echo urldecode($_GET['msn']);
        }
    ?>
</div>

<section class="container p-5">
    <div class="row">        
        <div class="col-lg-6">
            <!-- Formulario de login -->
            <?php
                $users = new UserService();
                echo $users->getFormLogin();
            ?>
        </div>
        <div class="col-lg-6">
             <!-- Formulario de registro -->
             <?php
                $users = new UserService();
                echo $users->getFormRegister();
             ?>
        </div>
             
    </div>
</section>

<script src="assets/js/login.js"></script>