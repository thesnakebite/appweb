<?php

function displayMessages() 
{
    $output = '';

    // Comprobar mensajes por GET
    if(isset($_GET['msn']) && !empty($_GET['msn'])){
        $mensaje = urldecode($_GET['msn']);

        // Separar el tipo de mensaje y su contenido
        $partes = explode(':', $mensaje, 2);

        if(count($partes) == 2) {
            $tipo = $partes[0];  // 'success', 'error' o 'warning'
            $texto = $partes[1];  // El mensaje en sí

            // Determinar la clase de alerta según el tipo
            if ($tipo === 'success') {
                $clase = 'alert-success';
                $icono = 'check-circle-fill';
                $titulo = '¡Éxito!';
            } elseif ($tipo === 'warning') {
                $clase = 'alert-warning';
                $icono = 'exclamation-triangle-fill';
                $titulo = '¡Atención!';
            } else { // 'error' u otros
                $clase = 'alert-danger';
                $icono = 'exclamation-triangle-fill';
                $titulo = '¡Error!';
            }

            $output .= '
                <div class="alert '.$clase.' alert-dismissible fade show d-flex align-items-center mensaje" role="alert">
                    <i class="bi bi-'.$icono.' me-2 fs-4"></i>
                    <div>
                        <strong>'.$titulo.'</strong> '.$texto.'
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            } else {
                // Si no tiene el formato esperado, mostrar como texto plano
                $output .= '<div class="alert alert-info">'.$mensaje.'</div>';
            }
        }

        // Comprobar variable local $msn
        global $msn;

        if(isset($msn) && !empty($msn)) {
            $output .="<div class='mensaje'>$msn</div>";
        }

        return $output;
}