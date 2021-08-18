<?php

require_once dirname(__DIR__).'/config/init.php'; // подключаем файл инициализации

use ishop\App;

new App(); // создаем экземпляр класса (идет вызов конструктора)

// debug(App::$app->getProperties()); // распечатываем массив параматров приложения
// throw new Exception('Страница не найдена!', 404); // создаем новое исключение
// debug(\ishop\Router::getRoutes()); // распечатываем таблицу маршрутов
