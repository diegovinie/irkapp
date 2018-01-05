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
                v-tabs(fixed, centered style={height: "440px"})
                    v-toolbar(app color="cyan" style={
                            padding: '0',
                            fontSize: '12px',
                            height: '56px'})
                        //- v-text-field(solo, label="Search", append-icon="keyboard_voice", prepend-icon="search")
                        v-tabs-bar(color="transparent" dark )
                            v-tabs-slider(color="yellow" style={margin: '0px', maxWidth: '80px'})
                            v-tabs-item(href="#tab-chat1" style={margin: '0px', maxWidth: '80px'}) Ramon
                            v-tabs-item(href="#tab-usuarios" style={margin: '0px', maxWidth: '80px'}) Usuarios
                            v-tabs-item(href="#tab-historial" style={margin: '0px', maxWidth: '80px'}) Historial
                    v-tabs-items
                        v-tabs-content#tab-chat1(style={backgroundColor: 'pink'})
                            Chat
                        v-tabs-content#tab-usuarios(style={backgroundColor: 'pink'})
                            UsersList
                        v-tabs-content#tab-opciones
                            Options
</template>

<script>

export default {
  name: 'app',
  data () {
    return {

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
