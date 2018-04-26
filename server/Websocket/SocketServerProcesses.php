<?php

namespace WebSocket;

use ghedipunk\PHPWebsockets\WebSocketUser;
use Models\User;

trait SocketServerProcesses
{
    public function sendUserList($user=null)
    {
        \hlp\logger('en send UserList:');

        \hlp\logger(print_r($this->users));

        foreach ($this->users as $user) {
            $aa[] = $user->id;
        }

        $preRes = [
            'header' => 'update',
            'data' => [
                'usersList' => $aa
            ]
        ];

        $response = json_encode($preRes);

        if($user){
            $this->send($user, $response);
        }
        else{
            foreach ($this->users as $user) {
                $this->send($user, $response);
            }
        }
    }

    public function checkUser($user)
    {
        \hlp\logger('en checkuser');

        $useM = new User;

        // Revisa si el cliente está en la bd
        $profile = $useM->findOne('id', 'nick', 'email')
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

    public function checkEmail($user, $email)
    {
        $user = $useM->find('id')
                  ->whereEmail('=', "$email")->exec();

        $tpye = $user? 'password' : 'signup';

        $this->send($user, json_encode([
            'header' => 'request',
            'type' => $type
        ]));
    }

    public function checkPassword($user, $email, $password)
    {
        $profile = $useM->findOne('id', 'nickname', 'avatar')
                     ->whereEmail('=', "$email")
                     ->wherePassword('=', md5($password))
                     ->exec();
        $res = $profile? ['header' => 'profile', 'content' => $profile] :
            ['header' => 'error', 'type' => 'wrong_password'];

        $this->send($user, json_encode($res));
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
        }
    }
}
