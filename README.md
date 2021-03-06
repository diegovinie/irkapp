# Irkapp
Vue.js | Vuex | Vuetifyjs | Pug | WebSocket | Webpack | PHP
----------

Irkapp es un conjunto de cliente y servidor para mensajería instantánea. La finalidad es que el cliente se convierta en un *add-on* para los sitios web que permita tener una sencilla comunicación entre las personas que se encuentran simultáneamente, mientras el servidor corre desde una ubicación diferente a la del sitio en cuestión.

La conexión se logra a través de WebSocket, donde el servidor usa una implementación para **PHP** escrita y mantenida por [ghedipunk](https://github.com/ghedipunk/PHP-Websockets/).

El cliente está desarrollado en [Vue.js](https://vuejs.org/) y además utiliza [Vuex](https://github.com/vuejs/vuex) para manejar el estado y [Vuetify](https://vuetifyjs.com/) para la plantilla. El *html* está escrito mayormente usando [pug](https://www.npmjs.com/package/pug) y el javascript busca ajustarse a **EmacSript2015**. Utiliza **Webpack** para el desarrollo, con la finalidad de hacer un único archivo js que pueda ser incluido en *html* del sitio interesado con una etiqueta `<script>`.

El usuario es identificado usando el protocolo *OAuth2* de **Google** a través del cliente y los datos (nombre, correo, id) son enviados al servidor.

en el archivo `server/settings.php` se encuentran las constantes referentes a la conexión con la base de datos y el *entry point* del servidor

## Instrucciones:

Se recomienda tener un terminal de *shell* para cliente y otro para servidor.

### Servidor:

Clonar el repositorio
```bash
$ git clone https://github.com/diegovinie/irkapp.git
```

Cambiar en el archivo `server/settings.php` los parámetros referentes a la base de datos.

Desde `server/` ejecutar:
```bash
$ php console database init
```
Para iniciar la base de datos

Para iniciar el servidor:
```bash
$ php server
```
El punto de acceso por defecto es
http://127.0.0.1:9000


### Cliente:

Desde el directorio `client/`

Instalar dependencias:
```bash
$ npm install
```
también descargará una librería desde **Google** (ver `bin/get_gapi.sh`)

Para ejecutar el cliente en modo desarrollo:
```bash
$ npm run dev
```
