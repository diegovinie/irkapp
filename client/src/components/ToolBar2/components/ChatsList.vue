<template lang="pug">
div
  v-list(two-line)
      template(v-for="chat in chats", :key="")
        v-list-tile(avatar, @click="getChat(chat.user)")
          v-list-tile-avatar
            img
          v-list-tile-content
            v-list-tile-title(v-html="chat.user")
            v-list-tile-sub-title eee
</template>

<script>
import store from '@/store'

export default {
  computed: {
    chats: () => store.state.chats,
  },
  methods: {
    getChat: (user) => {
      store.commit('SETCHAT', user)
      store.dispatch('CHANGEWINDOW', 'chat')
    }
  }
}
</script>

<style lang="css">
</style>
