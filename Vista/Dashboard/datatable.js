$(document).ready(function() {
    $.ajax({
        url: '../../Modelo/Usuario/obtenerUsuarioDashboard.php', // Cambia esto a la ruta correcta de tu archivo PHP
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);

            // Calcula los totales
            let totalSesionActiva = 0;
            let totalBloqueado = 0;

            response.datos.forEach(usuario => {
                if (usuario.SesionActiva) totalSesionActiva++;
                if (usuario.Bloqueado) totalBloqueado++;
            });

            // Inicializa DataTables con el pie de tabla
            $('#datosUsuarios').DataTable({
                data: response.datos,
                columns: response.columnas,
                paging: true,
                searching: true,
                ordering: true,
                footerCallback: function(row, data, start, end, display) {
                    // Usado para sumar los totales
                    $(this.api().column(3).footer()).html(totalSesionActiva);
                    $(this.api().column(4).footer()).html(totalBloqueado);
                },
                initComplete: function(settings, json) {
                    // Agrega el pie de tabla
                    $(settings.nTable).find('tfoot').html(
                        '<tr>' +
                            '<th colspan="2">Total</th>' +
                            '<th></th>' +
                            '<th style="text-align: right;" id="total-sesion-activa"></th>' +
                            '<th style="text-align: right;" id="total-bloqueado"></th>' +
                        '</tr>'
                    );

                    $('#total-sesion-activa').text(totalSesionActiva);
                    $('#total-bloqueado').text(totalBloqueado);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los datos:', error);
        }
    });
});
