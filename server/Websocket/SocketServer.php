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
use ghedipunk\PHPWebsockets\WebSocketUser;

class SocketServer extends WebSocketServer
{
    use SocketServerProcesses;
    use SocketServerMessages;

    public function __construct($addr, $port, $bufferLength = 2048)
    {
        parent::__construct($addr, $port, $bufferLength = 2048);

        // self::checkClientStatus();
    }


    /**
     * sin uso
     */
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

    /**
     * Maneja cada mensaje entrante
     */
    protected function process ($user, $message) {

        \hlp\logger("Mensaje entrante de: $user->id:\n$message");

        $m = json_decode($message);

        // Revisa el tipo de mensaje
        switch ($m->type) {
            // Para configurar algo en el servidor
            case 'set':
                switch ($m->data->header) {
                  // El primer mensaje cuando alguien conecta
                  case 'credentials':
                    $credentials = $m->data->content;
                    // Configura las credenciales en la bd
                    $this->setCredentials($user, $credentials);
                    // Actualiza la lista de usuarios de todos
                    $this->sendUserList();
                    // Avisa a los demás de la nueva conexión
                    $message = "$credentials->name conectado.";
                    $this->anyoneElseBroadcast($user, $message);
                    break;

                  default:
                    # code...
                    break;
                }
            // Solicita algo del servidor
            case 'get':
                # code...
                switch ($m->data) {
                    // Obtener la lista de usuarios
                    case 'users':
                    # code...
                    break;

                    default:
                    # code...
                    break;
                }
                break;

            // Envía algún parámetro para su revisión
            case 'check':
                switch ($m->data->key) {
                    // Verifica si existe el correo-e
                    case 'email':
                        # code...
                        break;

                    case 'password':

                        break;

                    default:
                        # code...
                        break;
                }
                break;

            // Mensaje de chat
            case 'message':
                $this->storeMessage($user, $message);
                $this->redirectMessage($user, $message);

                break;

            // Envía algo para actualizar
            case 'update':
                switch ($m->data->key) {
                    // Actualiza el estado
                    case 'status':
                        # code...
                        break;

                    default:
                        # code...
                        break;
                }
                break;

            default:
                # code...
                break;
        }
    }

    protected function connected ($user) {

        // $this->checkUser($user);

        $this->welcome($user);

      // Do nothing: This is just an echo server, there's no need to track the user.
      // However, if we did care about the users, we would probably have a cookie to
      // parse at this step, would be looking them up in permanent storage, etc.
    }

    protected function closed ($user) {

        $this->disconnectUser($user);

        $this->sendUserList();

      // Do nothing: This is where cleanup would go, in case the user had any sort of
      // open files or other objects associated with them.  This runs after the socket
      // has been closed, so there is no need to clean up the socket itself here.
    }

    /**
     * Procedimiento para actualizar y avisar la desconexión de un usuario
     */
    public function disconnectUser(WebSocketUser $user)
    {
        $userTable = new \Models\User;

        // Recupera el nombre de quien desconecta
        $fetch = $userTable->findOne('name')
            ->whereSocket('=', $user->id)->exec();

        $name = $fetch['name'];

        // Actualiza su estatus a 0
        $userTable->update('status', 0)
            ->whereSocket('=', $user->id)->exec();

        // Avisa a los demás usuarios de la desconexión
        $message = "$name desconectado.";
        $this->broadcast($message);

        \hlp\logger($message, true);
    }
}
