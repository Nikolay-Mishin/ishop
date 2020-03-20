<?php

// получаем активную валюту из контейнера 
$curr = \ishop\App::$app->getProperty('currency');

$consts = json_encode(get_defined_constants(true)['user']);
$path = PATH;
$symboleLeft = $curr["symbol_left"];
$symboleRight = $curr["symbol_right"];

/**
  * Метод Object.freeze() замораживает объект: это значит, что он предотвращает добавление новых свойств к объекту, удаление старых свойств из объекта и изменение существующих свойств или значения их атрибутов перечисляемости, настраиваемости и записываемости. В сущности, объект становится эффективно неизменным. Метод возвращает замороженный объект.
  */

$script = "<!-- _variables - ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<script>
	const Ishop = {
		consts: $consts,
		path: '$path', // ссылка на главную - абсолютный путь (для ajax-запросов и другого)
		symboleLeft: '$symboleLeft', // символ слева ($ 1)
		symboleRight: '$symboleRight' // символ справа (1 руб.)
	};
	freeze(Ishop); // замораживает объект
	freeze(Ishop.consts); // замораживает объект
</script>";

return $script;
