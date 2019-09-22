<?php
// функции приложения

// распечатывает массив
function debug($arr){
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

// перенаправляет на указанную страницу
function redirect($http = false){
    // $http - адрес перенаправления
    // если $http передан то $redirect = адресу перенаправления, иначе обновить/перезапросить текущую страницу
    if($http){
        $redirect = $http;
    }else{
        // если в массиве $_SERVER есть страница, с которой пришел пользователь (предыдущая страница), то берем ее, иначе главную
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    }
    header("Location: $redirect"); // перенаправляем на сформированный адрес
    exit; // завершаем скрипт
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