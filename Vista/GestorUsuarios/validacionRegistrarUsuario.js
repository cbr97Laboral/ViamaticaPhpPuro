
function validarUsername(inputElement) {
    let username = inputElement.value.trim();

    inputElement.value = username;

    const minLength = 8;
    const maxLength = 20;

    let msj = validarLongitud(username.length, minLength, maxLength);

    const pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{5,15}$/;
    if (!pattern.test(username)) {
        msj += '<br>Debe contener al menos una letra mayuscula, un número y no debe contener signos.';
    }

    document.getElementById('username-error').innerHTML = msj;
}

function validarNombres(inputElement) {
    validarNombresApellido(inputElement, 'nombres-error', `nombres`);
}

function validarApellidos(inputElement) {
    validarNombresApellido(inputElement, 'apellidos-error', `apellidos`);
}

function validarNombresApellido(inputElement, identificadorHtmlError, campo) {
    let msj = "";
    let nombresOapellido = inputElement.value.trim();
    inputElement.value = nombresOapellido;

    if (nombresOapellido === "") {
        msj = "No puede estar vacío";
        document.getElementById(identificadorHtmlError).innerHTML = msj;
        return;
    }

    const maxLength = 100;

    msj = validarLongitud(nombresOapellido.length, 1, maxLength);


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

function validarIdentificacion(inputElement) {
    let msj = '';
    let identificacion = inputElement.value.trim();

    identificacion = identificacion.replace(/\D/g, ''); // Elimina todo lo que no sea un dígito
    inputElement.value = identificacion;

    if (identificacion == "") {
        msj = "No puede estar vacío";
        document.getElementById('identificacion-error').innerHTML = msj;
        return;
    }

    console.log("IDENm");

    const maxLength = 10;

    msj = validarLongitud(identificacion.length, 10, maxLength);

    const pattern = /(.)\1{3}/;

    if (pattern.test(identificacion)) {
        msj += '<br>No puede tener 4 dígitos consecutivos iguales.';
    }

    inputElement.value = identificacion;

    document.getElementById('identificacion-error').innerHTML = msj;
}

function validarContrasena(inputElement) {
    const password = inputElement.value.trim();
    let msj = '';

    const minLength = 8;
    const maxLength = 100;

    msj = validarLongitud(password.length, minLength, maxLength);

    if (!/[A-Z]/.test(password)) {
        msj += '<br>Debe contener al menos una letra mayúscula.<br>';
    }

    if (/\s/.test(password)) {
        msj += '<br>No debe contener espacios.<br>';
    }

    if (!/[^\w\s]/.test(password)) {
        msj += 'Debe contener al menos un signo especial.<br>';
    }

    document.getElementById('contrasena-error').innerHTML = msj;
}

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('contrasena');
    const toggleButton = document.getElementById('toggle-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = '👁️';
    }
}


function validarLongitud(valor, minLength, maxLength) {
    let msj = "";
    if (valor < minLength) {
        msj = `Debe tener al menos ${minLength} caracteres.`;
    } else if (username.length > maxLength) {
        msj = `No debe exceder ${maxLength} caracteres.`;
    }

    return msj;
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (event) {
        let formularioValido = true;

        document.querySelectorAll('.error-message-registrar').forEach((element) => {
            element.innerHTML = '';
        });

        const usernameInput = document.getElementById('username');
        const nombresInput = document.getElementById('nombres');
        const apellidosInput = document.getElementById('apellidos');
        const identificacionInput = document.getElementById('identificacion');
        const contrasenaInput = document.getElementById('contrasena');

        validarUsername(usernameInput);
        validarNombres(nombresInput);
        validarApellidos(apellidosInput);
        validarIdentificacion(identificacionInput);
        validarContrasena(contrasenaInput);

        const errorMessages = document.querySelectorAll('.error-message-registrar');
        errorMessages.forEach(function (errorMessage) {
            if (errorMessage.innerHTML !== '') {
                formularioValido = false;
            }
        });

        if (!formularioValido) {
            event.preventDefault();
            alert('Por favor, corrige los errores antes de enviar el formulario.');
        }
    });

    //Cambiar por obtener por solicitud
    const roles = [
        { idRole: 1, NombreRole: 'Administrador' },
        { idRole: 2, NombreRole: 'Usuario' }
    ];
    const rolesSelect = document.getElementById('roles');

    rolesSelect.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccione un rol';
    rolesSelect.appendChild(defaultOption);

    roles.forEach(role => {
        const option = document.createElement('option');
        option.value = role.idRole;
        option.textContent = role.NombreRole;
        rolesSelect.appendChild(option);
    });
});
