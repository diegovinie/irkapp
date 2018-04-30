<?php

namespace WebSocket;

use ghedipunk\PHPWebsockets\WebSocketUser;
use Models\User;
use Models\Message as MessagesTable;

trait SocketServerProcesses
{
    /**
     * en prueba
     */
    public function getUserList()
    {
        \hlp\logger('en send getUserList:');

        $userTable = new User;

        // Consulta en la base de datos
        $fetch = $userTable->find('socket', 'name', 'email', 'status')
            ->whereStatus('!=', 0)->exec();

        // Filtra que los usuarios realmente tengan socket activo
        $users = array_filter($fetch, function($item){
            return in_array($item['socket'], array_keys($this->users));
        });

        return $users;
    }

    /**
     * Envía la lista de usuarios como update a todos
     */
    public function sendUserList($user=null)
    {
        \hlp\logger('en send UserList:');

        $userTable = new User;

        // Consulta en la base de datos
        $fetch = $userTable->find('socket', 'name', 'email', 'status')
            ->whereStatus('!=', 0)->exec();

        // Filtra que los usuarios realmente tengan socket activo
        $users = array_filter($fetch, function($item){
          return in_array($item['socket'], array_keys($this->users));
        });

        // Respuesta
        $response = [
          'type' => 'update',
          'data' => [
            'header' => 'users_list',
            'content' => array_values($users)
          ]
        ];

        $resjn = json_encode($response);

        // Manda la lista a todos los usuarios conectados
        foreach ($this->users as $user) {
          $this->send($user, $resjn);
        }
    }


    /**
     * Empareja el id del socket con el id, email del usuario
     *
     * @param $user WebSocketUser
     * @param $creentials object {id, email}
     */
    public function setCredentials(WebSocketUser $user, $credentials)
    {
        \hlp\logger('en setCredentials');
        $id = (string)$credentials->id;
        $id = substr($id, 0 , 20);    // El pequeño problema de los enteros grandes
        $email = (string)$credentials->email;
        $name = (string)$credentials->name;

        $userTable = new User;

        // Busca si antes ha registrado al usuario
        $fetch = $userTable->findOne('email')
            ->whereId('=', $id)
            ->exec();

        if(!$fetch){
            // Si no aparece lo registra
            $castid = "CAST(".\hlp\quote($id)." AS UNSIGNED)";
            $qsocket = \hlp\quote($user->id);
            $qorigin = \hlp\quote($user->headers['origin']);
            $qname = \hlp\quote($name);
            $qemail = \hlp\quote($email);

            $userTable->insert($castid, $qsocket, $qorigin, $qname, $qemail, 1)->exec();

            \hlp\logger("Usuario $name insertado.", true);
        }
        else{
            // Si aparace solo actualiza el socketid y el origen
            if($fetch['email'] != $email){
                echo "algo pasa con el email\n";
                var_dump($fetch);
            }

            $userTable->update('socket', $user->id)
                ->whereId('=', $id)->exec();

            $userTable->update('origin', $user->headers['origin'])
                ->whereId('=', $id)->exec();

            $userTable->update('status', 1)
                ->whereId('=', $id)->exec();

                \hlp\logger("Usuario $name actualizado.", true);
        }
    }

    public function storeMessage(WebSocketUser $user, $message)
    {
      $msgTable = new MessagesTable;
      $from = bquote($user->id);
      $to = bquote($message->to);
      $msg = bquote($message->content);
      $msgTable->insert(null, $from, $to, $msg, 0, null, 0, null, null, null)
          ->exec();
      // getlastid
      // date: timestamp
      // return mesasge
    }

    /**
    * sin uso
    */
    public function checkUser($user)
    {
      \hlp\logger('en checkuser');

      $userTable = new User;

      // Revisa si el cliente está en la bd
      $profile = $userTable->findOne('id', 'nick', 'email')
      ->whereOrigin('=', $user->headers['origin'])
      ->descend('id')->exec();

      if($profile){
        // Si existe
        $this->send($user, json_encode([
          'header' => 'pseudoProfile',
          'content' => $profile
        ]));
      }
      else{
        // Si no existe
        $this->send($user, json_encode([
          'header' => 'request',
          'type' => 'sign'
        ]));
      }
    }

    /**
    * sin uso
    */
    public function checkEmail($user, $email)
    {
      $user = $userTable->find('id')
      ->whereEmail('=', "$email")->exec();

      $tpye = $user? 'password' : 'signup';

      $this->send($user, json_encode([
        'header' => 'request',
        'type' => $type
      ]));
    }

    /**
    * sin uso
    */
    public function checkPassword($user, $email, $password)
    {
      $profile = $userTable->findOne('id', 'nickname', 'avatar')
      ->whereEmail('=', "$email")
      ->wherePassword('=', md5($password))
      ->exec();
      $res = $profile? ['header' => 'profile', 'content' => $profile] :
      ['header' => 'error', 'type' => 'wrong_password'];

      $this->send($user, json_encode($res));
    }
}
