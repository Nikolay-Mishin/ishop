<?php

namespace ishop\libs\collection;

/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
abstract class CollectionFactory {

	/**
	* Создаёт коллекцию заданного типа.
	*
	* @param string $type Тип коллекции
	* @return object
	*/
	public static function create(string $type): object {
		$class = $type . 'Collection';
		self::create_class($class);
		$obj = new $class($type);
		return $obj;
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function create_class(string $class): void {
		if (!class_exists($class)) {
			eval('class ' . $class . ' extends Collection { }');
		}
	}

}
