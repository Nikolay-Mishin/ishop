<?php

use ishop\App;

$path = PATH;
$curr = App::$app->getProperty('currency'); // получаем активную валюту из контейнера 
$symboleLeft = $curr["symbol_left"];
$symboleRight = $curr["symbol_right"];

return compact('path', 'symboleLeft', 'symboleRight');
