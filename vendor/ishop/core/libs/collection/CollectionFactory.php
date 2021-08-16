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
	public static function create(string $type, string $namespace = ''): object {
		$class = "${type}Collection";
		$obj = self::create_class($class, $namespace);
		$class = "$namespace\\$class";
		$obj = new $class($type, $namespace);
		preg_match('/^(\w+)(\W.+)$/', "$namespace\\$type", $match);
		debug($match);
		return $obj;
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function create_class(string $class, string $namespace = ''): void {
		$curr_namespace = __NAMESPACE__;
		debug($curr_namespace);
		debug("namespace $namespace; class $class extends \\$curr_namespace\Collection {}");
		if (!class_exists($class)) {
			eval("namespace $namespace; class $class extends \\$curr_namespace\Collection {}");
		}
		debug(getClassName("$namespace\\$class"));
	}

}
