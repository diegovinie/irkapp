// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.css'
import 'vuetify/src/stylus/main.styl'
import './assets/css/materialicons.css'

import store from './store'

import ToolBar from './components/ToolBar2'
import Chat from './components/Chat'

Vue.use(Vuetify)

Vue.component('ToolBar', ToolBar)
Vue.component('Chat', Chat)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  beforeCreate () {
    (function () {
      var cont = document.createElement('div')
      cont.id = 'app'
      document.body.appendChild(cont)
    })()
  },
  render: h => h(App)
})
