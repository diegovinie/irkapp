import Vue from 'vue'
import Vuex from 'vuex'

import ws from '../ws'
import {logger} from '@/helpers'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    ws: ws,
    users: [],
    chat: {
      id: null,
      name: 'Chat'
    },
    mainWindow: 'toolbar',
    chats: [],
    snack: {
      show: false,
      content: '',
      timeout: 10000
    },
    gapi: null,
    app: {
      active: false,
      id: null,
      email: null
    }
  },
  // getters: {
  //   app: (state) => state.app
  // },
  actions: {
    CHANGEWINDOW ({commit}, win) {
      commit('SETWINDOW', win)
    },
    PSEUDOPROFILE ({commit}, profile) {
      const snack = {
        show: true,
        content: `<div>Hola {{ profile.nickname }}!</div>
                  <v-btn>No soy {{ profile.nickname }}</v-btn>`,
        timeout: 10000
      }
      commit('SETSNACK', snack)
    },
    SIGN ({commit}, snack) {
      logger('SIGN')
      commit('SETSNACK', snack  )
    },
    ACTIVATE ({commit}, credentials) {
      commit('SET_CREDENTIALS', credentials)
      commit('SWITCH_ACTIVE')
    }
  },
  mutations: {
    SETUSERS (state, data) {
      logger('commiting SETUSERS')
      logger(data)
      state.users = data
    },

    SETCHAT (state, data) {
      state.chat.name = data.email
      state.chat.id = data.id
    },
    SETWINDOW (state, data) {
      state.mainWindow = data
    },
    ADDCHAT (state, data) {
      logger('addchat', data)
      state.chats.push({
        user: data.email
      })
    },
    SETSNACK (state, data) {
      state.snack = data
    },
    SET_GAPI (state, gapi) {
      logger('SET_GAPI')
      state.gapi = gapi
    },
    SET_CREDENTIALS (state, credentials) {
      logger('SET_CREDENTIALS')
      state.app.id = credentials.id
      state.app.email = credentials.email
    },
    SWITCH_ACTIVE: (state) => {
      logger('SWITCH_ACTIVE')
      state.app.active = !state.app.active
    }
  }
})
