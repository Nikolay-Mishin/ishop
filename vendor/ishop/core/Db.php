<?php
// класс БД - подключение к БД
// реализует паттерн Singletone

namespace ishop;

use ishop\traits\TSingletone;

class Db {

	use \ishop\traits\TSingletone;

	protected function __construct(){
		$db = require_once CONF . '/config_db.php'; // подключаем файл конфигурации БД
		// RedBeanPHP подключается автозагрузчиком композера (composer)
		class_alias('\RedBeanPHP\R','\R'); // меняем пространство имен \RedBeanPHP\R на \R
		// class_alias('\RedBeanPHP\R','R');
		\R::setup($db['dsn'], $db['user'], $db['pass']); // подключаемся к БД через RedBeanPHP
		// если соединение не установлено выбрасываем исключение
		if( !\R::testConnection() ){
			throw new \Exception("Нет соединения с БД", 500);
		}
		// RedBeanPHP позволяет работать с таблицами на ходу (в автоматическом/'жидком' режиме)
		// например, создать таблицу, если такой нет, создание несуществуещего поля при записн данных
		// https://redbeanphp.com/manual3_0/index.php?p=/manual3_0/freeze
		\R::freeze(true); // включаем режим заморозки (запрет на изменение таблиц и полей в автоматическом режиме)
		// если режим отладки включен, включаем этот режим и у RedBeanPHP
		if(DEBUG){
			\R::debug(true, 1);
		}

		// устанавливаем пользовательский метод для вызова метода 'dispense' с учетом '_' в имени таблицы
		// '_' в имени таблицы запрещено для RedBeanPHP => attributeValue вместо attribute_value)
		// '_' - служебный символ для работы со связями в таблицах
		\R::ext('xdispense', function($type){
			return \R::getRedBean()->dispense( $type );
		});
	}

}
