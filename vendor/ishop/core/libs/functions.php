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

// если получен массив, возвращает его
// иначе возвращает полученное значение в виде массива
function toArray($attrs, $attrToArray = true){
	$attrs = is_array($attrs) ? $attrs : toArray([$attrs]);
	if($attrToArray){
		foreach($attrs as $key => $attr){
			$attrs[$key] = is_array($attr) ? $attr : toArray([$attr], false);
		}
	}
	return $attrs;
}

function validateAttrs($class, $attrs){
	foreach($attrs as $key => $attr){
		$attrs[$key] = method_exists($class, $attr) ? $class->$attr() : (property_exists($class, $attr) ? $class->$attr : $attr);
	}
	return $attrs;
}
