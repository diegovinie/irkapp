<template lang="html">


  <v-snackbar
    :timeout="timeout"
    color=""
    multi-line
    vertical
    absulute
    right
    v-model="snackbar"
    style="bottom:170px; right: 0px;max-width:330px;"
  >
    <div>hola fulano</div>
    <v-btn>No soy fulano</v-btn>
    <v-btn dark flat @click.native="snackbar = false">Close</v-btn>
  </v-snackbar>

</template>

<script>
export default {
  data () {
    return {
      snackbar: true,
      color: '',
      mode: '',
      timeout: 60000,
      text: 'Hello, I\'m a snackbar'
    }
  }
}
</script>

<style lang="css">
</style>
