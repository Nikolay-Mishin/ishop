<?php
// константы проекта
define("DEBUG", 1);
define("ROOT", dirname(__DIR__)); // корень проекта
define("WWW", ROOT . '/public'); // публичная папка доступная пользователю (все запросы перенаправляем на данную папку)
define("APP", ROOT . '/app'); // папка приложения
define("CORE", ROOT . '/vendor/ishop/core'); // папка ядра приложения
define("LIBS", ROOT . '/vendor/ishop/core/libs');
define("CACHE", ROOT . '/tmp/cache'); // папка кэша
define("CONF", ROOT . '/config'); // папка конфигурации проекта
define("LAYOUT", 'default'); // шаблон (тема) по умолчанию

// http://ishop2.loc/public/index.php
$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
// http://ishop2.loc/public/
$app_path = preg_replace("#[^/]+$#", '', $app_path);
// http://ishop2.loc
$app_path = str_replace('/public/', '', $app_path);
define("PATH", $app_path); // корневой путь сайта
define("ADMIN", PATH . '/admin'); // путь админки

require_once ROOT . '/vendor/autoload.php'; // подключаем скрипт автозагрузки (composer)