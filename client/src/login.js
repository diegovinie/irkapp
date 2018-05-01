import store from '@/store'
import auth from '@/api/auth'

/**
 * Método para autenticar en Google e iniciar el WebSocket
 *
 * Incluido en App
 */
export default async function () {
  // El botón start en App
  this.start = 'Iniciando...'
  var gapiReady = await auth.asyn.getGapiReady()
  // Debe esperar por gapyReady
  var clientDone = auth.asyn.setClient()

  // Cuando las dos se resuelvan continua si no avisa
  Promise.all([gapiReady, clientDone])
  .then(async () => {
    // Consulta las credenciales del usuario
    const credentials = await auth.asyn.getIdentity()

    if (typeof credentials === 'object') {
      // Activa, fija las credenciales y conecta con el WebSocket
      store.dispatch('ACTIVATE', credentials)
    } else {
      console.log('Error')
      store.commit('ADD_ALERT', {
        from: 'Sistema',
        type: 'error',
        content: 'No se pudieron obtener los datos del usuario'
      })
    }
  })
  .catch(err => {
    // El botón de iniciar
    this.start = 'Iniciar'
    // Crea una nueva notificación
    store.commit('ADD_ALERT', {
      from: 'Sistema',
      type: 'error',
      content: err.error
    })
  })
}
