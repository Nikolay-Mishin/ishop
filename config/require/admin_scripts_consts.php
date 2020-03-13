<?php

$consts = json_encode(get_defined_constants(true)['user']);
$path = PATH;
$adminpath = ADMIN;

$script = "<!-- _variables - ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<script>
	const Ishop = {
		consts: $consts,
		path: '$path', // ссылка на главную - абсолютный путь (для ajax-запросов и другого)
		adminpath: '$adminpath'
	};
</script>";

return $script;
