<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\ChatWorker;

class ChatController extends AppController {

    public function startAction(){
        $handle = popen("php server.php", "r");
    }

    public function stopAction(){
        $output = passthru('ps ax | grep server\.php');
        $ar = preg_split('/ /', $output);
        if(in_array('/usr/bin/php', $ar)){
            $pid = (int) $ar[0];
            posix_kill($pid, SIGKILL);
        }
    }

}
