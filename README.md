# ViamaticaPhpPuro

## Descripción
Este proyecto es una implementación de una aplicación web utilizando PHP puro. El sistema permite gestionar usuarios, realizar intentos de sesión y redirigir a la página de inicio en caso de éxito. Además, se encarga de la navegación según el rol del usuario y registra las acciones en la base de datos.

## Link del Repositorio
[Repositorio en GitHub](https://github.com/cbr97Laboral/ViamaticaPhpPuro)

## Requisitos para Ejecutar el Proyecto
1. Tener **XAMPP** instalado.
2. Tener instalado **PHP versión 8**.
3. Ubicar el repositorio en la ruta `C:\xampp\htdocs\ViamaticaPhpPuro`.
4. Ejecutar el script SQL para crear la base de datos y llamarla **baseprueba**, incluenyendo script para configuración inicial de la base de datos.

## Pasos para Ejecutar el Proyecto
1. Clonar el repositorio en la ruta `C:\xampp\htdocs\ViamaticaPhpPuro`.
2. Abrir el navegador y acceder a la URL: [http://localhost/ViamaticaPhpPuro/Vista/Login/login.php](http://localhost/ViamaticaPhpPuro/Vista/Login/login.php).
3. Ejecutar el script SQL proporcionado para crear la base de datos.

## Funcionalidades del Proyecto

### Login
- Permite realizar intentos de sesión.
- Redirecciona al usuario a la página de inicio en caso de éxito.

### Intentos de Inicio de Sesión Fallidos
- Registra los intentos fallidos de inicio de sesión.

### Navegación
- Carga de opciones según las opciones asignadas al rol del usuario.

### Home
- Página de bienvenida con datos básicos del usuario y datos de la última sesión cerrada.

### Registro
- **Validaciones**: 
  - Por inputs y por submit.
- **Encriptación de Contraseña**:
  - Las contraseñas se encriptan utilizando `bcrypt`.
- **Generación de Correo**:
  - Se genera un correo único para cada usuario.
- **Creación de Registros**:
  - Usuarios
  - Persona
  - Controllogin
  - Usuario_rol

### Consulta de persona y edición
- **Consulta de usuario mediante campos principales**: 
  - Llenado de tabla con usuario segun los filtros.
- **Edición de usuario**:
  - validación por inputs y por submit, para el formulario de edición.
  - Las contraseñas se encriptan utilizando `bcrypt`.
