<style>
    #modalTarea .modal-content {
        background-color: white;
        color: #333;
    }
    
    #modalTarea .form-control {
        background-color: #fff;
        border: 1px solid #ced4da;
        color: #212529;
    }
    
    #modalTarea .form-control:focus {
        background-color: #fff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        color: #212529;
    }
    
    #modalTarea .form-control::placeholder {
        color: #6c757d;
    }
    
    #modalTarea .form-label {
        color: #212529;
        font-weight: 500;
    }
    
    #modalTarea .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    
    #modalTarea .modal-footer {
        border-top: 1px solid #dee2e6;
    }
</style>


<?php
    // Necesitamos una instancia de la clase
    $tasks = new TaskService();
?>

<section class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Gestión de tareas</h2>

            <!-- Mostrar mensajes de sistema -->
            <div id="mensajes">
                <?php 
                if(isset($msn) && !empty($msn)){
                    // Determinar si es un mensaje de éxito o error
                    $tipoMensaje = (strpos(strtolower($msn), 'error') !== false || 
                                   strpos(strtolower($msn), 'no se pudo') !== false) 
                                   ? 'danger' : 'success';
                    
                    // Crear un div con la clase de alerta correspondiente
                    echo '<div class="alert alert-'.$tipoMensaje.' alert-dismissible fade show" role="alert">';
                    echo '<i class="bi bi-'.($tipoMensaje == 'danger' ? 'exclamation-triangle' : 'check-circle').'"></i> ';
                    echo $msn;
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
                ?>
            </div>

            <!-- Tabla de tareas -->
            <?php echo $tareas->getTabla(); ?>

            <!-- Modal para crear/editar usuarios -->
            <?php echo $tasks->getModal(); ?>
        </div>
    </div>
</section>

<script>
    function cargarTarea(id) {
        // Petición AJAX
        fetch('index.php?action=OBTENER_TAREA_JSON&id=' + id)
            .then(response => response.json())
            .then(tarea => {
                // Cambiar el título del modal
                document.getElementById('modalTareaLabel').textContent = 'Editar Tarea';
                
                // Llenar los campos con los datos de la tarea
                document.getElementById('nombreTarea').value = tarea.nombre;
                document.getElementById('tiempoTarea').value = tarea.tiempo;
                
                // Para el checkbox de estado
                document.getElementById('estadoTarea').checked = tarea.estado == 1;
                
                // Cambiar la acción del formulario
                document.querySelector('#formTarea input[name="action"]').value = 'UPDATE_TAREAS';
                
                // Añadir campo oculto para el ID
                let idInput = document.querySelector('#formTarea input[name="id"]');
                if (!idInput) {
                    idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'id';
                    document.getElementById('formTarea').appendChild(idInput);
                }
                idInput.value = id;

                // Cambiar el texto del botón
                document.querySelector('#modalTarea .modal-footer button[type="submit"]').textContent = 'Actualizar Tarea';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudieron cargar los datos de la tarea');
            });
    }
</script>