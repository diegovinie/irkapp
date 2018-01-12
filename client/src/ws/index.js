import store from '../store'

const url = 'ws://localhost:9000/'
const socket = new WebSocket(url)

socket.onopen = function (msg) {
  console.log('Status: ' + this.readyState)
}

socket.onmessage = function (msg) {
  // console.log(msg)
  const pmsg = JSON.parse(msg.data)
  console.log(pmsg)

  if (pmsg.header == 'update') {
    store.commit('SETUSERS', pmsg.data.usersList)
  }

  if (pmsg.header == 'request') {
    if(pmsg.type == 'pseudoProfile') {
      console.log('pseudoProfile')
      store.dispatch('PSEUDOPROFILE', pmsg.content)
    }

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
