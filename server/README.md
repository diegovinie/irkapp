
Es un json con la siguiente forma:
{
  type: [string],
  data: {
    header: [string],
    from: [string],
    content: [mixed]
  }
}


Mensajes desde el servidor:

Actualización de la lista de usuarios:
{
  type: 'update',
  data: {
    header: 'user_list',
    content: ['socket' => [string], 'name' => [string], 'email' => [string]]
  }
}

Mensajes del sistema:
{
  type: 'message',
  data: {
    from: 'server',
    content: [string]
  }
}


Menajes al servidor:

Manda id, name y email al iniciar:
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
--------------------------------------------------
(no revisado)

Mandar mensaje a usuario:
{
  header: 'post',
  type: 'message',
  data: {
    user: [string] el id del usuario
    content: [string]
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
