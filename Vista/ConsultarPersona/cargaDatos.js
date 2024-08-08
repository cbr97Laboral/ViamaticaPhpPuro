function editarFila(row) {
    llenarModalEditar(row);
    openModal();
}

function llenarModalEditar(datos) {
    ModificarModal(datos);
}

function openModal() {
    document.getElementById('modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}


function ModificarModal(datos) {
    const selectIdUsuarioModal = document.getElementById('idUsuario-modal');
    const selectIdPersonaModal = document.getElementById('idPersona-modal');

    const selectRolModal = document.getElementById('roles-modal');

    const selectUserNameModal = document.getElementById('username-modal');
    const selectNombreModal = document.getElementById('nombres-modal');
    const selectApellidosModal = document.getElementById('apellidos-modal');
    const selectIdentificacionModal = document.getElementById('identificacion-modal');

    const selectSesionModal = document.getElementById('sesion-modal');
    const selectBloqueadoModal = document.getElementById('bloqueado-modal');

    selectIdUsuarioModal.value = datos.idUsuario;
    selectIdPersonaModal.value = datos.idPersona;

    selectRolModal.value = datos.idRol;
    selectUserNameModal.value = datos.UserName;
    selectNombreModal.value = datos.Nombres;
    selectApellidosModal.value = datos.Apellidos;
    selectIdentificacionModal.value = datos.Identificacion;
    selectSesionModal.value = datos.SesionActiva !== undefined ? String(datos.SesionActiva) : '';
    selectBloqueadoModal.value = datos.Bloqueado !== undefined ? String(datos.Bloqueado) : '';
}

function limpiarModal() {
    const datosDefault = {
        idUsuario: "",
        idPersona: "",
        Apellidos: "",
        Bloqueado: "",
        Identificacion: "",
        NombreRol: "",
        Nombres: "",
        SesionActiva: "",
        UserName: "",
        idRol: ""
    };
    ModificarModal(datosDefault);
    closeModal();
}


document.addEventListener('DOMContentLoaded', function () {
    const url = '../../Modelo/Roles/cargarRoles.php';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const selectRolForm = document.getElementById('roles');
            llenarSelect(selectRolForm, data.data, 'idRol', 'NombreRol');
            //MODAL
            const selectRolModal = document.getElementById('roles-modal');
            llenarSelect(selectRolModal, data.data, 'idRol', 'NombreRol');
        })
        .catch(error => console.error('Error al obtener los roles:', error));

    function InitModal() {
        const datosEstados = [
            { idEstado: 1, Nombre: 'Activo' },
            { idEstado: 0, Nombre: 'Inactivo' },
        ];

        const selectSesionModal = document.getElementById('sesion-modal');
        llenarSelect(selectSesionModal, datosEstados, 'idEstado', 'Nombre');
        const selectBloqueadoModal = document.getElementById('bloqueado-modal');
        llenarSelect(selectBloqueadoModal, datosEstados, 'idEstado', 'Nombre');
    }

    function DefaultSelect(selectElement) {
        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione una opción';

        selectElement.innerHTML = '';
        selectElement.appendChild(defaultOption);
    }

    function llenarSelect(selectElement, data, idKey, nameKey) {

        DefaultSelect(selectElement);

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[idKey];
            option.textContent = item[nameKey];
            selectElement.appendChild(option);
        });
    }

    InitModal();
});

$(document).ready(function () {
    $('.consultar-persona-form').on('submit', function (event) {
        event.preventDefault();
        cargarTabla();
    });
});

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
