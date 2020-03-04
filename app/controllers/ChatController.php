<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\ChatWorker;

class ChatController extends AppController {

    public function indexAction(){
        ChatWorker::run();
        $this->setMeta('Чат');
    }

}
