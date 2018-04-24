import store from '@/store'

// Si ya fue previamiente invocado usará la almacenada
if (store.state.gapi === null) {
  const {gapi} = require('../../lib/gapi')
  store.commit('SET_GAPI', gapi)
}

const gapi = store.state.gapi

const apiKey = 'AIzaSyA5YYzsAqiXx09Dvv7rtA_XeNjSmd1FNog'
const discoveryDocs = ["https://people.googleapis.com/$discovery/rest?version=v1"]
const clientId = '664922332794-4397sha7iqrbpb7p27f3q1b9vuu2ojf8.apps.googleusercontent.com'
const scope = 'profile'

function loadClientLib () {
  console.log(gapi)
  return gapi.load('client:auth2', initClient)
}

function initClient (ev) {
  return new Promise(function (resolve, reject) {
    gapi.client.init({
      apiKey,
      clientId,
      discoveryDocs,
      scope
    }).catch(function (err) {
      console.log('error en initClient')
      console.log(err)
      reject('d')
    }).then(function () {
      const auth = gapi.auth2.getAuthInstance()
      auth.isSignedIn.listen(updateSigninStatus)

      updateSigninStatus(auth.isSignedIn.get())
      resolve('h')
    })
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

export default {
  getIdEmail: function () {
    loadClientLib()
  }
}
