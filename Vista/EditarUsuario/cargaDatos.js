document.addEventListener('DOMContentLoaded', function () {
    if (typeof usuario !== 'undefined') {
        document.getElementById('username').value = usuario.UserName || '';
        document.getElementById('nombres').value = usuario.Nombres || '';
        document.getElementById('apellidos').value = usuario.Apellidos || '';
        document.getElementById('identificacion').value = usuario.Identificacion || '';
        document.getElementById('idUsuario').value = usuario.idUsuario || '';
    } else {
        console.error('El objeto usuario no está definido.');
    }
});
