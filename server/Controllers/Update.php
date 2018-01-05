<?php

namespace Controllers;

use Models\User;
use ghedipunk\PHPWebsockets\WebSocketServer;

class Update extends Controller
{
    public function getUserList(WebSocketServer $server)
    {
        $msg = new User();

        $list = $msg->find('id', 'email', 'status')
            ->whereStatus('!=', 0)->exec();
        $response = json_encode($list);

        foreach ($server->getUsers() as $user) {
            $server->send($user, $response);
        }

    }
}
