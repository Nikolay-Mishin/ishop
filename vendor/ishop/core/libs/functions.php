<?php
// функции приложения

/**
 * Распечатывает массив и, если параметр $die = true, завершает выполнение скрипта
 * @param  {array}   $arr Properties from this object will be returned
 * @param  {boolean} $die флаг на завершение выполнения скрипта
 * @return {void}         ничего не возвращает
 */
function debug($arr, bool $die = false): void {
	echo '<pre>' . print_r($arr, true) . '</pre>';
	if ($die) die;
}

// перенаправляет на указанную страницу
function redirect(bool $http = false): void {
	// $http - адрес перенаправления
	// если $http передан то $redirect = адресу перенаправления, иначе обновить/перезапросить текущую страницу
	if ($http) {
		$redirect = $http;
	} else {
		// если в массиве $_SERVER есть страница, с которой пришел пользователь (предыдущая страница), то берем ее, иначе главную
		$redirect = referer_url();
	}
	$redirect = $redirect !== true ? $redirect : PATH . $_SERVER['REQUEST_URI'];
	if (isset($_SESSION['redirect'])) unset($_SESSION['redirect']);
	header("Location: $redirect"); // перенаправляем на сформированный адрес
	exit; // завершаем скрипт
}

// обертка для функции htmlspecialchars() - обрабатывет спец символы от html-инъекций
function h(string $str): string {
	// ENT_QUOTES - чтобы преобразовывать и " (двойные кавычки)
	// return htmlentities($str, ENT_QUOTES, 'UTF-8');
	return htmlspecialchars($str, ENT_QUOTES);
}

function referer_url(string $url = ''): string {
	$referer_url = isset($_SERVER['HTTP_REFERER']) ? rtrim($_SERVER['HTTP_REFERER'], '/') : PATH;
	return $_SESSION['redirect'] = $url && $referer_url == PATH ? $url : $referer_url;
}

// добавление разрядов к цене (1000 => 1 000)
function price_format(int $price, int $precision = 0, bool $round = false, string $mode = 'up'): string {
	// $precision - Количество десятичных знаков, до которых производится округление
	// (default) $mode - PHP_ROUND_HALF_UP
	return number_format($round ? number_round($price, $precision, $mode) : $price, 0, '', ' ');
}

// округление числа (при пересчеты цены)
function number_round(int $price, int $precision = 0, string $mode = 'up'): int {
	// $precision - Количество десятичных знаков, до которых производится округление
	// mode - Используйте одну из этих констант для задания способа округления.
	/**
	 * 1. PHP_ROUND_HALF_UP - Округляет val в большую сторону от нуля до precision десятичных знаков, если следующий знак находится посередине. То есть округляет 1.5 в 2 и -1.5 в -2.
	 * 2. PHP_ROUND_HALF_DOWN - Округляет val в меньшую сторону к нулю до precision десятичных знаков, если следующий знак находится посередине. То есть округляет 1.5 в 1 и -1.5 в -1.
	 * 3. PHP_ROUND_HALF_EVEN - Округляет val до precision десятичных знаков в сторону ближайшего четного знака.
	 * 4. PHP_ROUND_HALF_ODD - Округляет val до precision десятичных знаков в сторону ближайшего нечетного знака.
	 */
	
	switch ($mode) {
		case 'down':
			$mode = PHP_ROUND_HALF_DOWN;
			break;
		case 'even':
			$mode = PHP_ROUND_HALF_EVEN;
			break;
		case 'odd':
			$mode = PHP_ROUND_HALF_ODD;
			break;
		default:
			$mode = PHP_ROUND_HALF_UP;
			break;
	}
	return round($price, $precision, $mode);
}

// BLOG - cropping a title in a post
function mbCutString(string $string, int $length, string $postfix = '...', string $encoding = 'UTF-8'): string {
	if (mb_strlen($string, $encoding) <= $length) {
		return $string;
	}
	$temp = mb_substr($string, 0, $length, $encoding); // до какого символа обрезать
	$spacePosition  = mb_strripos($temp, " ", 0, $encoding); // ищем положение пробела
	$result = mb_substr($temp, 0, $spacePosition, $encoding); // обрезка до целого слова

	return $result . $postfix;
}

// CamelCase - для изменения имен контроллеров (каждое слово в верхнем регистре)
function lowerCamelCase(string $name): string {
	// ThisMethodName => this_method_name
	return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $name));
}

function upperCamelCase(string $name): string {
	// this_method_name => ThisMethodName
	return preg_replace_callback('/(?:^|_)(.?)/', function($matches){ return strtoupper($matches[1]); }, $name);
}

function callMethod(object $class, string $method, array $attrs = []): bool {
	return isCallable($class, $method) ? call_user_func_array([$class, $method], toArray($attrs)) : false;
}

function callPrivateMethod(object $obj, string $method, array $args) {
	if (isCallable($obj, $method)) {
		$method = getReflector($obj)->getMethod($method);
		$method->setAccessible(true);
		$result = $method->invokeArgs($obj, $args);
		$method->setAccessible(false);
	}
	return $result ?? null;
}

// возвращает информацию о классе (app\models\User)
function getReflector(string $class): \ReflectionClass {
	return new \ReflectionClass(is_object($class) ? get_class($class) : $class);
}

// возвращает короткое имя класса (app\models\User => User)
function getClassShortName(string $class): string {
	return getReflector($class)->getShortName();
}

// возвращает короткое имя класса (app\models\User)
function getClassName(string $class): string {
	return getReflector($class)->getName();
}

function isCallable(string $class, string $method): bool {
	return method_exists($class, $method) && is_callable([$class, $method]);
}

// метод для преобразования массива в объект (stdClass Object)
function dataDecode(&$data, string $output = null): object {
	$data_type = gettype($data); // получаем тип переданных данных
	if ($data_type == $output) return $data; // если тип переданных данных = типу выходных данных, вернем переданные данные
	$json = json_encode($data); // кодируем данные в json
	return json_decode($json, is_object($data_type)); // декодируем json в объект (true - ассоциативный массив)
}

function arrayUnset(array &$array, $items): array {
	foreach (toArray($items) as $item) {
		if (isset($array[$item])) {
			unset($array[$item]);
		}
	}
	return $array;
}

function objectUnset(object $obj, $props): object {
	foreach (toArray($props) as $prop) {
		if (property_exists($obj, $prop)) {
			unset($obj->$prop);
		}
	}
	return $obj;
}

function arrayMultiKeyExists(array $arr, array $keys): array {
	// array_diff_key - Вычисляет расхождение массивов, сравнивая ключи
	// Сравнивает ключи array1 с ключами array2 и возвращает разницу
	return !array_diff_key($keys, array_keys($arr));

	// Example
	$arr = ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
	$keys = ['green', 'yellow','cyan'];
	arrayMultiKeyExists($arr, $keys);
	// Will return
	//Array
	//(
	//    [0] => yellow
	//    [1] => cyan
	//)
}

function arrayGetValues(array $arr, array $keys): array {
	// array_intersect_key - Вычислить пересечение массивов, сравнивая ключи.
	// возвращает массив, содержащий все элементы array1, имеющие ключи, содержащиеся во всех последующих параметрах.
	// array_flip - Меняет местами ключи с их значениями в массиве.
	return array_intersect_key($arr, array_flip($keys));

	// Example
	$arr = ['a' => 123, 'b' => 213, 'c' => 321];
	$keys = ['b', 'c'];
	arrayGetValues($arr, $keys);

	// Will return
	//Array
	//(
	//    [b] => 213
	//    [c] => 321
	//)
}

function array_multi_key_exists(array $arrNeedles, array $arrHaystack, bool $matchAll = true): bool {
	// array_shift - Извлекает первый элемент массива
	// извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент.
	// Все числовые ключи будут изменены таким образом, что нумерация массива начнётся с нуля, строковые ключи останутся прежними.
	$found = array_key_exists(array_shift($arrNeedles), $arrHaystack);
   
	if ($found && (count($arrNeedles) == 0 || !$matchAll))
		return true;
   
	if (!$found && count($arrNeedles) == 0 || $matchAll)
		return false;
   
	return array_multi_key_exists($arrNeedles, $arrHaystack, $matchAll);
}

function arrayMerge(array $arr1, array $arr2): array {
	// array_intersect_key - Вычислить пересечение массивов, сравнивая ключи.
	// возвращает массив, содержащий все элементы array1, имеющие ключи, содержащиеся во всех последующих параметрах.
	// array_flip - Меняет местами ключи с их значениями в массиве.
	return array_replace_recursive($arr1, array_intersect_key($arr2, $arr1));

	// Example
	$defaults = [
		'id'            => 123456,
		'client_id'     => null,
		'client_secret' => null,
		'options'       => [
			'trusted'   => false,
			'active'    => false
		]
	];
	$options = [
		'client_id'       => 789,
		'client_secret'   => '5ebe2294ecd0e0f08eab7690d2a6ee69',
		'client_password' => '5f4dcc3b5aa765d61d8327deb882cf99', // ignored
		'client_name'     => 'IGNORED',                          // ignored
		'options'         => [
			'active'      => true
		]
	];
	arrayMerge($defaults, $options);

	// Will return
	//Array
	//(
	//    [id]            => 123456
	//    [client_id]     => 789
	//    [client_secret] => '5ebe2294ecd0e0f08eab7690d2a6ee69'
	//    [options]       => [
	//        'trusted'   => false
	//        'active'    => true
	//    ]
	//)
}

// если получен массив, возвращает его
// иначе возвращает полученное значение в виде массива
function toArray($attrs, bool $attrToArray = false, array $data = [], string $result = 'attrs'): array {
	$attrs = is_array($attrs) ? $attrs : toArray([$attrs], $attrToArray, $data);
	$data = is_array($data) ? $data : toArray($attrs, $attrToArray, [$data], 'data');
	if ($data) {
		foreach ($data as $item) {
			if (!in_array($item, $attrs)) $attrs[] = $item;
		}
	}
	if ($attrToArray) {
		foreach ($attrs as $key => $attr) {
			$attrs[$key] = is_array($attr) ? $attr : toArray([$attr]);
		}
	}
	return ${$result};
}

function varName($v): ?string {
	$trace = debug_backtrace();
	$vLine = file(__FILE__);
	$fLine = $vLine[$trace[0]['line'] - 1];
    debug([$vLine, $fLine, $trace]);
	preg_match("#\\$(\w+)#", $fLine, $match);
	return $match[1] ?? null;
}

function getTrace(int $id = 0, bool $args = false): object {
	$args = $args ? DEBUG_BACKTRACE_PROVIDE_OBJECT : DEBUG_BACKTRACE_IGNORE_ARGS;
	$trace = debug_backtrace($args, $id ? $id + 1 : $id);
	return $id ? (object) $trace[$id] : $trace;
}

function getCaller(int $id = 0): array {
	// position 0 would be the line that called debug_backtrace (getTrace) function so we ignore it
	// position 1 would be the line that called this (getCaller) function so we ignore it
	// position 2 would be the line that called getCaller function so we ignore it
	return getTrace($id + 3);
}

function getParentCaller(int $id = 0): array {
	return getTrace($id + 4);
}

function getContext(string $pattern = '/^__(get|set|call)$/'): array {
	return getMethodTrace($pattern) ?? getParentCaller();
}

function getMethodTrace(string $pattern): ?array {
	$search = preg_grep($pattern, array_column(getTrace(), 'function'));
	$key = array_keys($search)[0] ?? null;
	return $key ? getTrace($key + 1) : null;
}

function validateAttrs(object $class, array $attrs): array {
	foreach ($attrs as $key => $attr) {
		$attrs[$key] = callMethod($class, $attr) ?: getProp($class, $attr);
	}
	return $attrs;
}

function getProp($class, $attr) {
	if (property_exists($class, $attr)) {
		$isStatic = array_key_exists($attr, getReflector($class)->getStaticProperties());
		$attr = is_object($class) && !$isStatic ? $class->$attr : getClassName($class)::${$attr};
	}
	return $attr;
}

// определяет является ли переданная строка регулярным выраженияем
	// This doesn't test validity; but it looks like the question is Is there a good way of test if a string is a regex or normal string in PHP? and it does do that.
function isRegex(string $str): bool {
	return preg_match("/^\/[\s\S]+\/$/", $str);
}

// создает новый массив на из переданного ассоциативного массива и ключа, по значению которого необходимо сформировать новые ключи
// сортирует массив в по ключам/значениям в зависимости от переданного типа $sort_key ('key'/'value')
// $sort_flag - флаги сортировки
// newArray($info->getProperties(), 'name', ['Value' => [',', '.', 'floatval']])
function newArray(array $array, string $key = '', array $patterns = [], string $sort_key = 'key', string $sort_flag = SORT_NATURAL): array {
	// формируем массив
	$newArray = []; // новый массив
	foreach ($array as $k => $v) {
		// если переданы паттерны для замены значений, производим замену для каждого переданного паттерна
		if ($patterns) {
			foreach ($patterns as $key_p => $pattern) {
				$value = is_array($v) ? $v[$key_p] : $v->$key_p;
				// если передан массив паттернов, работаем с ним
				if (is_array($pattern)) {
					// $pattern[0] - паттерн для поиска совпадения
					// $pattern[1] - паттерн для замены совпадения
					// $pattern[2] - если передана не пустая строка, вызывает указанную пользовательскую функцию
					// опеределяем вызываемую функцию на основе типа паттерна (regExp/string)
					$func = isRegex($pattern[0]) ? 'preg_replace' : 'str_replace';
					// call_user_func_array - Вызывает callback-функцию с массивом параметров
					$val = call_user_func_array($func, [$pattern[0], $pattern[1], $value]);
					// call_user_func - Вызывает callback-функцию, заданную в первом параметре
					$val = !isset($pattern[2]) ? $value : call_user_func($pattern[2], $val);
				} elseif (is_string($pattern)) {
					$val = call_user_func($pattern, $value);
				}
				$v[$key_p] = $val;
			}
		}
		// если передан ключ для задания новых значений ключей для исходного массива массива, берем эти значения
		// иначе используем ключи из исходного массива
		$newKey = is_array($v) ? $v[$key] : $v->$key;
		$newArray[$key ? $newKey : $k] = $v;
	}

	// сортируем массив
	switch ($sort_key) {
		case 'key':
			ksort($newArray, $sort_flag); // Сортирует массив по ключам
		break;
		case 'value':
			sort($newArray, $sort_flag); // Сортирует массив
		break;
	}

	return $newArray;

	/* $key = 'CharCode'
	Исходный массив
	[0] => Array
	(
		[CharCode] => EUR
		[Value] => 68.7710
	)

	Массив с новыми ключами
	[EUR] => Array
	(
		[CharCode] => EUR
		[Value] => 68.7710
	)
	*/
		
	/* флаги сортировки
		* SORT_REGULAR - обычное сравнение элементов;
		* SORT_NUMERIC - числовое сравнение элементов
		* SORT_STRING - строковое сравнение элементов
		* SORT_LOCALE_STRING - сравнивает элементы как строки с учетом текущей локали. Используется локаль, которую можно изменять с помощью функции setlocale()
		* SORT_NATURAL - сравнение элементов как строк, используя естественное упорядочение, как в функции natsort()
		* SORT_FLAG_CASE - может быть объединен (побитовое ИЛИ) с SORT_STRING или SORT_NATURAL для сортировки строк без учета регистра.
		*/
	// natsort() - Эта функция реализует алгоритм сортировки, при котором порядок буквенно-цифровых строк будет привычным для человека. Такой алгоритм называется "natural ordering"
	/*
	Обычная сортировка
	Array
	(
		[3] => img1.png
		[1] => img10.png
		[0] => img12.png
		[2] => img2.png
	)

	Сортировка natural order
	Array
	(
		[3] => img1.png
		[2] => img2.png
		[1] => img10.png
		[0] => img12.png
	)
	*/
}
