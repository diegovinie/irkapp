/*
 * Revisar msg = {..., data: (json){header: string, type: string, data: mixed}}
 */

import store from '../store'

const url = 'ws://localhost:9000/'
const socket = new WebSocket(url)

socket.onopen = function (msg) {
  console.log('Status: ' + this.readyState)
}

/**
 * Acciones al entrar un mensaje
 *
 * @param {object} msg {... data: json}
 */
socket.onmessage = function (msg) {

  const pmsg = JSON.parse(msg.data)
  console.log(pmsg)

  // Si el servidor manda actualización
  if (pmsg.header == 'update') {
    // actualiza la lista de usuarios
    store.commit('SETUSERS', pmsg.data.usersList)
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

export default socket

// export default {
//   init () {
//     const url = 'ws://localhost:9000/'
//     try {
//       socket = new WebSocket(url)
//
//       socket.onopen = function (msg) {
//         console.log('Status: ' + this.readyState)
//       }
//
//       socket.onmessage = function (msg) {
//         console.log(msg)
//         // console.log('Received: '+ msg.data)
//       }
//
//       socket.onclose = function (msg) {
//         console.log('Disconnected: ' + this.readyState)
//       }
//     } catch (exc) {
//       console.log(exc)
//     }
//   },
//
//   send (socket, msg) {
//     try {
//       socket.send(msg)
//     } catch (e) {
//       console.log(e)
//     }
//   }
// }
