<template lang="pug">
div
  //- Caja de identificación
  v-toolbar(app color="cyan" style={
          padding: '0',
          fontSize: '12px',
          height: '56px'})
          span {{chat.from}}
          v-btn(@click="toToolbar()") Regresar
  v-container(fluid, grid-list-lg style={backgroundColor: 'gray'})
      //- El despliegue de los mensajes
      v-layout(row, wrap, style={
              overflow: 'auto',
              height: '280px',
              backgroundColor: '#5e5050'})
          v-flex(xs12 v-for="msg in chat.conversation")
              v-card
                  //- v-card-title
                  v-card-actions
                    b {{ chat.from }}:
                    span {{ msg }}
          //- v-flex(xs12)
          //-     v-card.white--text
          //-         v-container(fluid, grid-list-lg)
          //-             v-layout(row)
          //-                 v-flex(xs7)
          //-                     div.title lalaa
          //-                     div Foster the people
          //-                 v-flex(xs5)
          //-                     v-card-media(
          //-                         src="https://vuetifyjs.com/static/doc-images/cards/foster.jpg", height="80px", contain
          //-                         )
      //- La caja para enviar mensaje
      v-layout(row, wrap, style={
          overflow: 'auto',
          height: '118px',
          backgroundColor: 'pink'
          })
          v-flex(xs12 style={
              position: 'fixed',
              bottom: '0px',
              backgroundColor: 'white',
              height: '80px'})
              v-card(style={backgroundColor: 'green'})
                  v-container(fluid, grid-list-lg)
                      v-text-field(
                          @keyup.enter="sendMessage"
                          single-line
                          placeholder="escribe y presiona 'enter'"
                          hide-details
                          rows="1"
                          v-model="response"
                          style={
                              maxHeight: '100px'
                              })
</template>

<script>
/**
 * Chat es el área donde se ven los mensajes de la conversation activa
 * y está el text-area para enviar mensajes. Está al mismo nivel de ToolBar
 */

import store from '@/store'

export default {
  data () {
    return {
      // captura el text-field para mandar el mensaje
      response: '',
      // Es el objeto WebSocket
      socket: store.state.socket
    }
  },
  computed: {
    // Contiene los datos del actual chat: from, mensajes...
    chat: () => store.state.chat
  },
  methods: {
    // Regresar al toolbar principal
    toToolbar: function () {
      store.dispatch('CHANGEWINDOW', 'toolbar')
    },
    // Manda el mensaje al servidor
    sendMessage: function ()  {
      console.log('sendMessage')
      this.socket.send(JSON.stringify({
        type: 'privmsg',
        from: store.state.app.name,
        to: this.chat.from,
        content: this.response
      }))
      this.response = ''
    }
  }
}
</script>

<style lang="css">
</style>
