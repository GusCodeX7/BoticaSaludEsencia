# Bitácora del proyecto BoticaSaludEsencia

## Hito 1 - Desarrollo colaborativo

### Integrante: Crispin-Gustavo
*Rama:* Crispin-Gustavo

### Actividades realizadas

- Se creó la estructura inicial del proyecto utilizando el patrón MVC.
- Se incorporaron los archivos compartidos de conexión a MySQL.
- Se desarrolló el modelo Usuario.php.
- Se implementaron las funciones listar() y buscarPorId().
- Se agregó UsuarioController.php.
- Se creó la vista usuarios/index.php con formulario y tabla.
- Se integraron los cambios de la rama main.
- Se verificó la conexión con la base de datos botica_saludesencia.

### Commits realizados

1. chore: crea estructura base MVC de BoticaSaludEsencia
2. feat: agrega modelo Usuario con funciones de acceso a BD
3. feat: agrega controlador de usuarios
4. feat: agrega formulario y tabla de usuarios
5. feat: agrega conexion MySQL y archivos compartidos

### Estado del módulo

El módulo Usuarios cuenta con su modelo,
controlador y vista inicial.

La conexión a MySQL fue verificada correctamente.
---

## Hito 3 - Correcciones del módulo Ventas

### Integrante: Alexandra-Robladillo
**Rama:** Alexandra-Robladillo

### Actividades realizadas

- Se revisó el funcionamiento de los archivos del módulo Ventas.
- Se agregó una validación del precio en el modelo Venta.php.
- Se ajustó el formato del constructor en DetalleVenta.php.
- Se organizó el método guardar() en VentaController.php.
- Se mejoró la presentación del código en ventas/index.php.
- Se verificó la sintaxis de los archivos PHP.
- Se prepararon los commits individuales con el prefijo fix:.
- Se realizaron pruebas de registro y visualización de ventas en el entorno local.

### Commits realizados

1. fix: valida el precio en el modelo Venta
2. fix: ordena el constructor de DetalleVenta
3. fix: ajusta el formato de VentaController
4. fix: ajusta el formato de la vista de ventas

### Estado actual

El módulo Ventas permite registrar y visualizar ventas correctamente en el entorno local. Se realizaron ajustes en los archivos del modelo, controlador y vista, manteniendo la estructura MVC y el funcionamiento existente.

Queda pendiente comprobar mediante una prueba específica el descuento del stock por lotes.