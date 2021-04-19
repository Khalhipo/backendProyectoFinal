# Backend en PHP

Backend en PHP para la asignatura de DAWEC en el IES Juan Bosco. Especialmente diseñado para su uso con el Framework de JavaScript [Angular](http://angular.io)

Modificado y adaptado para el proyecto final.

## Instalación

#### Pasos a seguir para la puesta en funcionamiento del Backend.

Dirigirse a la carpeta de instalación por defecto de Xampp con la consola de comandos:
```bash
cd c:\xampp\htdocs
```
Clonar el repositorio de este proyecto con el comando:

```bash
git clone https://github.com/Khalhipo/repositorioBueno/tree/master/backendBueno
```
Ejecutar el programa Xampp Control Panel e iniciar los servicios Apache y MySQL con el botón Start. Una vez iniciado, acceder a [http://localhost/phpmyadmin](http://localhost/phpmyadmin). Crear una nueva base de datos con el nombre `back`. Dentro de esa base de datos importar el archivo **backphp.sql** que creará la estructura de las tablas.

## Rutas, métodos y respuestas.

Rutas actualmente implementadas organizadas por método y funcionalidad.

#### Rutas relacionadas con las notas:

| Método | Ruta | Acción | Requiere JWT |
| :---: | --- | --- | :---: |
| **GET** |_localhost/backendphp/notas/_| Listar todas las notas | Opcional |
| **POST** |_localhost/backendphp/notas/_| Insertar una nota | Opcional |
| **PUT** |_localhost/backendphp/notas/_| Actualizar una nota | Obligatorio |
| **DELETE** |_localhost/backendphp/notas/id_| Eliminar una nota | Obligatorio |

#### Rutas relacionadas con el usuario:

| Método | Ruta | Acción | Requiere JWT |
| :---: | --- | --- | :---: |
| **GET** |_localhost/backendBueno/user/_| Leer la información del usuario JWT | Obligatorio |
| **POST** |_localhost/backendBueno/user/_| Registrar un usuario nuevo | Innecesario |
| **PUT** |_localhost/backendBueno/user/_| Actualizar la información del usuario JWT | Obligatorio |
| **DELETE** |_localhost/backendBueno/user/_|Eliminar el usuario JWT | Obligatorio |
| **DELETE** |_localhost/backendBueno/user/friend_|Eliminar el amigo del usuario JWT | Obligatorio |
| **POST** |_localhost/backendBueno/user/login_| Hacer Login con la información recibida | Innecesario |
| **POST** |_localhost/backendBueno/user/image_| Subir una imagen de perfil del usuario | Obligatorio |
| **POST** |_localhost/backendBueno/user/friend_| Añadir un amigo al usuario | Obligatorio |
| **GET** |_localhost/backendBueno/user/list_| Mostrar información de otros usuarios | Obligatorio |
| **GET** |_localhost/backendBueno/user/amigos_| Mostrar la lista de amigos | Obligatorio 

#### Rutas relacionadas con los entrenamientos:

| Método | Ruta | Acción | Requiere JWT |
| :---: | --- | --- | :---: |
| **GET** |_localhost/backendBueno/entrenamientos/listEj_| Obtener todos los ejercicios | Obligatorio |
| **GET** |_localhost/backendBueno/entrenamientos/etto_| Recuperar el entrenamiento | Obligatorio |
| **POST** |_localhost/backendBueno/entrenamientos/_| Crear un nuevo entrenamiento | Obligatorio |
| **POST** |_localhost/backendBueno/entrenamientos/ejer_| Crear un nuevo ejercicio | Obligatorio |
| **PUT** |_localhost/backendBueno/entrenamientos/_| Editar el entrenamiento | Obligatorio |
| **DELETE** |_localhost/backendBueno/entrenamientos/_| Eliminar el entrenamiento | Obligatorio |

#### Rutas relacionadas con los stats:

| Método | Ruta | Acción | Requiere JWT |
| :---: | --- | --- | :---: |
| **GET** |_localhost/backendBueno/stats/pesoCorporal_| Obtener los datos de peso corporal del usuario | Obligatorio |
| **GET** |_localhost/backendBueno/stats/volumen_| Obtener los datos del volumen de los ettos del usuario | Obligatorio |
| **GET** |_localhost/backendBueno/stats/intensidad_| Obtener los datos de la intensidad de los ettos del usuario | Obligatorio |
| **GET** |_localhost/backendBueno/stats/ettocategoria_| Obtener el nº de ejercicios por categoria de los ettos del usuario | Obligatorio |
| **GET** |_localhost/backendBueno/stats/categorias_| Obtener las categorías de la aplicación | Obligatorio |
| **POST** |_localhost/backendBueno/entrenamientos/_| Crear un nuevo entrenamiento | Obligatorio |
| **POST** |_localhost/backendBueno/entrenamientos/ejer_| Crear un nuevo ejercicio | Obligatorio |
| **PUT** |_localhost/backendBueno/entrenamientos/_| Editar el entrenamiento | Obligatorio |
| **DELETE** |_localhost/backendBueno/entrenamientos/_| Eliminar el entrenamiento | Obligatorio |

#### Rutas relacionadas con los mensajes:

| Método | Ruta | Acción | Requiere JWT |
| :---: | --- | --- | :---: |
| **GET** |_localhost/backendBueno/mensajes/_| Obtener los mensajes recibidos | Obligatorio |
| **GET** |_localhost/backendBueno/mensajes/sent_| Obtener los mensajes enviados | Obligatorio |
| **POST** |_localhost/backendBueno/mensajes/_| Enviar un mensaje a un usuario | Obligatorio |
| **PUT** |_localhost/backendBueno/mensajes/_| Actualizar un mensaje enviado | Obligatorio |
| **DELETE** |_localhost/backendBueno/mensajes/id_| Eliminar un mensaje recibido | Obligatorio |
| **DELETE** |_localhost/backendBueno/mensajes/chat_| Eliminar el chat | Obligatorio |


#### Códigos de respuesta del servidor:

| Código | Significado |
| :--: | -- |
| _200_ | Petición aceptada por el servidor |
| _201_ | La operación de inserción o actualización ha sido correcta |
| _400_ | Hay un error en la petición del cliente |
| _401_ | Fallo en la petición ya que requiere autorización |
| _404_ | No se ha encontrado el recurso de la petición |
| _409_ | Hay un conflicto con la petición del cliente |