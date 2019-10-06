<?php
// маршруты (пути/адреса) приложения

use ishop\Router; // подключаем маршрутизатор (для обращения к классу по короткому имени - без указания пространства имен)

// более конкретные правила должны находиться выше, чем более общие (от частного к общему)
// ^ - начало строки
// $ - конец строки
// ?P<key> - задает строковое наименование ключа в массиве (preg_match()) вместо нумерованного массива
// /? и (?P<action>[a-z-]+)? - слэш и выражение в скобках являются необязательными тк на конце данных выражения стоит ?

// user routes - пользовательские правила (более конкретные)
// page/about

// маршрут карточки товара
Router::add('^product/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Product', 'action' => 'view']);
Router::add('^category/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Category', 'action' => 'view']);

// default routes / общие правила
// page/view/about

Router::add('^admin$', ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);
// 2 параметр с соответствиями контреллера и экшена не заданы, чтобы сделать правило динамичным (controller/action)
Router::add('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');