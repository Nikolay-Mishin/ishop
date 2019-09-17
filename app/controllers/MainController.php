<?php

namespace app\controllers;

use ishop\Cache;

class MainController extends AppController {

    // public $layout = 'test'; // переопределяем свойство layout для данного контроллера (подключаемый шалон)

    public function indexAction(){
        $posts = \R::findAll('test'); // получаем все статьи из таблицы test
        // биндим данные (защита от sql-инъекций) вместо '?' подставляются указанные данные
        $post = \R::findOne('test', 'id = ?', [2]);
        // $this->layout = 'test'; // меняем свойство layout для данного контроллера (подключаемый шалон)
        // echo __METHOD__; // константа имя метода (app\controllers\MainController::indexAction)
        // заполняем мета-данные для данного контроллера
        // 'Главная страница' - можно использовать App::$app->getProperty('shop_name') | use ishop\App
        $this->setMeta('Главная страница', 'Описание...', 'Ключевики...');
        // данные для данного контроллера
        $name = 'John';
        $age = 30;
        $names = ['Andrey', 'Jane', 'Mike'];
        $cache = Cache::instance(); // создаем объект кэша
        // $cache->set('test', $names); // кэшируем данные
        // $cache->delete('test'); // очищаем кэш
        $data = $cache->get('test'); // получаем данные из кэша
        // если данные не получены, то кэшируем их заново
        if(!$data){
            $cache->set('test', $names);
        }
        debug($data); // распечатываем массив с данными кэша
        // заполняем данные для данного контроллера (передаем массив с данными)
        // ['name' => 'John', 'age' => 30, 'names' => ['Andrey', 'Jane',]]
        // compact - создает массив из переданных переменных (имен) по типу ключ-значение
        $this->set(compact('name', 'age', 'names', 'posts'));
    }

}