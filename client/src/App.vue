<template lang="pug">
v-app(dark light)
  Alerts
  div(style={
      backgroundColor: '#cee',
      width: '280px',
      maxHeight: '488px',
      position: 'fixed',
      right: '0',
      bottom: '0',
      margin: 'auto',
      })
    v-expansion-panel(v-if="app.active")
        v-expansion-panel-content
            div(slot="header") {{ app.name || 'dummie' }}
            div(style={height: "440px"})
              tool-bar(v-show="window == 'toolbar'")
              chat(v-show="window == 'chat'")
                v-btn(@click="$data.window = 'toolbar'") tocame
    div(v-else="!app.active" style={backgroundColor: '#000'} text-align="center")
      v-btn(@click="login()") {{ start }}
    //- div(v-else="!app.active")
    //-   div(slot="header" ) Iniciar
</template>

<script>
/* eslint-disable no-new */
import ws from '@/ws'
import store from '@/store'
import auth from '@/api/auth'
import Alerts from '@/components/Alerts'

// Funciones de ayuda y datos de prueba
import {logger} from '@/helpers'
import {tempAlert} from '@/../test/datosprueba'
import login from '@/login'

export default {
  name: 'app',

  components: {
    Alerts
  },

  data: function () {
    return {
      start: 'Iniciar',
      app: store.state.app,
    }
  },

  computed: {
    window: () => store.state.mainWindow,
  },

  methods: {
    // Método para autenticar en Google e iniciar el WebSocket
    login,
    // Método alternativo para iniciar el WebSocket
    socket: function () {
      return ws()
    }
  },

  mounted () {
    // Alert mensaje entrante de prueba
    // tempAlert(store)
  }
}
</script>

<style>
/*@import 'vuetify/src/stylus/main.styl'*/

#app {
  /*font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;*/
}
</style>
