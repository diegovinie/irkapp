<?php
/**
 * Es la clase principal, extiende a WebSocketServer (Adam Alexander, 2012) y
 * se encarga de manejar las peticiones y mantener acciones de control.
 *
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */

namespace Websocket;

use ghedipunk\PHPWebsockets\WebSocketServer;

class SocketServer extends WebSocketServer
{
    public function __construct($addr, $port, $bufferLength = 2048)
    {
        parent::__construct($addr, $port, $bufferLength = 2048);

        self::checkClientStatus();
    }

    public function checkUser($user)
    {
        $useM = new \Models\User;

        $profile = $useM->findOne('id', 'nick', 'email')
                  ->whereOrigin('=', $user->headers['origin'])
                  ->descend('id')->exec();

        if($profile){
            $this->send($user, json_encode([
                'header' => 'pseudoProfile',
                'content' => $profile
            ]));
        }
        else{
            $this->send($user, json_encode([
                'header' => 'request',
                'type' => 'sign'
            ]));
        }
    }

    public function sendUserList($user=null)
    {
        $usr = new \Models\User();

        $usersList = $usr->find('id', 'email', 'status')
            ->whereStatus('!=', 0)->exec();

        $preRes = [
            'header' => 'update',
            'data' => [
                'usersList' => $usersList
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

    public function checkClientStatus($user=null)
    {
        $clis = new \Models\Client;
        $cliList = $clis->findAll()->whereStatus('!=', '0')->exec();

        // revisa cada item de la base de datos
        $inacs = array_filter($cliList, function($dbU){

            return !in_array(
                $dbU['id'],
                array_values( array_column($this->users, 'id') )
            );
        });

        foreach ($inacs as $ina) {
            $clis->update('status', '0')->whereId('=', "{$ina['id']}")->exec();
        }

    }
    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

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

    protected function process ($user, $message) {


        // $this->send($user,$message);
        //   \hlp\logger("de: {$GLOBALS['origin']} >> $message");
        // var_dump($user);
        \hlp\logger("de: $user->id >> $message");
        var_dump(json_decode($message));
        $m = json_decode($message);

        // Updates
        if($m->header == 'update'){
          // Actualiza el status
          if($m->data->status){
            $usr = new \Models\User;
            $usr->update('status', $m->data->status)
              ->whereName('=', $user->id)->exec();
          }
        }

        // Gets
        if($m->header == 'get'){
          // Pide la lista de usuarios
          if($m->data == 'users'){

            $this->sendUserList($user);
          }
        }

        // Posts
        if($m->header == 'post'){
            // Manda a chequear el email
            if($m->type == 'email'){

                $this->checkEmail($user, $m->data);
            }
            // Manda a chequear la clave
            if($m->type == 'password'){
                $this->checkPassword($user, ...$m->data);
            }

            // Manda mensaje a un usuario
            if($m->type == 'message'){

              $this->send($m->data->user, $m->data->content)
            }

        }

    }

    protected function connected ($user) {

        // el origen es conocido? welcomeback : ask sign
        // sign? log : send annonymus
        // log? auth & send profile : signup
        // signup? reg, auth & send profile : send annonymus

        $this->checkUser($user);


        // $this->sendUserList();

      // Do nothing: This is just an echo server, there's no need to track the user.
      // However, if we did care about the users, we would probably have a cookie to
      // parse at this step, would be looking them up in permanent storage, etc.
    }

    protected function closed ($user) {

        \Controllers\Registration::disconnect($user);

        $this->sendUserList();

      // Do nothing: This is where cleanup would go, in case the user had any sort of
      // open files or other objects associated with them.  This runs after the socket
      // has been closed, so there is no need to clean up the socket itself here.
    }
}
