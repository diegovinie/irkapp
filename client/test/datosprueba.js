export const alerts = [
  {
    type: 'privmsg',
    from: 'Maria',
    content: 'hola amor'
  },
  {
    type: 'srvmsg',
    from: 'Servidor',
    content: 'hay un problema'
  },
  {
    type: 'privmsg',
    from: 'Pepe',
    content: 'joer tio'
  }
]

export const users = [
  {
    id: 'dsdsds343',
    name: 'Laura',
    email: 'lau@gooo.com',
    status: 0
  }
]

// Mensaje alert de prueba
export function tempAlert (store) {
  setTimeout( () => {
    store.commit('ADD_ALERT', {
      from: 'Che',
      content: 'porque se fue',
      type: 'privmsg'
    })
  }, 7000)
}
