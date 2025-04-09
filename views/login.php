<section class="container p-5">
    <div class="row">        
        <div class="col-lg-6">
            <!-- FORMULARIO DE LOGIN -->
            <?php
                $users = new Usuario();
                echo $users->getFormLogin();
            ?>
        </div>
        <div class="col-lg-6">
             <!-- FORMULARIO DE REGISTRO -->
             <?php
                $users = new Usuario();
                echo $users->getFormRegister();
             ?>
        </div>
             
    </div>
</section>

<script src="assets/js/login.js"></script>