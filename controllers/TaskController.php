<?php

$RUTA_ABSOLUTA = dirname(__DIR__) . '/';
require_once($RUTA_ABSOLUTA . 'config.php');
require_once($RUTA_ABSOLUTA . 'libs/TaskService.php');

$tareas = new TaskService();
$msn = '';

if ($_POST) {
    if (isset($_POST['action']) && !empty($_POST['action'])) {

        $datos = [];

        // Sanitizar datos del POST
        foreach ($_POST as $key => $value) {
            if ($key != 'action' && $key != 'redirect_to') {
                $datos[$key] = htmlspecialchars($value);
            }
        }

        switch ($_POST['action']) {
            case 'ADD_TAREAS':
                $resultado = $tareas->addTarea($datos);

                // Convertir el resultado a un formato simple para pasar por URL
                $msn = $resultado['status'] . ':' . $resultado['message'];
                
                // Verificar si hay un parámetro de redirección personalizado
                $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'tasks';
                
                // Redirigir a la página de tareas
                header('Location:' . RUTA_WEB . 'index.php?views=' . $redirect_to . '&msn=' . urlencode($msn));
                exit;
                break;

            case 'UPDATE_TAREAS':
                $resultado = $tareas->updateTarea($datos);

                $msn = $resultado['status'] . ':' . $resultado['message'];                
                header('Location:' . RUTA_WEB . 'index.php?views=tasks&msn=' . urlencode($msn));
                exit;
                break;
        }
    }
}

if ($_GET) {
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        switch ($_GET['action']) {
            case 'OBTENER_TAREA_JSON':
                if(isset($_GET['id'])) {
                    $tareaData = $tareas->obtenerTareaPorId($_GET['id']);
                    header('Content-Type: application/json');
                    echo json_encode($tareaData);
                    exit;
                }
                break;

            case 'ELIMINAR_TAREA':
                if(isset($_GET['id'])) {
                    $id = base64_decode($_GET['id']);
                    $resultado = $tareas->eliminarTarea($id);
                    
                    $tipo = (strpos($resultado, 'Éxito') !== false) ? 'warning' : 'error';
                    $mensaje = ($tipo === 'warning') ? 'Tarea eliminada del sistema.' : 'No se pudo eliminar la tarea.';
                    $msn = $tipo . ':' . $mensaje;
                    
                    // Redirigir de vuelta a la lista de usuarios
                    header('Location:' . RUTA_WEB . 'index.php?views=tasks&msn=' . urlencode($msn));
                    exit;
                }
                break;
        }
    }
}