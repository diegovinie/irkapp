<template lang="pug">
  div
    v-alert(
        :type="type"
        v-model="active"
        dismissible
        transition="slide-y-reverse-transition")
      div {{ from }} id:{{id}}
      v-spacer
      div {{ content }}
</template>

<script>
export default {
  data: () => ({
    countdown: 5000,
    active: false,
  }),

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
