<?php

$RUTA_ABSOLUTA = dirname(__DIR__) . '/';
require_once($RUTA_ABSOLUTA . 'config.php');
require_once($RUTA_ABSOLUTA . 'libs/UserService.php');

$usuario = new UserService();
$msn = '';

if ($_POST) {
    if (isset($_POST['action']) && !empty($_POST['action'])) {

        $datos = [];

        // Procesar lso datos del POST (sanitizando)
        foreach ($_POST as $key => $value) {
            // A cada valor para prevenir ataques XSS (Cross-Site Scripting)
            // excluimos ela campo action del array, ya que ese campo solo
            // se usa para determinar que acción realizar
            if ($key != 'action') {
                $datos[$key] = htmlspecialchars($value);
            }
        }

        switch ($_POST['action']) {
            case 'REG_USUARIOS':
                $resultado = $usuario->registerUserAccount($datos);
                // Convertir el resultado a un formato simple para pasar por URL
                $msn = $resultado['status'] . ':' . $resultado['message'];
                // Verificar si hay un parámetro de redirección personalizado
                $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'login';

                if ($resultado['status'] === 'success') {
                    // Si el registro es desde el panel de admin, redirigir a la lista de usuarios
                    if ($redirect_to === 'users') {
                        header('Location:' . RUTA_WEB . 'index.php?views=users&msn=' . urlencode($msn));
                    } else {
                        // Si es desde el formulario público, redirigir al login
                        header('Location:' . RUTA_WEB . 'index.php?views=login&msn=' . urlencode($msn));
                    }
                } else {
                    // Si hubo error, redirigir de acuerdo al parámetro
                    if ($redirect_to === 'users') {
                        header('Location:' . RUTA_WEB . 'index.php?views=users&msn=' . urlencode($msn));
                    } else {
                        header('Location:' . RUTA_WEB . 'index.php?views=login&msn=' . urlencode($msn));
                    }
                }
                exit;
                break;

            case 'LOGIN_USER':
                $resultado = $usuario->loginUsuario($datos);
                $msn = $resultado['status'] . ':' . $resultado['message'];
                
                // Si el login fue exitoso, redirigir al dashboard
                if ($resultado['status'] === 'success') {
                    header('Location:' . RUTA_WEB . 'index.php?views=dashboard&msn=' . urlencode($msn));
                } else {
                    // Si hubo error, redirigir de vuelta al login
                    header('Location:' . RUTA_WEB . 'index.php?views=login&msn=' . urlencode($msn));
                }
                exit;
                break;

            case 'ACTUALIZAR_USUARIO':
                $msn = $usuario->actualizarUsuario($datos);
                // Redirigir de vuelta a la lista de usuarios
                header('Location:' . RUTA_WEB . 'index.php?views=users&msn=' . urlencode('success:Usuario actualizado correctamente'));
                exit;
                break;

            default:
                break;
        }
    }
}

if ($_GET) {
    if(isset($_GET['action']) && !empty($_GET['action'])){
        switch($_GET['action']) {
            case 'CERRAR_SESION':
                $usuario->logout();
                break;
                
            case 'OBTENER_USUARIO_JSON':
                if(isset($_GET['id'])) {
                    $usuarioData = $usuario->obtenerUsuarioPorId($_GET['id']);
                    header('Content-Type: application/json');
                    echo json_encode($usuarioData);
                    exit;
                }
                break;
            
            case 'MODIFICAR_ESTADO':
                if (isset($_GET['id'])) {
                    $id = htmlspecialchars(trim($_GET['id']));
                    $resultado = $usuario->modificarEstado($id);

                    header('Content-Type: application/json');
                    if (strpos($resultado, 'Éxito') !== false) {
                        echo json_encode(['status' => 'success', 'message' => 'Estado modificado correctamente.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'No se pudo modificar el estado.']);
                    }
                    exit(0);
                }
                break;

            case 'ELIMINAR_USUARIO':
                if(isset($_GET['id'])) {
                    $id = base64_decode($_GET['id']);
                    $resultado = $usuario->eliminarUsuario($id);
                    
                    // Convertir el resultado a un formato simple para pasar por URL
                    $tipo = (strpos($resultado, 'Éxito') !== false) ? 'warning' : 'error';
                    $mensaje = ($tipo === 'warning') ? 'Usuario eliminado del sistema.' : 'No se pudo eliminar el usuario.';
                    $msn = $tipo . ':' . $mensaje;
                    
                    // Redirigir de vuelta a la lista de usuarios
                    header('Location:' . RUTA_WEB . 'index.php?views=users&msn=' . urlencode($msn));
                    exit;
                }
                break;
            }
    }
}