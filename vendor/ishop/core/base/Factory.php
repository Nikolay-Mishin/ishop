<?php

namespace ishop\base;

use ishop\libs\Parser;

/**
* Базовая Фабрика, которую будут наследовать все Фабрики
*
* @author [x26]VOLAND
*/
abstract class Factory {

	private static string $postfix = '';
	private static string $extends = '';
	private static string $implements = '';
	private static string $context = '';

	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
	* @return object
	*/
	public static function create(string|object $type): object {
		extract(self::createClass($type));
		return new $class($type);
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function createClass(string|object $class): array {
		extract(arrayMerge(get_class_vars(__CLASS__), get_class_vars(get_called_class())));
		$type = $class;
		$class .= $postfix;
		$return = compact('class', 'type');
		extract(Parser::_namespace($class));
		if (!class_exists($class)) {
			$_namespace = $namespace ? "namespace $namespace;" : $namespace;
			$extends = $extends ? "extends ".$extends : $extends;
			$implements = $implements ? "implements ".$implements : $implements;
			eval("$_namespace class $class $extends $implements { $context }");
		}
		return $return;
	}

}
