<section class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </h5>
                </div>
                <div class="card-body bg-transparent">
                    <h4>Bienvenido, <span class="text-capitalize"><?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Usuario'; ?></span></h4>
                    <p class="lead">
                        <small>Este es tu panel de control personal.</small>
                    </p>
                    
                    <div class="row mt-4">
                        <!-- Resumen de actividad -->
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-activity text-primary display-4"></i>
                                    <h5 class="mt-3">Estado del sistema</h5>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Servidor:</span>
                                            <span class="badge bg-success">Activo</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Base de datos:</span>
                                            <span class="badge bg-success">Conectada</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Sesión:</span>
                                            <span class="badge bg-success">Activa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estadísticas de usuario -->
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-person-badge text-success display-4"></i>
                                    <h5 class="mt-3">Tu perfil</h5>
                                    <div class="mt-3">
                                        <div class="text-center mb-3">
                                            <div class="d-inline-block rounded-circle bg-primary text-white" style="width: 60px; height: 60px; line-height: 60px; font-size: 24px;">
                                                <?php 
                                                echo strtoupper(substr(isset($_SESSION['user']) ? $_SESSION['user'] : 'U', 0, 1)); 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Estado:</span>
                                            <span class="badge bg-success">Activo</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Rol:</span>
                                            <span>Usuario</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del sistema -->
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-cpu text-warning display-4"></i>
                                    <h5 class="mt-3">Información del sistema</h5>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>PHP:</span>
                                            <span><?php echo phpversion(); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Servidor:</span>
                                            <span><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Fecha:</span>
                                            <span><?php echo date('d/m/Y'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>