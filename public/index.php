<?php

require_once dirname(__DIR__) . '/config/init.php'; // подключаем файл инициализации
require_once LIBS . '/functions.php';

new \ishop\App(); // создаем экземпляр класса (идет вызов конструктора)

debug(\ishop\App::$app->getProperties());