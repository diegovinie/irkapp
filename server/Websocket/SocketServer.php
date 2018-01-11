<?php

namespace Websocket;

use ghedipunk\PHPWebsockets\WebSocketServer;

class SocketServer extends WebSocketServer
{
    public function __construct($addr, $port, $bufferLength = 2048)
    {
        parent::__construct($addr, $port, $bufferLength = 2048);

        self::checkUserStatus();
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

    public function checkUserStatus($user=null)
    {
        $users = new \Models\User;
        $usersList = $users->findAll()->whereStatus('!=', '0')->exec();

        // revisa cada item de la base de datos
        $inac = array_filter($usersList, function($dbU){

            return !in_array(
                $dbU['name'],
                array_values( array_column($this->users, 'id') )
            );
        });

        foreach ($inac as $user) {
            var_dump($user['id']);
            $users->update('status', '0')->whereId('=', "{$user['id']}")->exec();
        }

    }
    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

    protected function process ($user, $message) {


      // $this->send($user,$message);
    //   \hlp\logger("de: {$GLOBALS['origin']} >> $message");
        // var_dump($user);
        \hlp\logger("de: $user->id >> $message");
        var_dump(json_decode($message));
        $m = json_decode($message);

        if($m->header == 'update'){
          if($m->data->status){
            $usr = new \Models\User;
            $usr->update('status', $m->data->status)
              ->whereName('=', $user->id)->exec();
          }
        }

        if($m->header == 'get'){
          if($m->data == 'users'){

            $this->sendUserList($user);
          }
        }

    }

    protected function connected ($user) {


        // ob_start();
        // var_dump($this);
        // file_put_contents('ser', ob_get_clean());
        // ob_end_clean();

        \Controllers\Registration::connect($user);
        echo "despues de connect \n";
        $this->sendUserList();

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
