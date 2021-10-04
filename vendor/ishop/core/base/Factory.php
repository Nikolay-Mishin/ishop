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

	protected string $_postfix = '';
	protected string $_extends = '';
	protected string $_implements = '';
	protected string $_context = '';
    
	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
	* @param mixed $args Объекты
	* @return object
	*/
	public static function create(string|object $type, mixed ...$args): object {
	    if (self::checkType($type)) array_unshift($args, $type);
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
	    extract(arrayMerge(get_class_vars(__CLASS__), get_class_vars(get_called_class())));
	    extract(Parser::_namespace($class, $postfix));
	    if (!class_exists($class)) {
	        $_namespace = $namespace ? "namespace $namespace;" : $namespace;
	        $extends = $extends ? "extends ".$extends : $extends;
	        $implements = $implements ? "implements ".$implements : $implements;
	        eval("$_namespace class $className $extends $implements { $context }");
	    }
	    return compact('type', 'class');
	}

	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
	* @param mixed $args Объекты
	* @return object
	*/
	public function _create(string|object $type, mixed ...$args): object {
	    if (self::checkType($type)) array_unshift($args, $type);
	    return $this->_getInstance($type, ...$args);
	}

	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
	* @param mixed $args Объекты
	* @return object
	*/
	protected function _getInstance(string|object $type, mixed ...$args): object {
	    extract($this->_createClass($type));
	    return new $class($type, ...$args);
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	protected function _createClass(string|object $class): array {
	    extract(Parser::_namespace($class, $this->_postfix));
		if (!class_exists($class)) {
		    $_namespace = $namespace ? "namespace $namespace;" : $namespace;
		    $extends = $this->_extends ? "extends ".$this->_extends : $this->_extends;
		    $implements = $this->_implements ? "implements ".$this->_implements : $this->_implements;
		    eval("$_namespace class $className $extends $implements { $this->_context }");
		}
	    return compact('type', 'class');
	}

}
