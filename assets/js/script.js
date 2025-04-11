document.addEventListener('DOMContentLoaded', () => {
    // Añadir transiciones suaves
    let mensajes = document.querySelectorAll('#salidas .mensaje')
    
    function startCounter() {
        mensajes.forEach((mensaje, index) => {
           setTimeout(() => {
               mensaje.style.display = "none"

               // Elimina el parámetro msn de la URL después de ocultar el mensaje
               if (index === mensajes.length - 1) {
                    let url = new URL(window.location.href)

                    url.searchParams.delete('msn')
                    window.history.replaceState( {}, document.title, url)
               }
           }, (index + 1) * 4000)
       })

       if(mensajes.length > 0) {
           // Llamada a la función solo una vez dentro del evento DOMContentLoaded
           startCounter()
       }
   }

   startCounter()
})