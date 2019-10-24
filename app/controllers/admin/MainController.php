<?php

namespace app\controllers\admin;

// Контроллер главной страницы админки

class MainController extends AppController {

    public function indexAction(){
        $this->setMeta('Панель управления'); // устанавливаем мета-данные
    }

}