<?php

namespace Websocket;

use ghedipunk\PHPWebsockets\WebSocketServer;

class SocketServer extends WebSocketServer
{
    public function sendUserList()
    {
        $usr = new \Models\User();

        $list = $usr->find('id', 'email', 'status')
            ->whereStatus('!=', 0)->exec();

        $response = json_encode($list);

        foreach ($this->users as $user) {
            $this->send($user, $response);
        }
    }
    //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

    protected function process ($user, $message) {


      $this->send($user,$message);
    //   \hlp\logger("de: {$GLOBALS['origin']} >> $message");
        // var_dump($user);
        \hlp\logger("de: $user->id >> $message");

    }

    protected function connected ($user) {

        // ob_start();
        // var_dump($this);
        // file_put_contents('ser', ob_get_clean());
        // ob_end_clean();

        \Controllers\Registration::connect($user);

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
