<template lang="pug">
  v-container(fluid, grid-list-lg)
      v-layout
          v-flex
              v-switch(
                    :label="`conexión: ${conSwitch.toString()}`"
                    v-model="conSwitch")
              v-switch(
                  :label="`ocupado: ${busy.toString()}`"
                  v-model="busy"
                )
              //- v-switch(v-model="b")
</template>

<script>
import store from '@/store'

export default {
  name: 'options',
  data: function () {
    return {
      conSwitch: false,
      socket: null,
      busy: false,
      // ele: this.ala
    }
  },

  methods: {
    connect () {
      console.log('con')
      const url = 'ws://localhost:9000/'
      try {
        this.$data.socket = new WebSocket(url)
      } catch (err) {
        console.log(err)
        this.$data.conSwitch = false
      }
    },
    disconnect () {
      console.log('dis')
      this.$data.socket.close()
    }
  },
  watch: {
    busy: function (val) {
      if(val === true){
        // console.log(this.ows)
        console.log(store.state.ws)
        store.state.ws.send(JSON.stringify({
          header: 'update',
          data: {status: 2}
        }))
      }
    },

    conSwitch: function (val) {
      val ? this.connect() : this.disconnect()
    },
    socket: function (val) {
      console.log(val)
      if (val.readyState === 0) console.log('conectando')
      if (val.readyState === 1) console.log('conectado')
      if (val.readyState === 2) console.log('desconectando')
      if (val.readyState === 3) console.log('desconectado')
    }
  }
}
</script>

<style lang="css">
</style>
