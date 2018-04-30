import Vue from 'vue'
import Vuex from 'vuex'

import ws from '../ws'
import {logger} from '@/helpers'
import {alerts as datosAlerts} from '../../test/datosprueba'
import {users as datosUsers} from '../../test/datosprueba'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    ws: ws,
    users: datosUsers,
    chat: {
      id: null,
      name: 'Chat'
    },
    mainWindow: 'toolbar',
    chats: [{
      id: null,
      from: null,
      conversation: [

      ]
    }],
    snack: {
      show: false,
      content: '',
      timeout: 10000
    },
    gapi: null,
    app: {
      active: true,
      id: null,
      name: null,
      email: null
    },
    alerts: datosAlerts
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
      const socket = ws() // transitoriamente
    }
  },
  mutations: {
    SET_USERS_LIST (state, data) {
      logger('commiting SET_USERS_LIST')
      state.users = data
      logger(data[0]['name'])
    },

    SETCHAT (state, data) {
      state.chat.name = data.email
      state.chat.id = data.id
    },
    SETWINDOW (state, data) {
      state.mainWindow = data
    },
    ADD_CHAT (state, data) {
      logger('add_chat', data)
      state.chats.push()
    },
    ADD_MSG (state, chat, message) {
      logger('add_msg')
      const chate = state.chats.find((item) => item === chat)
      chate.conversation.push(message)
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
      state.app.name = credentials.name
    },
    SWITCH_ACTIVE: (state) => {
      logger('SWITCH_ACTIVE')
      state.app.active = !state.app.active
    },
    CLEAR_ALERTS: (state) => {
      logger('CLEAR_ALERTS')
      state.alerts = []
    },

    ADD_ALERT: (state, msg) =>{
      logger('ADD_ALERT')
      state.alerts.push(msg)
    }
  }
})
