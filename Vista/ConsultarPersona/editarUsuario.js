function editar() {

    const idUsuario = document.getElementById('idUsuario-modal').value;
    const idPersona = document.getElementById('idPersona-modal').value;

    const idRol = document.getElementById('roles-modal').value;

    const username = document.getElementById('username-modal').value;
    const nombres = document.getElementById('nombres-modal').value;
    const apellidos = document.getElementById('apellidos-modal').value;
    const identificacion = document.getElementById('identificacion-modal').value;

    const sesionActiva = document.getElementById('sesion-modal').value;
    const bloqueado = document.getElementById('bloqueado-modal').value;

    const contrasena = document.getElementById('contrasena-modal').value;
    
    const formData = new FormData();
    formData.append('idUsuario', idUsuario);
    formData.append('idPersona', idPersona);
    formData.append('idRol', idRol);
    formData.append('username', username);
    formData.append('nombres', nombres);
    formData.append('apellidos', apellidos);
    formData.append('identificacion', identificacion);
    formData.append('sesionActiva', sesionActiva);
    formData.append('bloqueado', bloqueado);
    formData.append('contrasena', contrasena);

    fetch('../../Modelo/Usuario/editarUsuarioPersona.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            alert("Ocurrio un error en la actualización");
            closeModal();
        }
        else{
            closeModal();
            alert("Edición exitosa");
            cargarTabla();
        }
    })
    .catch(error => {
        console.error('Error al realizar la solicitud:', error);
    });
    
}

function cargarTabla(){
    var formData = {
        idRol: $('#roles').val(),
        username: $('#username').val(),
        nombres: $('#nombres').val(),
        apellidos: $('#apellidos').val(),
        identificacion: $('#identificacion').val(),
        correo: $('#correo').val(),
    };

    $.ajax({
        url: '../../Modelo/Usuario/buscarPersona.php',
        type: 'GET',
        dataType: 'json',
        data: formData,
        success: function (response) {
            if ($.fn.DataTable.isDataTable('#datosUsuarios')) {
                $('#datosUsuarios').DataTable().destroy();
            }
            $('#datosUsuarios').empty();

            $('#datosUsuarios').DataTable({
                data: response.datos,
                columns: response.columnas,
                columns: response.columnas.concat([
                    {
                        title: 'Editar',
                        data: null,
                        className: 'text-center',
                        render: function (data, type, row) {

                            if (row.NombreRol !== "Administrador") {
                                var rowJson = JSON.stringify(row);
                                return '<button class="btn btn-primary edit-button" onclick=\'editarFila(' + rowJson + ')\'>Editar</button>';
                            } else {
                                return '';
                            }
                        }
                    }
                ]),
                paging: true,
                searching: true,
                ordering: true,
            });
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los datos:', error);
        }
    });
}