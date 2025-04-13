document.addEventListener('DOMContentLoaded', () => {
    // Añadir transiciones suaves
    let mensajes = document.querySelectorAll('#salidas .mensaje')
    
    if(mensajes.length > 0) {
        mensajes.forEach((mensaje, index) => {
            setTimeout(() => {
                mensaje.style.display = "none"
                
                // Elimina el parámetro msn de la URL después de ocultar el mensaje
                if (index === mensajes.length - 1) {
                    let url = new URL(window.location.href)
                    url.searchParams.delete('msn')
                    window.history.replaceState({}, document.title, url)
                }
            }, (index + 1) * 4000)
        })
    }
})