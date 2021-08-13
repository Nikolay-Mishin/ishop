<?php

$consts = getConsts();
$path = PATH;
$adminpath = ADMIN;

/**
  * Метод Object.freeze() замораживает объект: это значит, что он предотвращает добавление новых свойств к объекту, удаление старых свойств из объекта и изменение существующих свойств или значения их атрибутов перечисляемости, настраиваемости и записываемости. В сущности, объект становится эффективно неизменным. Метод возвращает замороженный объект.
  */

$script = "<!-- _variables - ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<script>
	const Ishop = {
		consts: $consts,
		path: '$path', // ссылка на главную - абсолютный путь (для ajax-запросов и другого)
		adminpath: '$adminpath'
	};
	freeze(Ishop); // замораживает объект
	freeze(Ishop.consts); // замораживает объект
</script>";

return $script;
