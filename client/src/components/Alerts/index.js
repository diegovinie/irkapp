/*
 * Controla la aparición de alertas de mensajes.
 * Pasa como props: id, from, content, type
 * Cuando se acaba el tiempo el mensaje se destruye y dispara un
 * evento destroy-message para eliminarlo de store
 * Recibe el array de mensajes de store.state.alerts
 */

import store from '@/store'
import Message from './Message'

export default {
  components: {
    Message
  },
  template:
    `<div :style="style">
      <message
        v-for="(msg, index) in messages"
        v-bind:key="index"
        v-bind:id="index"
        v-bind:from="msg.from"
        v-bind:content="msg.content"
        v-bind:type="msg.type">
      </message>
    </div>`,
  data: () => ({
    style: {
      width: "280px",
      right: "0",
      position: "fixed",
      bottom: '50px'
    }
  }),
  computed: {
    messages: () => store.state.alerts
  },
  watch: {
    messages: () => console.log('Mensaje nuevo')
  }
}
