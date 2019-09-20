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