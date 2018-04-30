<template lang="pug">
  div()
    v-tabs(fixed, v-model="active",  centered )
        v-toolbar(app color="cyan" style={
                padding: '0',
                fontSize: '12px',
                height: '56px'})
            v-tabs-bar(color="transparent" dark )
                v-tabs-slider(color="yellow" style={margin: '0px', maxWidth: '80px'})
                v-tabs-item(href="#tab-chat1" style={margin: '0px', maxWidth: '80px'}) Chats
                v-tabs-item(href="#tab-contacts" style={margin: '0px', maxWidth: '80px'}) Contactos
                v-tabs-item(href="#tab-opciones" style={margin: '0px', maxWidth: '80px'}) Opciones
        v-tabs-items
            v-tabs-content#tab-chat1(style={backgroundColor: 'pink'})
                ChatsList
            v-tabs-content#tab-contacts(style={backgroundColor: 'pink'})
                Contacts(@changeWindow="changeTab")
            v-tabs-content#tab-opciones
                Options
</template>

<script>
import ChatsList from './components/ChatsList'
import Contacts from './components/Contacts'
import Options from './components/Options'

export default {
  components: {
    ChatsList,
    Contacts,
    Options
  },
  data () {
    return {
      // La pestaña activa
      active: null,
    }
  },

  methods: {
    // Cambia las pestañas del ToolBar
    changeTab: function (tab) {
      this.active = tab
    }
  },
}
</script>

<style lang="css">
</style>
