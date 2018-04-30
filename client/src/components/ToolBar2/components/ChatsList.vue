<template lang="pug">
div
  v-list(two-line)
      template(v-for="chat in chats", :key="")
        v-list-tile(avatar, @click="getChat(chat.from)")
          v-list-tile-avatar
            img
          v-list-tile-content
            v-list-tile-title(v-html="chat.from")
            v-list-tile-sub-title(v-html="chat.conversation")
</template>

<script>
import store from '@/store'

export default {
  computed: {
    chats: () => store.state.chats,
  },
  methods: {
    getChat: (user) => {
      console.log('getChat')

      console.log(user)
      store.dispatch('CHANGE_CHAT', user)
      store.dispatch('CHANGEWINDOW', 'chat')
    }
  },
  // mounted: function () {
  //   console.log(this.chats)
  // }
}
</script>

<style lang="css">
</style>
