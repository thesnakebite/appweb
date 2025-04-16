document.addEventListener('DOMContentLoaded', () => {
    // Añadir transiciones suaves
    let mensajes = document.querySelectorAll('#salidas .mensaje')
    
    if(mensajes.length > 0) {
        mensajes.forEach((mensaje, index) => {
            setTimeout( () => {
                /* 
                 * IMPORTANTE: Sistema de animación de Bootstrap
                 * 
                 * Las alertas de Bootstrap utilizan las clases 'fade' y 'show' para sus animaciones:
                 * - 'fade': Activa la transición suave (opacity y otros efectos)
                 * - 'show': Controla el estado visible del elemento
                 * 
                 * Simplemente aplicar 'display: none' no respeta este sistema de animación.
                 * El enfoque correcto es:
                 * 1. Quitar la clase 'show' primero (esto inicia la animación de desvanecimiento)
                 * 2. Esperar que termine la animación (~300ms)
                 * 3. Eliminar completamente el elemento para liberar memoria y espacio en el DOM
                 */
                mensaje.classList.remove('show')

                // Esperamos a que termine la animación de desvanecimiento antes de eliminar el elemento
                setTimeout( ()=> {
                    mensaje.remove()

                    // Elimina el parámetro msn de la URL después de ocultar el mensaje
                    if (index === mensajes.length - 1) {
                        let url = new URL(window.location.href)
                        url.searchParams.delete('msn')
                        window.history.replaceState({}, document.title, url)
                    }
                }, 300) // esperar 300ms para la animación
                
            }, (index + 1) * 4000)
        })
    }
})