
function validarUsername(inputElement) {
    let username = inputElement.value.trim();

    inputElement.value = username;

    const minLength = 0;
    const maxLength = 20;

    let msj = validarLongitud(username.length, minLength, maxLength);

    document.getElementById('username-error').innerHTML = msj;
}

function validarNombres(inputElement) {
    validarNombresApellido(inputElement, 'nombres-error');
}

function validarApellidos(inputElement) {
    validarNombresApellido(inputElement, 'apellidos-error');
}

function validarNombresApellido(inputElement, identificadorHtmlError) {
    let msj = "";
    let nombresOapellido = inputElement.value.trim();
    inputElement.value = nombresOapellido;

    const maxLength = 100;

    msj = validarLongitud(nombresOapellido.length, 1, maxLength);

    const nombresOapellidoArray = nombresOapellido.split(' ');
    if (nombresOapellidoArray.length > 2) {
        nombresOapellido = nombresOapellidoArray.slice(0, 2).join(' ');
        inputElement.value = nombresOapellido;
    }

    document.getElementById(identificadorHtmlError).innerHTML = msj;
}

function validarIdentificacion(inputElement) {
    let msj = '';
    let identificacion = inputElement.value.trim();

    identificacion = identificacion.replace(/\D/g, ''); // Elimina todo lo que no sea un dígito
    inputElement.value = identificacion;

    const maxLength = 10;

    msj = validarLongitud(identificacion.length, 10, maxLength);

    inputElement.value = identificacion;

    document.getElementById('identificacion-error').innerHTML = msj;
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