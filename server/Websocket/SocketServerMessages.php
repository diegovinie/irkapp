<?php
namespace WebSocket;

use ghedipunk\PHPWebsockets\WebSocketUser;

/**
 * Métodos para mandar mensajes. Funciona con WebSocket
 */
trait SocketServerMessages
{
    /**
     * Envía mensaje a todos los conectados
     */
    public function broadcast(string $message)
    {
        $response = [
            'type' => 'message',
            'data' => [
                'from' => 'server',
                'content'=> $message
          ]
        ];
        foreach ($this->users as $user) {
            $this->send($user, json_encode($response));
        }
    }

    /**
     * Envía mensaje a todos menos el indicado
     */
    public function anyoneElseBroadcast(WebSocketUser $userSkip, string $message)
    {
        $response = [
            'type' => 'message',
            'data' => [
                'from' => 'server',
                'content'=> $message
          ]
        ];
        foreach ($this->users as $user) {
            if($user == $userSkip) continue;

            $this->send($user, json_encode($response));
        }
    }

    /**
     * Mensaje de bienvenida
     */
    public function welcome(WebSocketUser $user)
    {
      $welcome = "Hola!";

      $message = [
        'type' => 'srvmsg',
        'from' => 'server',
        'content' => $welcome        
      ];
      $this->send($user, json_encode($message));
    }

    public function redirectMessage($message)
    {
      // encontrar user con message.to
      // send
    }
}
