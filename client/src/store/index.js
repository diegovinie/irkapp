import Vue from 'vue'
import Vuex from 'vuex'

import ws from '../ws'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    ws: ws,
    users: [],
    chat: {
      id: null,
      name: 'Chat'
    }
  },
  actions: {

  },
  mutations: {
    SETUSERS (state, data) {
      console.log('commiting SETUSERS')
      console.log(data)
      state.users = data
    },

    SETCHAT (state, data) {
      state.chat.name = data.email
      state.chat.id = data.id
    }
  }
})
