<?php

require_once dirname(__DIR__) . '/config/init.php'; // подключаем файл инициализации
require_once LIBS . '/functions.php'; // подключаем файл с функциями
require_once CONF . '/routes.php';

new \ishop\App(); // создаем экземпляр класса (идет вызов конструктора)

// debug(\ishop\App::$app->getProperties()); // распечатываем массив параматров приложения
// throw new Exception('Страница не найдена!', 404);