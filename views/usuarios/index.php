<style>
    #modalUsuario .modal-content {
        background-color: white;
        color: #333;
    }
    
    #modalUsuario .form-control {
        background-color: #fff;
        border: 1px solid #ced4da;
        color: #212529;
    }
    
    #modalUsuario .form-control:focus {
        background-color: #fff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        color: #212529;
    }
    
    #modalUsuario .form-control::placeholder {
        color: #6c757d;
    }
    
    #modalUsuario .form-label {
        color: #212529;
        font-weight: 500;
    }
    
    #modalUsuario .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    
    #modalUsuario .modal-footer {
        border-top: 1px solid #dee2e6;
    }
</style>

<?php
// Necesitamos una instancia de la clase
$userController = new UserService();

// Verificamos si el usuario tiene sesión iniciada
if(!isset($_SESSION['user'])) {
    // Redirigir a login si nohay sesión
    header('Location: index.php?views=login');
    exit;
}

?>

<section class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Gestión de usuarios</h2>

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

            <!-- Tabla de usuarios -->
            <?php echo $userController->getTable(); ?>

            <!-- Modal para crear/editar usuarios -->
             <?php echo $userController->getModal(); ?>
        </div>
    </div>
</section>

<script>
    function vistaPrevia() {
        const $inputFoto = $('#fotoUsuario')

        // Añadimos un listener
        $inputFoto.on('change', function() {

            const archivo = this.files[0]
            // Verificamos si se selecciona el archivo
            if (archivo) {
                let $vistaPrevia = $('#previewContainer')

                // Si no existe, crearlo
                if ($vistaPrevia.length === 0) {
                    $vistaPrevia = $(
                        '<div id="previewContainer" class="mb-3">' +
                            '<label class="form-label">Vista previa de la nueva foto</label>' +
                            '<div class="text-center">' +
                            '<img id="imgPreview" class="img-thumbnail" style="max-height: 100px;">' +
                            '</div>' +
                        '</div>'
                    )
                
                    // Insertamos imagen
                    $(this).closest('.mb-3').after($vistaPrevia)
                }

                // Crear un objeto URL para el archivo
                const urlArchivo = URL.createObjectURL(archivo)

                // Asignar la Url a la imagen de la vista previa
                $('#imgPreview').attr('src', urlArchivo)

                // Mostrar la sección de vista previa
                $vistaPrevia.show()
            } else {
                // Si no hay archivo seleccionado, ocultar la vista previa
                $('#previewContainer').hide()
            }
        }
    )}

    function cargarUsuario(id) {

        // Petición AJAX
        fetch('index.php?action=OBTENER_USUARIO_JSON&id=' + id)
            .then(response => response.json())
            .then(usuario => {
                // Cambiar el título del modal
                document.getElementById('modalUsuarioLabel').textContent = 'Editar Usuario'
                
                // Llenar los campos con los datos del usuario
                document.getElementById('nombreUsuario').value = usuario.nombre
                document.getElementById('emailUsuario').value = usuario.email
                
                // Para el checkbox de estado
                document.getElementById('estadoUsuario').checked = usuario.estado == 1
                // Cambiar el texto del botón
                document.querySelector('#modalUsuario .modal-footer button[type="submit"]').textContent = 'Actualizar Usuario';
                
                // Mostrar la imagen actual si existe
                const fotoContainer = document.querySelector('#modalUsuario .modal-body .mb-3:nth-child(4) + div')
                
                // Si ya existe una imagen previa, eliminarla para evitar duplicados
                if (fotoContainer && fotoContainer.classList.contains('mb-3')) {
                    fotoContainer.remove()
                }
                
                // Si el usuario tiene foto, mostrarla
                if (usuario.foto && usuario.foto.trim() !== '') {
                    const fotoDiv = document.createElement('div')
                    fotoDiv.className = 'mb-3'
                    fotoDiv.innerHTML = `
                        <label class="form-label">Foto actual</label>
                        <div class="text-center">
                            <img src="${usuario.foto}" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    `
                    
                    // Insertar después del input de la foto
                    const fotoInput = document.getElementById('fotoUsuario').closest('.mb-3')
                    fotoInput.insertAdjacentElement('afterend', fotoDiv)
                }
                
                // Cambiar la acción del formulario
                document.querySelector('#formUsuario input[name="action"]').value = 'ACTUALIZAR_USUARIO'
                
                // Añadir campo oculto para el ID
                let idInput = document.querySelector('#formUsuario input[name="id"]')
                if (!idInput) {
                    
                    idInput = document.createElement('input')
                    idInput.type = 'hidden'
                    idInput.name = 'id'
                    document.getElementById('formUsuario').appendChild(idInput)
                }
                idInput.value = id

                // Inicializamos la funcionalidad de vista previa
                vistaPrevia()
            })
            .catch(error => {

                console.error('Error:', error)
                alert('No se pudieron cargar los datos del usuario')
            })

            vistaPrevia()
    }

    async function modificarEstado(id) {
        // Deshabilitamos el switch mientras se procesa para evitar clics múltiples
        document.getElementById('flexSwitchCheck' + id).disabled = true
        
        try {
            const response = await fetch(`index.php?action=MODIFICAR_ESTADO&id=${id}`)
            const data = await response.json()
            
            // Redirigir a la misma página pero con el parámetro msn para mostrar el mensaje
            let msn = `${data.status}:${data.message}`;
            window.location.href = `index.php?views=users&msn=${encodeURIComponent(msn)}`
            
        } catch (error) {
            console.error('Error:', error)
            // En caso de error, también redirigir pero con mensaje de error
            window.location.href = `index.php?views=users&msn=${encodeURIComponent('error:Error al procesar la solicitud.')}`
        }
    }
</script>