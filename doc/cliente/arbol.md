/bin  # scripts de bash
/lib  # otras librerías de terceros
/src
  /api
    index.js  # vacío
    auth.js   # Autorización Google
  /assets
    /css
      materialicons.css   # Para algunos iconos
    logo.png
  /components
    /Alerts   # notificaciones sobre la aplicación minimizada
      index.js
      Message.vue   # Componente mensaje
    /ToolBar    # Barra superior
      /components
	      ChatList.vue   # Lista de las conversaciones
	      Contacts.vue   # Lista de contactos
	      Options.vue    # Opciones de la aplicación
      index.vue
    Chat.vue    # Ventana con el chat activo
  /store    # El estado Vuex
    index.js
  /ws   # WebSocket
    index.js
  /static
  /test
    /e2e
    /unit
    datosprueba.js    # Datos y funciones de prueba
main.js
App.vue
helpers.js    # Funciones de ayuda
