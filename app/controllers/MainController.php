<?php

namespace app\controllers;

class MainController extends AppController {

//    public $layout = 'test'; // переопределяем свойство layout для данного контроллера (подключаемый шалон)

    public function indexAction(){
//        $this->layout = 'test'; // меняем свойство layout для данного контроллера (подключаемый шалон)
//        echo __METHOD__; // константа имя метода (app\controllers\MainController::indexAction)
        // заполняем мета-данные для данного контроллера
        $this->setMeta('Главная страница', 'Описание...', 'Ключевики...');
        $name = 'John';
        $age = 30;
        $names = ['Andrey', 'Jane',];
        // заполняем данные для данного контроллера (передаем массив с данными)
        $this->set(compact('name', 'age', 'names'));
    }

}