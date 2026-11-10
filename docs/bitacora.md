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


### Integrante: Alexandra-Robladillo
*Rama:* Alexandra-Robladillo

### Actividades realizadas

- Se incorporaron los archivos compartidos del proyecto utilizando la estructura MVC.
- Se desarrolló el modelo Venta.php para consultar y registrar ventas.
- Se creó el modelo DetalleVenta.php para registrar y consultar los medicamentos vendidos.
- Se implementó VentaController.php para comunicar el modelo con la vista.
- Se creó la vista ventas/index.php con el formulario de registro y la tabla de ventas.
- Se implementó el cálculo del total de las ventas.
- Se agregó el descuento automático del stock de medicamentos por lotes.
- Se implementaron transacciones MySQL para guardar las ventas y sus detalles.
- Se verificó la sintaxis de los modelos y del controlador utilizando PHP 8.3.30.
- Se subieron los cambios a la rama Alexandra-Robladillo en GitHub.

### Commits realizados

1. Agregar modelos de ventas y detalle de ventas
2. Agregar controlador de ventas
3. Agregar vista del modulo de ventas
4. Actualizar registro de ventas y control de stock

### Estado del módulo

El módulo Ventas cuenta con sus modelos, controlador y vista de registro.

Se implementó el registro de ventas, el cálculo del total y el descuento de stock mediante transacciones.
