<?php

namespace Controllers;

// use Models\User;
use ghedipunk\PHPWebsockets\WebSocketUser;

class Registration extends Controller
{
    public function connect(WebSocketUser $user)
    {
        $cliM = new \Models\Client;
        $useM = new \Models\User;

        $a = $useM->find('id', 'nickname', 'avatar')->whereClient('=', "$user->id")->exec();

        if($a){
            // retorna
        }
        else{
            $b = $useM->find('id', 'nickname', 'avatar')->whereOrigin('=', "{$user->headers['origin']}")->descend('id', 1)->exec();
            if($b){
                // retorna
            }
            else{

            }
        }

        // inserta el nuevo usuario
        $cliM->insert(
            "'" .$user->id ."'",
            "'" .$user->headers['origin'] ."'",
            'NULL',
            1)->exec();

          $id = $cliM->db->query("SELECT COUNT(id) FROM clients")->fetchColumn(0);

          // act. el numbre de usuario
          $cliM->update('email', "invitado$id")
              ->whereId('=', $id)->exec();
    }

    public function disconnect(WebSocketUser $user)
    {
        $cliM = new \Models\User;

        $cliM->update('status', 0)
            ->whereName('=', $user->id)->exec();
    }
}
