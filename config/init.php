<?php
// константы проекта
define("DEBUG", 1);
define("ROOT", dirname(__DIR__)); // корень проекта
define("WWW", ROOT . '/public'); // публичная папка доступная пользователю (все запросы перенаправляем на данную папку)
define("APP", ROOT . '/app'); // папка проекта
// папка приложения
define("VENDOR", ROOT . '/vendor');
define("CORE", VENDOR . '/ishop/core'); // папка ядра приложения
define("LIBS", VENDOR . '/ishop/core/libs');
// папка с временными файлами
define("TMP", ROOT . '/tmp');
define("CACHE", TMP . '/cache'); // папка кэша
define("CONF", ROOT . '/config'); // папка конфигурации приложения
define("LAYOUT", 'default'); // шаблон (тема) по умолчанию

// http://ishop2.loc/public/index.php
$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
// http://ishop2.loc/public/
$app_path = preg_replace("#[^/]+$#", '', $app_path);
// http://ishop2.loc
$app_path = str_replace('/public/', '', $app_path);
define("PATH", $app_path); // корневой путь сайта
define("ADMIN", PATH . '/admin'); // путь админки

require_once VENDOR . '/autoload.php'; // подключаем скрипт автозагрузки (composer)