document.addEventListener('DOMContentLoaded', function() {
    let formRegistro = document.getElementById('formRegistro');    

    if (formRegistro) {
        formRegistro.addEventListener('submit', function(event) {
            if (!validar()) {
                // Parar el evento por defecto del Submit
                event.preventDefault();
            }
        });
    }
    
    function validar() {
        let user = document.getElementById('userRegistro');
        let password1 = document.getElementById('password1');
        let password2 = document.getElementById('password2');
        let email1 = document.getElementById('email1');
        let email2 = document.getElementById('email2');
        let con = document.querySelector('input[name="conectado"]');
        let est = document.querySelector('input[name="estado"]');
        let esValido = true;

        // Validar usuario
        if (user && (user.value.length < 3 || user.value.length > 10)) {
            alert('El usuario debe tener entre 3 y 10 caracteres');
            user.style.border = '2px solid red';
            esValido = false;
        } else if (user) {
            user.style.border = '';
        }

        // Validar contraseñas
        if (password1 && password2 && password1.value !== password2.value) {
            alert('Las contraseñas no coinciden');
            password1.style.border = '2px solid red';
            password2.style.border = '2px solid red';
            esValido = false;
        } else if (password1 && password2) {
            password1.style.border = '';
            password2.style.border = '';
        }

        // Validar emails
        if (email1 && email2 && email1.value !== email2.value) {
            alert('Los emails no coinciden');
            email1.style.border = '2px solid red';
            email2.style.border = '2px solid red';
            esValido = false;
        } else if (email1 && email2) {
            email1.style.border = '';
            email2.style.border = '';
        }

        // Validar campos ocultos
        if (con && est && (con.value !== '0' || est.value !== '1')) {
            alert('Los campos ocultos tienen valores incorrectos');
            esValido = false;
        }

        return esValido;
    }
});