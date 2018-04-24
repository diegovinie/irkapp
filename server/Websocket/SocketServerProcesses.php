<?php

namespace WebSocket;

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

        $useM = new \Models\User;

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
}
