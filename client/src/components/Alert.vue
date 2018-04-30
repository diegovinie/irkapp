<template lang="pug">
  div(:style="style")
    v-alert(:type="type" v-model="active" dismissible transition="slide-x-reverse-transition")
      div {{ msg.title }}
      v-spacer
      div {{ msg.content }}
</template>

<script>
export default {
  data: () => ({
    type: 'success',
    countdown: 3000,
    queue: 2,
    active: true,
    data: {
      title: 'From',
      content: 'jaja jaja jajaja jajaja.'
    }
  }),
  computed: {
    style: function () {
      return {
          width: "280px",
          right: "0",
          position: "fixed",
          bottom: (this.queue * 60) + 50 + 'px'
      }
    }
  },
  props: ['msg'],

  mounted: function () {
    // Programa la destrucción del alert
    // setTimeout(() => {
    //   this.active = false
    // }, this.countdown)
  },
  destroyed: function () {
    console.log('destroyed')
  }
}
</script>

<style lang="css">
</style>
