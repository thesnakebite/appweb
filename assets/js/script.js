document.addEventListener('DOMContentLoaded', () => {
    // Añadir transiciones suaves
    let mensajes = document.querySelectorAll('#salidas .mensaje');
    
    function startCounter() {
        mensajes.forEach((mensaje, index) => {
           setTimeout(() => {
               mensaje.style.display = "none";
           }, (index + 1) * 4000);
       });
   }

   // Llamada a la función solo una vez dentro del evento DOMContentLoaded
   startCounter(); 
});