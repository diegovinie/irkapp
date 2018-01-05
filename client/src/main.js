// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.css'
import 'vuetify/src/stylus/main.styl'
import './assets/css/materialicons.css'

import Chat from './components/Chat'
import UsersList from './components/UsersList'
import Options from './components/Options'

Vue.config.productionTip = false

Vue.use(Vuetify)

Vue.component('Chat', Chat)
Vue.component('UsersList', UsersList)
Vue.component('Options', Options)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  beforeCreate () {
    (function () {
      var cont = document.createElement('div')
      cont.id = 'app'
      document.body.appendChild(cont)
    })()
  },
  render: h => h(App)
})
