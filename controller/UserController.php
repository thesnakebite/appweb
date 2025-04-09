<?php
$RUTA_ABSOLUTA = dirname(__DIR__) . '/';
require_once($RUTA_ABSOLUTA . 'config.php');
require_once($RUTA_ABSOLUTA . 'models/User.php');

$usuarios = new Usuario();


// if($_POST){
//     if(isset($_POST['action']) && !empty($_POST['action'])){
//         if($_POST['action'] == 'REG_USUARIOS'){      
//             $id = $usuarios->registrarUsuario($_POST);                
//             $msn = $usuarios->SubirFoto($_FILES,$id);
//             header('location:'.RUTA_WEB.'index.php?views=users&msn='.$msn);                  
//         }
//         if($_POST['action'] == 'LOGIN_USER'){
//             $msn = $usuarios->loginUsuarios($_POST);  
//             header('location:'.RUTA_WEB.'index.php?views=dashboard&msn='.$msn);               
//         }
//         if($_POST['action'] == 'UPDATE_USUARIOS'){
//             $msn = $usuarios->updateUsuario($_POST); 
//             if(isset($_POST['id']) && !empty($_POST['id'])){
//                 $id = $_POST['id'];
//                 $msn = $usuarios->SubirFoto($_FILES,$id);
//                 header('location:'.RUTA_WEB.'index.php?views=users&msn='.$msn);           
//             }               
//         }
// 
//     }       
// }

// if($_GET){  
//     
//     if(isset($_GET['action']) && !empty($_GET['action'])){
//         if($_GET['action'] == 'CERRAR_SESSION'){
//             $usuarios->cerrarSesion();
//         }
// 
//         if($_GET['action'] == 'borraruser'){
//             $id = base64_decode($_GET['id']);
//             $usuarios->DeleteUsuario($id);
//         }
// 
//         if($_GET['action'] == 'MODIFCAR_ESTADO'){
//             $id = htmlspecialchars(trim($_GET['id']));
//             $usuarios->ModificarEstado($id);
//             
//         }
// 
//         if($_GET['action'] == 'edituser'){
//             
//         }
//         
//                  
//     }
// }

class Usuario
{
    private $formRegistro;
    private $formLogin;
    private $userDB;
    private $table;
    private $modal;

    public function __construct()
    {
        $this->userDB = new UsuariosDB();
        $this->setFormRegister();
        $this->setFormLogin();
        $this->setTable();
        $this->setModal();
    }

    private function setFormRegister()
    {
        $this->formRegistro = '
        <form
                action="index.php"
                method="POST"
                id="formRegistro"
                class="border border-white p-4 rounded-4"
                enctype="multipart/form-data"
            >

                <h4 class="text-center text-white">Registro</h4>

                <div class="mb-3">
                    <label for="user" class="form-label">Usuario</label>
                    <input
                        class="form-control"
                        id="userRegistro"
                        name="nombre" 
                    />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password1"
                        name="password" 
                    />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Confirmar Contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password2"
                        name="password" 
                    />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email1"
                        name="email" 
                    />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Confirmar Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email2"
                        name="email" 
                    />
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input
                        type="file"
                        class="form-control-file"
                        name="foto" 
                    />
                </div>

                <input type="hidden" name="conectado" value="0">
                <input type="hidden" name="estado" value="1">

                <input type="hidden" name="action" value="REG_USUARIOS" />
                <input type="submit" class="btn btn-secondary" value="Registrar" />
                <input type="reset" class="btn btn-danger" value="Reset" />
            </form>
        ';
    }

    public function getFormRegister()
    {
        $this->setFormRegister();

        return $this->formRegistro;
    }

    public function registrarUsuario($datos)
    {
        // Verificamos si hay un archivo subido
        if(isset($_FILES) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $rutaFoto = $this->userDB->updatePhoto($_FILES);

            if($rutaFoto) {
                // Añadimo la ruta de la foto a los datos 
                $datos['foto'] = $rutaFoto;
            }
        }

        $respuesta = $this->userDB->addUsuarios($datos);

        if ($respuesta == 1) {
            return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Éxito!</strong> Usuario registrado correctamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } else {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> No se pudo registrar el usuario. Intenta nuevamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }

    private function setFormLogin()
    {
        $this->formLogin = '
            <form
                action="index.php"
                method="POST"
                class="border border-white p-4 rounded-4">
                <h4 class="text-center text-white">Acceso</h4>
                <div class="mb-3">
                    <label for="user" class="form-label">E-Mail</label>
                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        required>
                </div>

                <input type="hidden" name="action" value="LOGIN_USER">

                <input type="submit" class="btn btn-secondary" value="Acceder">
                <input type="reset" class="btn btn-danger" value="Reset">
            </form>
        ';
    }

    public function getFormLogin()
    {
        $this->setFormLogin();

        return $this->formLogin;

        if ($respuesta == -1) {
            $msn = '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>
                <strong>¡Error!</strong> No se pudo registrar el usuario. Intenta nuevamente.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        } else {

            if ($userDB['estado'] == 1) {

                if (md5($datos['password']) == $userDB['password']) {

                    $_SESSION['email'] = $datos['email'];
                    $_SESSION['nombre'] = $userDB['nombre'];
                } else {
                    $msn = '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div>
                        <strong>¡Error!</strong> No se pudo registrar el usuario. Intenta nuevamente.
                    </div>
                    </div>';
                }
            }

            $msn = '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
            <div>
                <strong>¡Éxito!</strong> Usuario registrado correctamente.
            </div>
            </div>';
        }
    }

    public function loginUsuario($datos)
    {
        $respuesta = $this->userDB->loginUser($datos);

        if ($respuesta === false) {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> Usuario no encontrado. Verifica tus credenciales.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } else {

            if ($respuesta['estado'] == 1) {
                if (md5($datos['password']) == $respuesta['password']) {
                    $_SESSION['user'] = $respuesta['nombre'];
                    $_SESSION['email'] = $datos['email'];
                    $_SESSION['id'] = $respuesta['id'];

                    $this->actualizarEstadoConexion($respuesta['id'], 1);

                    return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>
                            <strong>¡Bienvenido!</strong> Has iniciado sesión correctamente.
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                } else {
                    return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                        <div>
                            <strong>¡Error!</strong> Contraseña incorrecta. Intenta nuevamente.
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            } else {
                return '<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div>
                        <strong>¡Atención!</strong> Tu cuenta está desactivada. Contacta al administrador.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
    }

    public function logout()
    {
        if (isset($_SESSION['id'])) {
            $this->actualizarEstadoConexion($_SESSION['id'], 0);
        }

        session_destroy();

        unset($_SESSION);

        header('Location: index.php');
        exit();
    }

    private function setTable()
    {
        $this->table = '
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-shadow card-secondary shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <h5 class="mb-0 text-dark">Listado de usuarios</h5>
                                
                                <button type="button" 
                                    class="btn btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUsuario" 
                                    title="Crear usuario"
                                >
                                <i class="bi bi-person-plus-fill me-1"></i>
                                Crear usuario
                            </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">USUARIO</th>
                                            <th class="text-center">EMAIL</th>
                                            <th class="text-center">CONECTADO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                <tbody>';

                                $usuarios = $this->userDB->getUsuarios();

                                // Verificamos si hay usuarios
                                if ($usuarios != -1) {
                                    foreach ($usuarios as $usuario) {

                                        $estadoConexion = ($usuario['conectado'] == 1) ?
                                            '<span class="badge rounded-pill bg-success p-2">Activo</span>' :
                                            '<span class="badge rounded-pill bg-danger text-white p-2">Inactivo</span>';

                                        $estadoCuenta = ($usuario['estado'] == 1) ?
                                            '<div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck'.$usuario['id'].'" '.($usuario['estado'] == 1 ? 'checked' : '').' onchange="modificarEstado('.$usuario['id'].')">
                                            </div>' :
                                            '<div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck'.$usuario['id'].'" onchange="modificarEstado('.$usuario['id'].')">
                                            </div>';
    
                                            $this->table .= '<td class="d-flex justify-content-around">';
                                            $this->table .= '<div class="d-flex align-items-center justify-content-start gap-3">';
                                            $this->table .= '<div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">';
                                            
                                            // Comprobar si tiene foto
                                            if(!empty($usuario['foto'])) {
                                                $this->table .= '<img src="' . $usuario['foto'] . '" alt="' . $usuario['nombre'] . '" style="width: 100%; height: 100%; object-fit: cover;">';
                                            } else {
                                                $this->table .= '<div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">'
                                                    . strtoupper(substr($usuario['nombre'], 0, 1)) .
                                                    '</div>';
                                            }
                                            
                                            $this->table .= '</div>';
                                            $this->table .= '<span>' . htmlspecialchars($usuario['nombre']) . '</span>';
                                            $this->table .= '</td>';

                                            $this->table .= '</td>';

                                            $this->table .= '<td class="text-center"><a href="mailto:' . $usuario['email'] . '" class="text-decoration-none">' . $usuario['email'] . '</a></td>
                                                <td class="text-center">' . $estadoConexion . '</td>
                                                <td class="text-center">' . $estadoCuenta . '</td>
                                                <td class="text-center">
                                                    <div class="btn-group grid gap-1" role="group">
                                                        <div class="p-1 g-col-6">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-primary" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#modalUsuario" 
                                                                    data-id="' . $usuario['id'] . '" 
                                                                    onclick="cargarUsuario(' . $usuario['id'] . ')" 
                                                                    title="Editar"
                                                            >
                                                                <i class="bi bi-pencil-fill"></i>
                                                            </button>
                                                        </div>
                                                        <div class="p-1 g-col-6">
                                                            <a href="index.php?action=ELIMINAR_USUARIO&id=' . base64_encode($usuario['id']) . '" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm(\'¿Estás seguro de eliminar este usuario?\')" title="Eliminar">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>';
                                        }
                                    } else {
                                        $this->table .= '
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="alert alert-info mb-0">
                                                        <i class="bi bi-info-circle-fill me-2"></i>
                                                        No hay usuarios registrados en el sistema
                                                    </div>
                                                </td>
                                            </tr>';
                                        }

                                $totalUsuarios = ($usuarios !== -1) ? count($usuarios) : 0;

                                $this->table .= '
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-dark">
                                                    Total de usuarios: 
                                                    <span class="badge bg-primary rounded-pill text-white" style="font-size: 1rem;">' . $totalUsuarios . '</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    }

    public function getTable()
    {
        return $this->table;
    }

    private function setModal($usuario = null) 
    {
        $esEdicion = $usuario !== null;
        $titulo = $esEdicion ? 'Editar Usuario' : 'Crear Nuevo Usuario';
        $botonTexto = $esEdicion ? 'Actualizar Usuario' : 'Crear Usuario';
        $accion = $esEdicion ? 'ACTUALIZAR_USUARIO' : 'REG_USUARIOS';
        
        $id = $esEdicion ? $usuario['id'] : '';
        $nombre = $esEdicion ? $usuario['nombre'] : '';
        $email = $esEdicion ? $usuario['email'] : '';
        $estadoChecked = $esEdicion && $usuario['estado'] == 1 ? 'checked' : '';
        
        $this->modal = '<!-- Modal para Usuario -->
        <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalUsuarioLabel">' . $titulo . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formUsuario" method="POST" action="index.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombreUsuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="nombreUsuario" name="nombre" value="' . $nombre . '" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="passwordUsuario" class="form-label">Contraseña' . ($esEdicion ? ' (dejar en blanco para mantener la actual)' : '') . '</label>
                                <input type="password" class="form-control" id="passwordUsuario" name="password" ' . (!$esEdicion ? '' : '') . '>
                            </div>
                            
                            <div class="mb-3">
                                <label for="emailUsuario" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailUsuario" name="email" value="' . $email . '" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="fotoUsuario" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="fotoUsuario" name="foto">
                            </div>';
        
                            // Mostrar la imagen actual si existe y estamos en modo edición
                            if ($esEdicion && isset($usuario['foto']) && !empty($usuario['foto'])) {
                                $this->modal .= '
                                                <div class="mb-3">
                                                    <label class="form-label">Foto actual</label>
                                                    <div class="text-center">
                                                        <img src="' . $usuario['foto'] . '" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                </div>';
                            }
        
                            $this->modal .= '              
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="estadoUsuario" name="estado" value="1" ' . $estadoChecked . '>
                                                        <label class="form-check-label" for="estadoUsuario">Cuenta activa</label>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="conectado" value="0">
                                                <input type="hidden" name="action" value="' . $accion . '">';
        
                            if ($esEdicion) {
                                $this->modal .= '
                                                <input type="hidden" name="id" value="' . $id . '">';
                            }
        
                            $this->modal .= '
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" form="formUsuario" class="btn btn-primary">' . $botonTexto . '</button>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }

    public function getModal()
    {
        $usuario = null;

        if (isset($_GET['id'])) {
            $usuario = $this->obtenerUsuarioPorId($_GET['id']);
        }

        $this->setModal($usuario);

        return $this->modal;
    }

    public function actualizarUsuario($datos) 
    {
        // Verificamos si hay un archivo subidio
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $rutaFoto = $this->userDB->updatePhoto($_FILES);

            if($rutaFoto) {
                $datos['foto'] = $rutaFoto;  // Añadimos la ruta de la foto a los datos
            }
        }
        
        // Llamar al método del modelo
        $resultado = $this->userDB->updateUser($datos);
        
        if ($resultado) {
            return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Éxito!</strong> Usuario actualizado correctamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } else {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> No se pudo actualizar el usuario.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }

    public function obtenerUsuarioPorId($id) 
    {
        return $this->userDB->getUsuarioPorId($id);
    }

    public function eliminarUsuario($id)
    {
        $resultado = $this->userDB->deleteUser($id);

        if ($resultado == 1) {
            return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Éxito!</strong> Usuario eliminado correctamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }  {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> No se pudo eliminar el usuario.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }

    public function modificarEstado($id)
    {
        $resultado = $this->userDB->updateStateDB($id);
        
        if ($resultado) {
            return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Éxito!</strong> Estado modificado correctamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } else {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> No se pudo modificar el estado.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }

    public function actualizarEstadoConexion($id, $conectado)
    {
        return $this->userDB->updateConnected($id, $conectado);
    }
}
?>