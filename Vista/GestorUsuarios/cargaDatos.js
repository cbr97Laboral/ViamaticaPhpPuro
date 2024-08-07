document.addEventListener('DOMContentLoaded', function () {
    const url = '../../Modelo/Roles/cargarRoles.php';
    fetch(url)
        .then(response => response.json())
        .then(data => {

            const selectElement = document.getElementById('roles');
        
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Seleccione un rol';
            selectElement.appendChild(defaultOption);

            data.data.forEach(role => {
                const option = document.createElement('option');
                option.value = role.idRol;
                option.textContent = role.NombreRol;
                selectElement.appendChild(option);
            });
        })
        .catch(error => console.error('Error al obtener los roles:', error));
});
