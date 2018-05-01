/**
 * Métodos para comunicarse con la Api de Google y autenticar
 *
 * Actualmente en uso la versión 'async' a través del objeto 'asyn'
 */

import store from '@/store'
import {logger, thenableRejection as gapiReject} from '@/helpers'

// Si ya fue previamiente invocado usará la almacenada
if (store.state.gapi === null) {
  const {gapi} = require('../../lib/gapi')
  store.commit('SET_GAPI', gapi)
}

const gapi = store.state.gapi

// Parámetros para conectarse a Google
const apiKey = 'AIzaSyA5YYzsAqiXx09Dvv7rtA_XeNjSmd1FNog'
const discoveryDocs = ["https://people.googleapis.com/$discovery/rest?version=v1"]
const clientId = '664922332794-4397sha7iqrbpb7p27f3q1b9vuu2ojf8.apps.googleusercontent.com'
const scope = 'profile'

function loadClientLib () {
  return gapi.load('client:auth2', initClient)
}

function initClient (ev) {
  return new Promise(function (resolve, reject) {
    gapi.client.init({
      apiKey,
      clientId,
      discoveryDocs,
      scope
    }).then(function () {
      const auth = gapi.auth2.getAuthInstance()
      auth.isSignedIn.listen(updateSigninStatus)

      updateSigninStatus(auth.isSignedIn.get())
      resolve('h')
    }, gapiReject)
  })
}

function updateSigninStatus (signed) {
  return new Promise(function (resolve, reject) {

    if (signed) {
      let cred = getId()
    } else {
      gapi.auth2.getAuthInstance().signIn().catch(function (err) {
        console.log('no autenticado no llamada')
        console.log(err)
      })
    }
  })
}

function getId () {
  return new Promise(function (resolve, reject) {
    gapi.client.request({
      path: 'https://www.googleapis.com/oauth2/v1/userinfo'
    }).then(function (res) {
      let credentials = {
        id: res.result.id,
        email: res.result.email
      }
      // console.log(credentials)
      // Guarda la id y email en store
      store.dispatch('ACTIVATE', credentials)
      // Retorna id y email
      resolve(credentials)
    }, function (err) {
      console.log('error en getId')
      console.log(err)
      reject('problemas para consultar la api')
    })
  })
}

/**
 * versiones asíncronas
 */
const asyn = {
  /**
   * Carga las librerías
   *
   * @return {object} gapi
   */
  getGapiReady: async function () {
    await new Promise(function (resolve, reject) {
      try {
        gapi.load('client:auth2', resolve)
      } catch (e) {
        logger('Problemas para cargar las librerías')
        console.log(e)
        reject()
      }
    })
    return gapi
  },

  /**
   * Autentica al cliente
   *
   * @return {object} gapi
   */
  setClient: async function () {
    await new Promise(function (resolve, reject) {
      gapi.client.init({
        apiKey,
        clientId,
        discoveryDocs,
        scope
      }).then(function () {
        const auth = gapi.auth2.getAuthInstance()
        // auth.isSignedIn.listen(updateSigninStatus)
        auth.signIn().then(function (res) {
          if (auth.isSignedIn.get()) {
            resolve()
          } else {
            reject()
          }
        }, function (err) {
          console.log('setClient reject')
          console.log(err)
          reject(err)
        })
      })
    })
    return gapi
  },

  /**
   * Consulta los datos del usuario
   *
   * @return {object} {id, givenName, email}
   */
  getIdentity: function () {

    const auth = gapi.auth2.getAuthInstance()

    if (auth.isSignedIn.get()) {
      return new Promise(function (resolve, reject) {
        gapi.client.request({
          path: 'https://www.googleapis.com/oauth2/v1/userinfo'
        }).then(function (res) {
          let credentials = {
            id: res.result.id,
            email: res.result.email,
            name: res.result.given_name
          }
          resolve(credentials)
        }, gapiReject)
      })
    } else {
      logger('getIdentity: Usuario no autenticado')
      return null
    }
  }
}

export default {
  asyn,
  getIdEmail: function () {
    loadClientLib()
  }
}
