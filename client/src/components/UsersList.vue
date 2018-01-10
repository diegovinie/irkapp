<template lang="pug">
v-container(fluid, grid-list-lg style={backgroundColor: 'gray'})
    v-layout
        v-flex
            v-card.white--text
                v-card-title Disponibles
                v-list
                  v-list-tile(v-for="user, i in users", :key="i")
                    v-btn(@click="openChat(user)")
                      v-list-tile-title {{user.email}}
                    v-list-tile-sub-title
                      span(v-if="user.status == 1") conectado
                      span(v-else) ocupado
                    v-divider
                //- v-btn(
                //-       v-for="user, i in users"
                //-       :key="i") {{ user.email }}
                //-     //- div(v-if="user.status == 1") conectado
                //-     //- div(v-esle) ocupado
                //-     <div v-if="user.status == 1">conectado</div>
                //-     <div v-else>ocupado</div>
</template>

<script>
import store from '../store'
import vm from '../App'
// import vue from 'vue'

export default {
  computed: {
    users: () => store.state.users,
    ela: function(){
      return this.$parent.$parent
    }
  },
  methods: {
    openChat: function (user) {
      console.log(user)
      this.$emit('changeWindow', 'tab-chat1')
      store.commit('SETCHAT', user)


    },
    aaa: function (e) {
      console.log('en aaa: ', e)
    }
  },
  // props: ['tabs'],
  watch: {
    users: (value) => {
      console.log(value)
    }
  },
  mounted () {
    console.log('ela: ')
    console.log(this)

  }
}
</script>

<style lang="css">
</style>
