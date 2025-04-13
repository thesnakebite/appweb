<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-app"></i>
            AppWeb
        </a>
        <button 
            class="navbar-toggler" 
            type="button"
            data-bs-toggle="collapse" 
            data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" 
            aria-expanded="false" 
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (!isset($_GET['views']) || $_GET['views'] == 'dashboard') ? 'active' : ''; ?>" 
                       aria-current="page" 
                       href="<?php echo RUTA_WEB; ?>index.php?views=dashboard">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['views']) && $_GET['views'] == 'tasks') ? 'active' : ''; ?>" 
                       aria-current="page" 
                       href="<?php echo RUTA_WEB; ?>index.php?views=tasks">
                        Tareas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['views']) && $_GET['views'] == 'users') ? 'active' : ''; ?>" 
                       aria-current="page" 
                       href="<?php echo RUTA_WEB; ?>index.php?views=users">
                        Usuarios
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" 
                       href="#" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        Opciones
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Ayuda</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Logout -->
            <div class="d-flex align-items-center">
                <?php if(isset($_SESSION['user']) && !empty($_SESSION['user'])): ?>
                    <span class="text-black me-3">Hola, <?php echo $_SESSION['user']; ?></span>
                    <a href="<?php echo RUTA_WEB; ?>index.php?action=CERRAR_SESION" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
                    </a>
                <?php else: ?>
                    <a class="btn btn-primary" href="<?php echo RUTA_WEB; ?>index.php?views=login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<header class="container mt-5 pt-3">
    <div id="salidas">
        <?php 
        if(isset($msn) && !empty($msn)){
            echo "<div class='mensaje'>$msn</div>";
        }
        if(isset($_GET['msn']) && !empty($_GET['msn'])){
            echo '<div class="mensaje">'.$_GET['msn'].'</div>';
        }
        ?>
    </div>
</header>