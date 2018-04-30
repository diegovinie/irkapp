/*
 * Revisar msg = {..., data: (json){header: string, type: string, data: mixed}}
 */

import store from '../store'
import {logger} from '@/helpers'

const url = 'ws://localhost:9000/'

/**
 * Crea el WebSocket
 *
 * @return {WebSocket} El WebSocket para ser almacenado en store
 */
export default function createWebSocket () {
  // El objeto a devolver
  const socket = new WebSocket(url)

  /**
   * Manda las credenciales una vez establecida la conexión
   *
   * @param {obj} msg mensaje de abierto del servidor
   */
  socket.onopen = function (msg) {
    logger(`Conexión establecida, cod: ${this.readyState}`)

    // Datos para saltarse la autenticación de google
    // const content = {
    //   id: "12345678901234567890",
    //   email: 'cosmonauta@cosmo.com',
    //   name: 'Diego'
    // }

    // Datos de la autenticación de google
    const content = {
      id: store.state.app.id,
      email: store.state.app.email,
      name: store.state.app.name
    }

    const response = {
      type: 'set',
      data: {
        header: 'credentials',
        content
      }
    }
    // Manda las credenciales al servidor
    socket.send(JSON.stringify(response))
  }

  /**
  * Acciones al entrar un mensaje
  *
  * @param {object} msg {... data: json}
  */
  socket.onmessage = function (msg) {

    // Tipos de mensajes
    const msgtypes = [
      'srvmsg',
      'prvmsg',
      'grpmsg'
    ]

    // Mensaje parseado
    const pmsg = JSON.parse(msg.data)
    logger(pmsg)

    // Si el servidor manda actualización
    if (pmsg.type == 'update') {
      if (pmsg.data.header == 'users_list') {
        console.log(pmsg.data.content);
        // actualiza la lista de usuarios
        store.commit('SET_USERS_LIST', pmsg.data.content)
      }
    }

    // Si llega un mensaje de cualquier tipo
    if (msgtypes.indexOf(pmsg.type) !== -1) {
      const chats = store.state.chats
      // Busca si ya hay conversaciones con el remitente
      const chat = chats.find((item) => item.from == pmsg.from)
      if (!chat) {
        // Si no hay chat previo
        let data = {
          from: pmsg.from,
          id: chats.length + 1,
          conversation: [pmsg.content]
        }
        store.commit('ADD_CHAT', data)
      } else {
        // Solo agrega el mensaje al array conversation
        store.commit('ADD_MSG', chat, pmsg.data)
      }
      // Crear la alerta de mensaje nuevo
      store.commit('ADD_ALERT', {
        from: pmsg.from,
        content: pmsg.content,
        type: pmsg.type
      })
    }

    // Se el servidor pide algo
    if (pmsg.header == 'request') {
      // perfil
      if(pmsg.type == 'pseudoProfile') {
        console.log('pseudoProfile')
        // Manda el perfil??
        store.dispatch('PSEUDOPROFILE', pmsg.content)
      }
      // no recuerdo ???
      if(pmsg.type == 'sign') {
        console.log('sign')
        const temp = '<div><input type="text" placeholder="correo-e" onsubmit.prevent="send()" /></div>'
        store.dispatch('SIGN', {show: true, timeout: 10000, content: temp})
      }
    }
  }

  socket.onclose = function (msg) {
    console.log('Disconnected: ' + this.readyState)
  }

  return socket
}
