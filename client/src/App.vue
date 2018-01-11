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
      ws: ws,
      ala: 0,
      tabs: ['tab-chat1', 'tab-usuarios', 'tab-opciones'],
      active: null,
      a: null,
    }
  },

  computed: {
    chat: () => store.state.chat,
    window: () => store.state.mainWindow,
  },

  methods: {
    changeWindow: function (win) {

      this.active = win
    },
    changeala: function () {
      this.ala = 1
    }
  },

  created () {

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
