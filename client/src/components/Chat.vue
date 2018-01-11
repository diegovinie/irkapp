<template lang="pug">
div
  v-toolbar(app color="cyan" style={
          padding: '0',
          fontSize: '12px',
          height: '56px'})
          span {{name}}
          v-btn(@click="toToolbar()") Regresar
  v-container(fluid, grid-list-lg style={backgroundColor: 'gray'})
      v-layout(row, wrap, style={
              overflow: 'auto',
              height: '280px',
              backgroundColor: '#5e5050'})
          v-flex(xs12)
              v-card
                  v-card-title aaa
                  v-card-actions ddd
          v-flex(xs12)
              v-card
                  v-card-title aaa
                  v-card-actions ddd
          v-flex(xs12)
              v-card.white--text
                  v-container(fluid, grid-list-lg)
                      v-layout(row)
                          v-flex(xs7)
                              div.title lalaa
                              div Foster the people
                          v-flex(xs5)
                              v-card-media(
                                  src="https://vuetifyjs.com/static/doc-images/cards/foster.jpg", height="80px", contain
                                  )

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
                          single-line
                          placeholder="text label"
                          hide-details
                          rows="1"

                          style={
                              maxHeight: '100px'
                              })
</template>

<script>
import store from '@/store'

export default {
  data () {
    return {

    }
  },
  computed: {
    name: () => store.state.chat.name,
    window: () => store.state.mainWindow
  },
  methods: {
    toToolbar: function () {
      store.dispatch('CHANGEWINDOW', 'toolbar')
    }
  }
}
</script>

<style lang="css">
</style>
