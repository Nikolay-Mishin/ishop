<?php

require_once dirname(__DIR__).'/config/init.php'; // подключаем файл инициализации

new \ishop\App(); // создаем экземпляр класса (идет вызов конструктора)

// debug(\ishop\App::$app->getProperties()); // распечатываем массив параматров приложения
// throw new Exception('Страница не найдена!', 404); // создаем новое исключение
// debug(\ishop\Router::getRoutes()); // распечатываем таблицу маршрутов
