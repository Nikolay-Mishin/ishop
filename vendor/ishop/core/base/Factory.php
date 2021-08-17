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
	public static function create(string|object $type, object ...$args): object {
		$class = self::createClass($type);
		if (is_object($type)) array_unshift($args, $type);
		return new $class($class);
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function createClass(string|object $class): void {
		extract(Parser::_namespace($class.self::$postfix));
		if (!class_exists($class)) {
			$namespace = $namespace ? "namespace $namespace; " : $namespace;
			$extends = self::$extends ? "extends ".self::$extends : self::$extends;
			$implements = self::$implements ? "implements ".self::$implements : self::$implements;
			debug("$namespace class $class $extends $implements {".self::$context."}");
			eval("$namespace class $class $extends $implements {".self::$context."}");
		}
		debug(getClassName("$namespace\\$class"));
	    return "$namespace\\$class".self::$postfix;
	}

}
