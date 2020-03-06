<?php
// функции приложения

// распечатывает массив
// если параметр $die = true, завершает выполнение скрипта
function debug($arr, $die = false){
	echo '<pre>' . print_r($arr, true) . '</pre>';
	if($die) die;
}

// перенаправляет на указанную страницу
function redirect($http = false){
	// $http - адрес перенаправления
	// если $http передан то $redirect = адресу перенаправления, иначе обновить/перезапросить текущую страницу
	if($http){
		$redirect = $http;
	}else{
		// если в массиве $_SERVER есть страница, с которой пришел пользователь (предыдущая страница), то берем ее, иначе главную
		$redirect = referer_url();
	}
	$redirect = $redirect !== true ? $redirect : PATH . $_SERVER['REQUEST_URI'];
	if(isset($_SESSION['redirect'])) unset($_SESSION['redirect']);
	header("Location: $redirect"); // перенаправляем на сформированный адрес
	exit; // завершаем скрипт
}

function referer_url($url = ''){
	$referer_url = isset($_SERVER['HTTP_REFERER']) ? rtrim($_SERVER['HTTP_REFERER'], '/') : PATH;
	return $_SESSION['redirect'] = $url && $referer_url == PATH ? $url : $referer_url;
}

// обертка для функции htmlspecialchars() - обрабатывет спец символы от html-инъекций
function h($str){
	// ENT_QUOTES - чтобы преобразовывать и " (двойные кавычки)
	// return htmlentities($str, ENT_QUOTES, 'UTF-8');
	return htmlspecialchars($str, ENT_QUOTES);
}

// добавление разрядов к цене (1000 => 1 000)
function price_format($price, $precision = 0, $round = false, $mode = 'up'){
	// $precision - Количество десятичных знаков, до которых производится округление
	// (default) $mode - PHP_ROUND_HALF_UP
	return number_format($round ? number_round($price, $precision, $mode) : $price, 0, '', ' ');
}

// округление числа (при пересчеты цены)
function number_round($price, $precision = 0, $mode = 'up'){
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
function mbCutString($string, $length, $postfix = '...', $encoding = 'UTF-8') {
	if (mb_strlen($string, $encoding) <= $length) {
		return $string;
	}
	$temp = mb_substr($string, 0, $length, $encoding); // до какого символа обрезать
	$spacePosition  = mb_strripos($temp, " ", 0, $encoding); // ищем положение пробела
	$result = mb_substr($temp, 0, $spacePosition, $encoding); // обрезка до целого слова

	return $result . $postfix;
}

// метод для преобразования массива в объект (stdClass Object)
function dataDecode($data, $output = null){
	$data_type = gettype($data); // получаем тип переданных данных
	if($data_type == $output) return $data; // если тип переданных данных = типу выходных данных, вернем переданные данных
	$isObject = $data_type == 'object'; // получаем boolean, является ли данный тип объектом
	$json = json_encode($data); // кодируем данные в json
	return json_decode($json, $isObject); // декодируем json в объект (true - ассоциативный массив)
}

// возвращает короткое имя класса (app\models\User => User)
function getClassShortName($class){
	return getClassInfo($class)->getShortName();
}

// возвращает короткое имя класса (app\models\User)
function getClassName($class){
	return getClassInfo($class)->getName();
}

// возвращает короткое имя класса (app\models\User)
function getClassInfo($class){
	return new \ReflectionClass($class);
}

// CamelCase - для изменения имен контроллеров (каждое слово в верхнем регистре)
function lowerCamelCase($name){
	// ThisMethodName => this_method_name
	return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $name));
}

function upperCamelCase($name){
	// this_method_name => ThisMethodName
	return preg_replace_callback('/(?:^|_)(.?)/', function($matches){return strtoupper($matches[1]);}, $name);
}

function arrayUnset($array, $items){
	foreach(toArray($items) as $item){
		if(isset($array[$item])){
			unset($array[$item]);
		}
	}
	return $array;
}

// если получен массив, возвращает его
// иначе возвращает полученное значение в виде массива
function toArray($attrs, $attrToArray = false, $data = [], $result = 'attrs'){
	//debug(['Start', $attrs, $attrToArray, $data]);
	$attrs = is_array($attrs) ? $attrs : toArray([$attrs], $attrToArray, $data);
	//debug(['$attrs toArray', $attrs, $attrToArray, $data]);
	$data = is_array($data) ? $data : toArray($attrs, $attrToArray, [$data], 'data');
	//debug(['$data toArray', $attrs, $attrToArray, $data]);
	if($data){
		foreach($data as $item){
			if(!in_array($item, $attrs)) $attrs[] = $item;
		}
	}
	if($attrToArray){
		foreach($attrs as $key => $attr){
			$attrs[$key] = is_array($attr) ? $attr : toArray([$attr]);
		}
	}
	return ${$result};
}

function validateAttrs($class, $attrs){
	//debug(['validateAttrs', $attrs]);
	foreach($attrs as $key => $attr){
		$attrs[$key] = callMethod($class, $attr) ?: getProp($class, $attr);
	}
	//debug(['validateAttrs', $attrs]);
	return $attrs;
}

function callMethod($class, $attr, $attrs = []){
	//debug(['callMethod', $attr, $attrs]);
	return method_exists($class, $attr) && is_callable([$class, $attr]) ? call_user_func_array([$class, $attr], $attrs) : false;
}

function getProp($class, $attr){
	if(property_exists($class, $attr)){
		$isStatic = array_key_exists($attr, getClassInfo($class)->getStaticProperties());
		$attr = is_object($class) && !$isStatic ? $class->$attr : getClassName($class)::${$attr};
	}
	return $attr;
}

// определяет является ли переданная строка регулярным выраженияем
	// This doesn't test validity; but it looks like the question is Is there a good way of test if a string is a regex or normal string in PHP? and it does do that.
function isRegex($str){
	return preg_match("/^\/[\s\S]+\/$/", $str);
}

// создает новый массив на из переданного ассоциативного массива и ключа, по значению которого необходимо сформировать новые ключи
// сортирует массив в по ключам/значениям в зависимости от переданного типа $sort_key ('key'/'value')
// $sort_flag - флаги сортировки
// newArray($info->getProperties(), 'name', ['Value' => [',', '.', 'floatval']])
function newArray($array, $key = '', $patterns = [], $sort_key = 'key', $sort_flag = SORT_NATURAL){
	// формируем массив
	$newArray = []; // новый массив
	foreach ($array as $k => $v){
		// если переданы паттерны для замены значений, производим замену для каждого переданного паттерна
		if($patterns){
			foreach ($patterns as $key_p => $pattern){
				$value = is_array($v) ? $v[$key_p] : $v->$key_p;
				// если передан массив паттернов, работаем с ним
				if(is_array($pattern)){
					// $pattern[0] - паттерн для поиска совпадения
					// $pattern[1] - паттерн для замены совпадения
					// $pattern[2] - если передана не пустая строка, вызывает указанную пользовательскую функцию
					// опеределяем вызываемую функцию на основе типа паттерна (regExp/string)
					$func = isRegex($pattern[0]) ? 'preg_replace' : 'str_replace';
					// call_user_func_array - Вызывает callback-функцию с массивом параметров
					$val = call_user_func_array($func, [$pattern[0], $pattern[1], $value]);
					// call_user_func - Вызывает callback-функцию, заданную в первом параметре
					$val = !isset($pattern[2]) ? $value : call_user_func($pattern[2], $val);
				}
				elseif(is_string($pattern)){
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
	switch($sort_key){
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
