<template lang="pug">
v-app(dark light)
  v-snackbar(
      :timeout="snack.timeout"
      color=""
      multi-line
      vertical
      absulute
      right
      v-model="snack.show"
      :slot="snack.content"
      style={bottom: '170px', right: '0px', maxWidth: '330px'}) ggg
    //- div#snack-content hola fulano
    //- div#snack-action
    //- v-btn No soy fulano
    v-btn(dark flat @click.native="snackbar = false") Cerrar
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
          v-expansion-panel-content
              div(slot="header") Chat
              div(style={height: "440px"})
                tool-bar(v-show="window == 'toolbar'")
                chat(v-show="window == 'chat'")
                  v-btn(@click="$data.window = 'toolbar'") tocame
</template>

<script>
import ws from './ws'
import store from './store'

export default {
  name: 'app',

  data: function () {
    return {
      ala: 0,
      tabs: ['tab-chat1', 'tab-usuarios', 'tab-opciones'],
      active: null,
      a: null,
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
    }
  },

  mounted () {

    // setTimeout( () => {
    //   this.snackbar = true
    // }, 2000)
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
