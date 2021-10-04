<?php

namespace ishop\base;

use ishop\libs\Parser;

/**
* Базовая Фабрика, которую будут наследовать все Фабрики
*
* @author [x26]VOLAND
*/
abstract class Factory {

	protected static string $postfix = '';
	protected static string $extends = '';
	protected static string $implements = '';
	protected static string $context = '';
    
    /**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
    * @param mixed $args Объекты
	* @return object
	*/
    public static function create(string|object $type, mixed ...$args): object {
        $args = self::checkType($type) ? array_unshift($args, $type) : $args;
		return self::getInstance($type, ...$args);
	}
  
    protected static function checkType(string|object $type): bool {
		return is_object($type) || !is_string($type);
	}

	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
    * @param mixed $args Объекты
	* @return object
	*/
	protected static function getInstance(string|object $type, mixed ...$args): object {
		extract(self::createClass($type));
		return new $class($type, ...$args);
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	protected static function createClass(string|object $class): array {
		extract(Parser::_namespace($class));
		list($type, $class) = [$class, $class.$postfix];
		$return = compact('type', 'class');
		if (!class_exists($class)) {
			$_namespace = $namespace ? "namespace $namespace;" : $namespace;
			$extends = self::$extends ? "extends ".self::$extends : self::$extends;
			$implements = self::$implements ? "implements ".self::$implements : self::$implements;
			eval("$_namespace class $class $extends $implements { self::$context }");
		}
		return $return;
	}

}
