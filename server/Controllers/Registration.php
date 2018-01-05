<?php

namespace Controllers;

use Models\User;
use ghedipunk\PHPWebsockets\WebSocketUser;

class Registration extends Controller
{
    public function connect(WebSocketUser $user)
    {
        $userModel = new User;

        // inserta el nuevo usuario
        $userModel->insert(
            'null',
            "'" .$user->id ."'",
            'null',
            "'" .$user->headers['origin'] ."'",
            1
            )->exec();

          $id = $userModel->getLastId();

          // act. el numbre de usuario
          $userModel->update('email', "invitado$id")
              ->whereId('=', $id)->exec();
    }

    public function disconnect(WebSocketUser $user)
    {
        $userModel = new User;

        $userModel->update('status', 0)
            ->whereName('=', $user->id)->exec();
    }
}
