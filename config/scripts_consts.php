<?php

// получаем активную валюту из контейнера 
$curr = \ishop\App::$app->getProperty('currency');

$consts = json_encode(get_defined_constants(true)['user']);
$path = PATH;
$adminpath = ADMIN;
$symboleLeft = $curr["symbol_left"];
$symboleRight = $curr["symbol_right"];

$script = "<!-- _variables - ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<script>
	const Ishop = {
		consts: $consts,
		path: '$path', // ссылка на главную - абсолютный путь (для ajax-запросов и другого)
		adminpath: '$adminpath',
		symboleLeft: '$symboleLeft', // символ слева ($ 1)
		symboleRight: '$symboleRight' // символ справа (1 руб.)
	};
</script>";

return $script;
