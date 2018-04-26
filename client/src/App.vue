<template lang="pug">
v-app(dark light)
  div(style={
          backgroundColor: '#cee',
          width: '280px',
          maxHeight: '488px',
          position: 'fixed',
          right: '0',
          bottom: '0',
          margin: 'auto',
          })
      v-expansion-panel
          v-expansion-panel-content(v-if="app.active")
              div(slot="header") {{ app.name }}
              div(style={height: "440px"})
                tool-bar(v-show="window == 'toolbar'")
                chat(v-show="window == 'chat'")
                  v-btn(@click="$data.window = 'toolbar'") tocame
          v-expansion-panel-content(v-else="!app.active")
            div(slot="header" @click="login()") Iniciar
</template>

<script>
/* eslint-disable no-new */
import ws from './ws'
import store from './store'
import auth from './api/auth'
import {logger} from '@/helpers'

export default {
  name: 'app',

  data: function () {
    return {
      ala: 0,
      tabs: ['tab-chat1', 'tab-usuarios', 'tab-opciones'],
      active: null,
      a: null,
      app: store.state.app
    }
  },

  computed: {
    ws: () => ws,
    chat: () => store.state.chat,
    window: () => store.state.mainWindow,
    snack: () => store.state.snack
  },

  methods: {
    changeWindow: function (win) {

      this.active = win
    },
    changeala: function () {
      this.ala = 1
    },
    login: async function () {
      await auth.asyn.getGapiReady()
      await auth.asyn.setClient()
      const credentials = await auth.asyn.getIdentity()

      console.log('iden es:')
      console.log(credentials)
      if (typeof credentials === 'object') {
        store.dispatch('ACTIVATE', credentials)
      } else {
        console.log('Error')
      }
    }
  },

  mounted () {
    window.onload = function () {

      // setTimeout(function () {
      //   console.log('creando ws')
      //   const socket = ws()
      //   console.log(socket)
      // }, 7000)
    }
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
