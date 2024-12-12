# Sistema de Gestión de Pastelería

##Video

<iframe width="560" height="315" src="https://www.youtu.be/-vMb2US0Yog/<VIDEO_ID>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

https://youtu.be/-vMb2US0Yog


<video width="640" height="360" controls>
  <source src="ProyectoPastereria.mp4" type="video/mp4">
  Tu navegador no soporta la etiqueta de video.
</video>

##Descripción General
Este es un sistema de gestión basado en la web para una pastelería (Pastelería). Permite la gestión de clientes y productos, el procesamiento de pedidos e incluye interfaces para usuarios y administradores. El sistema está desarrollado utilizando PHP, MySQL y JavaScript, con un enfoque en la programación orientada a objetos y la arquitectura MVC.

Estructura del Proyecto

## Características

- Autenticación de usuario (Iniciar sesión/Cerrar sesión)
- Gestión de clientes (Crear, Actualizar, Eliminar)
- Gestión de productos (Crear, Ver)
- Funcionalidad del carrito de compras
- Procesamiento de pedidos
- Sistema de calificación de productos
- Panel de administración para la gestión general

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)

## Instalación

1. Clona el repositorio a tu máquina local o servidor:
   git clone https://github.com/AntonioPazo/examenPHP.git

2. Configura tu servidor web para que apunte al directorio `public` como la raíz del documento.

3. Importa el esquema de la base de datos:

4. Configura la conexión a la base de datos:
Abre `src/ConexionDB.php` y actualiza las credenciales de la base de datos:

private $host = 'localhost'; 
private $usuario = 'root'; 
private $password = '';
private $baseDatos = 'pasteleriadb';


## Uso

### Para los Clientes

1. Navega a la página de inicio (`index.php`).
2. Regístrate para una nueva cuenta o inicia sesión con tus credenciales existentes.
3. Navega por el catálogo de productos, agrega artículos al carrito y procede a la compra.
4. Consulta el historial de pedidos y califica los productos adquiridos.

### Para los Administradores

1. Inicia sesión con las credenciales de administrador (usuario: admin, contraseña: admin).
2. Accede al panel de administración a través de `mainAdmin.php`.
3. Gestiona clientes, productos y consulta la información del sistema.

## Consideraciones de Seguridad

- Las contraseñas se almacenan de forma segura mediante un hash.
- Se implementa validación y sanitización de entradas para prevenir inyecciones SQL y ataques XSS.
- Las funcionalidades de administrador están protegidas con control de acceso basado en roles.
