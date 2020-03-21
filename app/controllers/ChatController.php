<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\ChatWorker;

class ChatController extends AppController {

    private $server = APP . "/widgets/chat/ChatWorker.php";
    private $server_name = 'ChatWorker';

    public function startAction(){
        debug("php $this->server");
        //$handle = popen("php $this->server", "r"); // server.php
    }

    public function stopAction(){
        $output = passthru("ps ax | grep $this->server_name\.php"); // server
        $ar = preg_split('/ /', $output);
        if(in_array('/usr/bin/php', $ar)){
            $pid = (int) $ar[0];
            posix_kill($pid, SIGKILL);
        }
    }

}
