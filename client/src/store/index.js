import Vue from 'vue'
import Vuex from 'vuex'

import ws from '../ws'
import {logger} from '@/helpers'
import {alerts as datosAlerts} from '../../test/datosprueba'
import {users as datosUsers} from '../../test/datosprueba'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // borrar
    ws: ws,
    // Objeto WebSocket
    socket: null,
    // Los usuarios de contactos
    users: datosUsers,
    // Los datos del chat activo en la ventana Chat
    chat: {
      id: null,
      from: 'Chat',
      conversation: null
    },
    // Cambia el selector de ventanas entre toolbar y chat
    mainWindow: 'toolbar',
    // Almacena todas las conversaciones con los usuarios
    chats: [],
    // borrar
    snack: {
      show: false,
      content: '',
      timeout: 10000
    },
    // La instancia de Google Apis
    gapi: null,
    // Datos generales de quien esta logeado
    app: {
      // Si la aplicación está activa
      active: false,
      id: null,
      name: null,
      email: null
    },
    // Array con los mensajes alert
    alerts: []
  },

  actions: {
    // Cambiar la venta principal
    CHANGEWINDOW ({commit}, win) {
      commit('SETWINDOW', win)
    },
    // borrar
    PSEUDOPROFILE ({commit}, profile) {
      const snack = {
        show: true,
        content: `<div>Hola {{ profile.nickname }}!</div>
                  <v-btn>No soy {{ profile.nickname }}</v-btn>`,
        timeout: 10000
      }
      commit('SETSNACK', snack)
    },
    // borrar
    SIGN ({commit}, snack) {
      logger('SIGN')
      commit('SETSNACK', snack  )
    },
    // Crea el WebSocket y activa la aplicación
    ACTIVATE ({commit}, credentials) {
      const socket = ws() // transitoriamente
      commit('SET_CREDENTIALS', credentials)
      commit('SWITCH_ACTIVE')
      commit('SET_SOCKET', socket)
    },
    // Busca los datos y cambia el chat activo
    CHANGE_CHAT ({state, commit}, user) {
      let chat = state.chats.find((item) => {
        return item.from === user
      })
      commit('SET_CHAT', chat)
    }
  },
  mutations: {
    // Fija la lista de usuarios que se ve en contactos
    SET_USERS_LIST (state, data) {
      logger('commiting SET_USERS_LIST')
      state.users = data
      logger(data[0]['name'])
    },
    // Fija los datos del chat activo
    SET_CHAT (state, data) {
      logger('commiting SET_CHAT')
      state.chat.from = data.from
      state.chat.conversation = data.conversation
    },
    // Fija la ventana principal
    SETWINDOW (state, data) {
      logger('commiting SETWINDOW')
      state.mainWindow = data
    },
    // Agrega un chat con un nuevo usuario al array chats
    ADD_CHAT (state, data) {
      logger('commiting ADD_CHAT', data)
      state.chats.push(data)
    },
    // Agrega un nuevo mensaje a la conversación
    ADD_MSG (state, chat, message) {
      logger('commiting ADD_MSG')
      const chate = state.chats.find((item) => item === chat)
      chate.conversation.push(message)
    },
    // borrar
    SETSNACK (state, data) {
      state.snack = data
    },
    // No se usa?
    SET_GAPI (state, gapi) {
      logger('commiting SET_GAPI')
      state.gapi = gapi
    },
    // Fija las credenciales de la cuenta de Google
    SET_CREDENTIALS (state, credentials) {
      logger('commiting SET_CREDENTIALS')
      state.app.id = credentials.id
      state.app.email = credentials.email
      state.app.name = credentials.name
    },
    // Cambia la aplicación a activa
    SWITCH_ACTIVE: (state) => {
      logger('commiting SWITCH_ACTIVE')
      state.app.active = !state.app.active
    },
    // Elimina todas las alert de array
    CLEAR_ALERTS: (state) => {
      logger('commiting CLEAR_ALERTS')
      state.alerts = []
    },
    // Agrega un nuevo mensaje de alert
    ADD_ALERT: (state, msg) =>{
      logger('commiting ADD_ALERT')
      state.alerts.push(msg)
    },
    // Fija el objeto WebSocket
    SET_SOCKET: (state, socket) => {
      logger('commiting SET_SOCKET')
      state.socket = socket
    }
  }
})
