<template lang="pug">
v-container(fluid, grid-list-lg style={backgroundColor: 'gray', overflow: 'auto', maxHeight: '384px'})
    v-layout
        v-flex
            v-card.white--text
                v-card-title Disponibles
                v-list
                  v-list-tile(v-for="user, i in users", :key="i" @click="openChat(user)")
                    v-list-tile-content
                      v-list-tile-title {{user.name}}
                      v-list-tile-sub-title
                        span(v-if="user.status == 0") desconectado
                        span(v-else-if="user.status == 1") conectado
                        span(v-else-if="user.status == 2") ocupado
                        span(v-else="user.status == 3") desconocido
                    v-icon(:color="status(user.status)") star_border
</template>

<script>
import store from '@/store'

export default {
  data: () => {
    return {

    }
  },
  computed: {
    // users: () => store.state.users,
    chats: () => store.state.chats,
    users: () => store.state.users
  },

  methods: {
    openChat: function (user) {

      if(!this.chats.find( e => e.user == user)) {
        store.commit('ADDCHAT', user)
      }

      store.commit('SETCHAT', user)
      store.dispatch('CHANGEWINDOW', 'chat')
    },

    status: function (st) {
      return st == 1? 'green' : 'red'
    }
  },

  watch: {
    users: (value) => {
      console.log(value)
    }
  },

  mounted () {

  }
}
</script>

<style lang="css">
</style>
