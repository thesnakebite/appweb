<?php

$RUTA_ABSOLUTA = dirname(__DIR__) . '/';
require_once($RUTA_ABSOLUTA . 'config.php');
require_once($RUTA_ABSOLUTA . 'models/TaskDB.php');

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

class TaskService
{
    private $formulario;
    private $tabla;
    private $nombre;
    private $tiempo;
    private $estado;
    private $tareas;
    private $tareasDB;
    private $modal;


    public function __construct()
    {
        $this->tareasDB = new TaskDB;
        $this->tareas = $this->buscarTareasDB();
        $this->setTabla();
        $this->setModal();
    }

    private function buscarTareasDB()
    {
        // Inicializar $tareas como un array vacío
        $tareas = [];

        $datos = $this->tareasDB->consultarTareasDB();

        if (isset($datos) and !empty($datos)) {
            $i = 0;
            /// $datos -> MYSQL_RESULT
            foreach ($datos as $dato) {
                $tareas[$i]['id'] = $dato['id'];
                $tareas[$i]['nombre'] = $dato['nombre'];
                $tareas[$i]['tiempo'] = $dato['tiempo'];
                $tareas[$i]['estado'] = $dato['estado'];
                $tareas[$i]['verificar'] = false;

                $i++;
            }
        }
        return $tareas;
    }

    private function setTabla()
    {
        $this->tabla = '
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card card-shadow card-secondary shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <h5 class="mb-0 text-dark">Listado de tareas</h5>

                                <button type="button" 
                                    class="btn btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalTarea" 
                                    title="Crear tarea"
                                >
                                    <i class="bi bi-person-plus-fill me-1"></i>
                                    Crear tarea
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Tiempo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    // Obtener las tareas (Devuelve un array)
                                    $this->tareas = $this->tareasDB->consultarTareasDB();

                                    // Verificamos si hay tareas
                                    if (isset($this->tareas) && !empty($this->tareas)) {
                                        foreach ($this->tareas as $tarea) {
                                            $this->tabla .= '<tr>';
                                            $this->tabla .= '<td>' . $tarea['id'] . '</td>';
                                            $this->tabla .= '<td>' . $tarea['nombre'] . '</td>';
                                            $this->tabla .= '<td>' . $tarea['tiempo'] . '</td>';
                                            $this->tabla .= '<td>';
                                            
                                            // Mostrar estado 
                                            if ($tarea['estado'] == 1) {
                                                $this->tabla .= '<span class="badge bg-success">Disponible</span>';
                                            } else {
                                                $this->tabla .= '<span class="badge bg-danger">No Disponible</span>';
                                            }
                                            
                                            $this->tabla .= '</td>';
                                            $this->tabla .= '<td class="text-center">
                                                <div class="btn-group grid gap-1" role="group">
                                                    <div class="p-1 g-col-6">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalTarea" 
                                                                data-id="' . $tarea['id'] . '" 
                                                                onclick="cargarTarea(' . $tarea['id'] . ')" 
                                                                title="Editar"
                                                        >
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </button>
                                                    </div>
                                                    <div class="p-1 g-col-6">
                                                        <a href="index.php?action=ELIMINAR_TAREA&id=' . base64_encode($tarea['id']) . '" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm(\'¿Estás seguro de eliminar esta tarea?\')" 
                                                           title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>';
                                            $this->tabla .= '</tr>';
                                        }
                                    } else {
                                        $this->tabla .= '
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="alert alert-info mb-0">
                                                        <i class="bi bi-info-circle-fill me-2"></i>
                                                        No hay tareas registradas en el sistema
                                                    </div>
                                                </td>
                                            </tr>';
                                    }
                                    $this->tabla .='
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        $this->tabla .= '
            </tbody>
        </table>';
    }

    public function getTabla()
    {
        return $this->tabla;
    }

    private function setModal($tarea = null) 
    {
        $esEdicion = $tarea !== null;
        $titulo = $esEdicion ? 'Editar Tarea' : 'Crear Nueva Tarea';
        $botonTexto = $esEdicion ? 'Actualizar Tarea' : 'Crear Tarea';
        $accion = $esEdicion ? 'UPDATE_TAREAS' : 'ADD_TAREAS';
        
        $id = $esEdicion ? $tarea['id'] : '';
        $nombre = $esEdicion ? $tarea['nombre'] : '';
        $tiempo = $esEdicion ? $tarea['tiempo'] : '';
        $estadoChecked = $esEdicion && $tarea['estado'] == 1 ? 'checked' : '';
        
        $this->modal = '
        <!-- Modal para Tarea -->
        <div class="modal fade" id="modalTarea" tabindex="-1" aria-labelledby="modalTareaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalTareaLabel">' . $titulo . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formTarea" method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="nombreTarea" class="form-label">Nombre de la tarea</label>
                                <input type="text" class="form-control" id="nombreTarea" name="nombre" value="' . $nombre . '" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tiempoTarea" class="form-label">Tiempo estimado (horas)</label>
                                <input type="date" class="form-control" id="tiempoTarea" name="tiempo" value="' . $tiempo . '" required>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="estadoTarea" name="estado" value="1" ' . $estadoChecked . '>
                                    <label class="form-check-label" for="estadoTarea">Tarea disponible</label>
                                </div>
                            </div>
                            
                            <input type="hidden" name="action" value="' . $accion . '">';

            if ($esEdicion) {
                $this->modal .= '
                                <input type="hidden" name="id" value="' . $id . '">';
            } else {
                // Añadir el parámetro de redirección para identificar que viene del panel de admin
                $this->modal .= '
                                <input type="hidden" name="redirect_to" value="tasks">';
            }

            $this->modal .= '
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" form="formTarea" class="btn btn-primary">' . $botonTexto . '</button>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function getModal()
    {
        $tarea = null;

        if (isset($_GET['id'])) {
            $tarea = $this->obtenerTareaPorId($_GET['id']);
        }

        $this->setModal($tarea);

        return $this->modal;
    }

    public function addTarea($datos) {
        // Si estado no está definido, establecerlo a 0
        if (!isset($datos['estado'])) {
            $datos['estado'] = 0;
        }
        
        $respuesta = $this->tareasDB->addTareas($datos); // Nota que llama a addTareas con "s"
        
        if ($respuesta) {
            return ['status' => 'success', 'message' => 'Tarea agregada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al agregar la tarea.'];
        }
    }

    public function obtenerTareaPorId($id) 
    {
        return $this->tareasDB->consultarTareaById($id);
    }

    public function updateTarea($datos)
    {
        // Si estado no está definido, establecerlo a 0
        if (!isset($datos['estado'])) {
            $datos['estado'] = 0;
        }

        $respuesta = $this->tareasDB->updateTareasDB($datos);

        if ($respuesta) {
            return ['status' => 'success', 'message' => 'Tarea actualizada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al actualizar la tarea.'];
        }
    }

    public function eliminarTarea($id)
    {
        $resultado = $this->tareasDB->deleteTareasDB($id);
        
        if ($resultado == 1) {
            return '<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Éxito!</strong> Tarea eliminada correctamente.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } else {
            return '<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>¡Error!</strong> No se pudo eliminar la tarea.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
}

