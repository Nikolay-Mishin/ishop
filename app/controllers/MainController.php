<?php

namespace app\controllers;

// extends - MainController наследует класс AppController (расширяет/дополняет его)
class MainController extends AppController {

    // public $layout = 'test'; // переопределяем свойство layout для данного контроллера (подключаемый шалон)

    public function indexAction(){
        // $this->layout = 'test'; // меняем свойство layout для данного контроллера (подключаемый шалон)
        // echo __METHOD__; // константа имя метода (app\controllers\MainController::indexAction)
        // заполняем мета-данные для данного контроллера
        // 'Главная страница' - можно использовать App::$app->getProperty('shop_name') | use ishop\App
        $this->setMeta('Главная страница', 'Описание...', 'Ключевики...');
        // данные для данного контроллера
        $name = 'John';
        $age = 30;
        $names = ['Andrey', 'Jane',];
        // заполняем данные для данного контроллера (передаем массив с данными)
        // ['name' => 'John', 'age' => 30, 'names' => ['Andrey', 'Jane',]]
        // compact - создает массив из переданных переменных (имен) по типу ключ-значение
        $this->set(compact('name', 'age', 'names'));
    }

}