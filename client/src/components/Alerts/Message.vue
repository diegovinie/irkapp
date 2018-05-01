<template lang="pug">
  div
    v-alert(
        :type="alertType"
        v-model="active"
        dismissible
        transition="slide-y-reverse-transition")
      v-spacer
      div
        b {{ from }}:
        span  {{ content }}
</template>

<script>
export default {
  data: () => ({
    countdown: 10000,
    active: false,
  }),

  computed: {
    // Según el tipo de mensaje
    alertType: function () {
      if (this.type === 'privmsg') {
        return 'success'
      }
      if (this.type === 'srvmsg') {
        return 'info'
      }
      if (this.type === 'error') {
        return 'error'
      }
      return ''
    }
  },
  // Lo importado del padre
  props: ['from', 'content', 'type', 'id'],

  mounted: function () {
    // Para que se vea la transición
    this.active = true
    // Establece el tiempo de vida
    setTimeout(() => {
      this.active = false
    }, this.countdown)
  },
  watch: {
    active: function (isActive) {
      // Si cambia a falso, saltando cuando arranca
      if (!isActive) {
        // dar tiempo a la transición
        setTimeout( () => {
          this.$destroy()
        }, 500)
      }
    }
  },
  destroyed: function () {
    // Solo para indicar que fue destruido
    console.log(`destruido message ${this.id}`)
  }
}
</script>

<style lang="css">
</style>
