<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-house"></i>
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
                        <a class="nav-link active" aria-current="page" href="https://appweb.test/index.php?views=home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="https://appweb.test/index.php?views=tareas">Tareas</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="https://appweb.test/index.php?views=users">Usuarios</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
  
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  <?php 
                      if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                          echo '
                              <a class="btn btn-danger" href="index.php?action=CERRAR_SESSION" style="margin-left:5px;">
                                  <i class="fa-regular fa-rectangle-xmark"></i>
                              </a>';
                            } else 
                            {
                                echo '
                                    <a class="btn btn-primary" href="index.php?views=login" style="margin-left:5px;">
                                        <i class="fa-solid fa-user"></i>
                                    </a>
                                ';
                            }
                  ?>
                </form>
            </div>
        </div>
    </nav>

    <header class="container">
        <div 
            id="salidas"
        >
              <?php 
              if(isset($msn) AND !empty($msn)){
                  echo "<div class='mensaje'>$msn</div>"; // Agregar clase 'mensaje'
              }
              if(isset($_GET['msn']) && !empty($_GET['msn'])){
                  echo '<div class="mensaje">'.$_GET['msn'].'</div>'; // Agregar clase 'mensaje'
              }
              ?>
        </div>
    </header>

