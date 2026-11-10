# Clases y funciones - BoticaSaludEsencia

## Módulo Usuarios

*Responsable:* Crispin Ramos Gustavo Anthony
*Rama:* Crispin-Gustavo

### Clase Usuario

*Archivo:* app/modelos/Usuario.php

Esta clase permite consultar la información de los
usuarios registrados en la base de datos.

#### Función listar()

- Obtiene todos los usuarios registrados.
- Consulta la tabla usuarios.
- Retorna un arreglo con los resultados.
- Muestra los campos id_usuario, nombre, usuario y rol.

#### Función buscarPorId(int $id)

- Busca un usuario mediante su identificador.
- Utiliza una consulta preparada con PDO.
- Retorna los datos del usuario encontrado.
- Si no existe, retorna false.

### Clase Conexion

*Archivo:* app/config/Conexion.php

#### Función conectar()

Establece la conexión con MySQL mediante PDO.
Utiliza los parámetros definidos en config.php
y devuelve el objeto de conexión.

### Controlador UsuarioController

*Archivo:* app/controladores/UsuarioController.php

El controlador organiza las solicitudes del módulo
Usuarios y comunica el modelo con las vistas.

### Vista de usuarios

*Archivo:* app/vistas/usuarios/index.php

Contiene la estructura inicial del formulario y
la tabla para mostrar información de los usuarios.

## Estado del desarrollo

- Modelo Usuario implementado.
- Dos funciones de consulta desarrolladas.
- Controlador y vista inicial incorporados.
- Conexión con MySQL verificada