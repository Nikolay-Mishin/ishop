<?php
// константы проекта

define("DEBUG", 1); // режим отладки вкл/откл
define("ROOT", dirname(__DIR__)); // корень проекта
define("WWW", ROOT . '/public'); // публичная папка доступная пользователю (все запросы перенаправляем на данную папку)
define("APP", ROOT . '/app'); // папка проекта
define("VENDOR", ROOT . '/vendor'); // папка приложения
define("CORE", VENDOR . '/ishop/core'); // папка ядра приложения
define("LIBS", VENDOR . '/ishop/core/libs'); // папка с библиотеками приложения
define("TMP", ROOT . '/tmp'); // папка с временными файлами
define("CACHE", TMP . '/cache'); // папка кэша
define("CONF", ROOT . '/config'); // папка конфигурации приложения
define("LAYOUT", 'watches'); // шаблон (тема) по умолчанию
// API ЦБ РФ - путь к файлу с курсами валют
define("API_CB_XML", 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date("d/m/Y")); // xml
define("API_CB", 'https://www.cbr-xml-daily.ru/daily_json.js'); // json

// http://ishop2.loc/public/index.php
$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
// http://ishop2.loc/public/
$app_path = preg_replace("#[^/]+$#", '', $app_path);
// http://ishop2.loc
$app_path = str_replace('/public/', '', $app_path);
define("PATH", $app_path); // корневой путь сайта
define("ADMIN", PATH . '/admin'); // путь админки

require_once VENDOR . '/autoload.php'; // подключаем скрипт автозагрузки (composer)
