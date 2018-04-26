
Es un json con la siguiente forma:
{
  header: [string],
  data: {
    status: [string]
  }
}

Obtener la lista de usuarios:
{
  header: 'get',
  data: 'users'
}

Chequear correo-e:
{
  header: 'post',
  type: 'email'
}

Chequear clave:
{
  header: 'post',
  type: 'password'
}

Indicar al servidor que estado ocupado:
{
  header: 'update',
  data: {
    status: n
  }
}
donde [int] n: 2 (ocupado)


Mandar mensaje a usuario:
{
  header: 'post',
  type: 'message',
  data: {
    user: [string] el id del usuario
    content: [string]
  }
}


Mensajes desde el servidor:

Actualización de la lista de usuarios:
{
  header: 'update',
  data: {
    usersList: [array]
  }
}

Peticiones del servidor:

Pide pseudoProfile:
{
  header: 'request',
  type: 'pseudoProfile'
}

Identificarse con correo-e:
{
  header: 'request',
  type: 'sign'
}

Menajes al servidor:

Manda id y email al iniciar:
{
  type: 'set',
  data: {
    header: 'credentials',
    content: {
      id: [int],
      name: [string],
      email: [string]
    }
  }
}
