
function validarModalUsername(inputElement) {
    let username = inputElement.value.trim();

    inputElement.value = username;

    const minLength = 8;
    const maxLength = 20;

    let msj = validarModalLongitud(username.length, minLength, maxLength);

    const pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,20}$/;
    if (!pattern.test(username)) {
        msj += '<br>Debe contener al menos una letra mayuscula, un número y no debe contener signos.';
    }

    document.getElementById('username-modal-error').innerHTML = msj;
}

function validarModalNombres(inputElement) {
    validarModalNombresApellido(inputElement, 'nombres-modal-error', `nombres`);
}

function validarModalApellidos(inputElement) {
    validarModalNombresApellido(inputElement, 'apellidos-modal-error', `apellidos`);
}

function validarModalNombresApellido(inputElement, identificadorHtmlError, campo) {
    let msj = "";
    let nombresOapellido = inputElement.value.trim();
    inputElement.value = nombresOapellido;

    if (nombresOapellido === "") {
        msj = "No puede estar vacío";
        document.getElementById(identificadorHtmlError).innerHTML = msj;
        return;
    }

    const maxLength = 100;

    msj = validarModalLongitud(nombresOapellido.length, 1, maxLength);


    const pattern = /^[A-Z][a-z]+ [A-Z][a-z]+$/; // Expresión regular para exactamente dos nombres separados por un solo espacio

    const nombresOapellidoArray = nombresOapellido.split(' ');
    if (nombresOapellidoArray.length > 2) {
        nombresOapellido = nombresOapellidoArray.slice(0, 2).join(' ');
        inputElement.value = nombresOapellido;
    }

    if (!pattern.test(nombresOapellido)) {
        msj += `<br>Debe contener exactamente dos ${campo}, cada uno comenzando con una letra mayúscula y separados por un solo espacio.`;
    }

    document.getElementById(identificadorHtmlError).innerHTML = msj;
}

function validarModalIdentificacion(inputElement) {
    let msj = '';
    let identificacion = inputElement.value.trim();

    identificacion = identificacion.replace(/\D/g, ''); // Elimina todo lo que no sea un dígito
    inputElement.value = identificacion;

    if (identificacion == "") {
        msj = "No puede estar vacío";
        document.getElementById('identificacion-modal-error').innerHTML = msj;
        return;
    }

    const maxLength = 10;

    msj = validarModalLongitud(identificacion.length, 10, maxLength);

    const pattern = /(.)\1{3}/;

    if (pattern.test(identificacion)) {
        msj += '<br>No puede tener 4 dígitos consecutivos iguales.';
    }

    inputElement.value = identificacion;

    document.getElementById('identificacion-modal-error').innerHTML = msj;
}

function validarModalContrasena(inputElement) {
    const password = inputElement.value.trim();
    let msj = '';
    inputElement.value = password;
    if (password =="") {
        document.getElementById('contrasena-modal-error').innerHTML = msj;
        return;
    }

    const minLength = 8;
    const maxLength = 100;

    msj = validarModalLongitud(password.length, minLength, maxLength);

    if (!/[A-Z]/.test(password)) {
        msj += '<br>Debe contener al menos una letra mayúscula.<br>';
    }

    if (/\s/.test(password)) {
        msj += '<br>No debe contener espacios.<br>';
    }

    if (!/[^\w\s]/.test(password)) {
        msj += 'Debe contener al menos un signo especial.<br>';
    }

    document.getElementById('contrasena-modal-error').innerHTML = msj;
}

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('contrasena-modal');
    const toggleButton = document.getElementById('toggle-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = '👁️';
    }
}


function validarModalLongitud(valor, minLength, maxLength) {
    let msj = "";
    if (valor < minLength) {
        msj = `Debe tener al menos ${minLength} caracteres.`;
    } else if (username.length > maxLength) {
        msj = `No debe exceder ${maxLength} caracteres.`;
    }

    return msj;
}

function editarUsuarioModal() {
    let formularioValido = true;

    document.querySelectorAll('.error-message-modal').forEach((element) => {
        element.innerHTML = '';
    });

    const usernameInput = document.getElementById('username-modal');
    const nombresInput = document.getElementById('nombres-modal');
    const apellidosInput = document.getElementById('apellidos-modal');
    const identificacionInput = document.getElementById('identificacion-modal');
    const contrasenaInput = document.getElementById('contrasena-modal');

    validarModalUsername(usernameInput);
    validarModalNombres(nombresInput);
    validarModalApellidos(apellidosInput);
    validarModalIdentificacion(identificacionInput);
    validarModalContrasena(contrasenaInput);

    const errorMessages = document.querySelectorAll('.error-message-modal');
    errorMessages.forEach(function (errorMessage) {
        if (errorMessage.innerHTML !== '') {
            formularioValido = false;
        }
    });

    if (!formularioValido) {
        alert('Por favor, corrige los errores antes de enviar el formulario.');
        return;
    }

    editar();
}
