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
    v-expansion-panel
        v-expansion-panel-content(v-if="app.active")
            div(slot="header") {{ app.name || 'dummie' }}
            div(style={height: "440px"})
              //- tool-bar(v-show="window == 'toolbar'")
              //- chat(v-show="window == 'chat'")
              //-   v-btn(@click="$data.window = 'toolbar'") tocame
        v-expansion-panel-content(v-else="!app.active")
          div(slot="header" @click="socket()") Iniciar
</template>

<script>
/* eslint-disable no-new */
import ws from '@/ws'
import store from '@/store'
import auth from '@/api/auth'
import {logger} from '@/helpers'
import Alerts from '@/components/Alerts'

export default {
  name: 'app',

  components: {
    Alerts
  },

  data: function () {
    return {
      tabs: ['tab-chat1', 'tab-usuarios', 'tab-opciones'],
      app: store.state.app,
      men: {
        title: 'Shit',
        content: 'ola ke ace'
      }
    }
  },

  computed: {
    chat: () => store.state.chat,
    window: () => store.state.mainWindow,
    snack: () => store.state.snack
  },

  methods: {

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
    },
    socket: function () {
      return ws()
    }
  },

  mounted () {
    setTimeout( () => {
      store.commit('ADD_ALERT', {
        from: 'Che',
        content: 'porque se fue',
        type: 'success'
      })
    }, 12000)
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
