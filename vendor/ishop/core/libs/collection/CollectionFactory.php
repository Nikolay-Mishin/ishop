<?php

namespace ishop\libs\collection;

use Collection;

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
		$class = "${type}Collection";
		self::createClass($class);
		$obj = new $class($type);
		return $obj;
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function createClass(string $class): void {
		$curr_namespace = __NAMESPACE__;
        extract(self::parseNamespace($class));
        //debug($curr_namespace);
        //debug("namespace $namespace; class $class extends \\$curr_namespace\Collection {}");
		if (!class_exists($class)) {
			eval("namespace $namespace; class $class extends \\$curr_namespace\Collection {}");
		}
        //debug(getClassName("$namespace\\$class"));
        debug($filter = self::getDeclaredClass('Collection'));
	}

	private static function parseNamespace(string $namespace): array {
        preg_match('/(.*)(\W)(\w+)$/', $namespace, $match);
        $class = $match[3] ?? $namespace;
        $namespace = $match[1] ?? '';
		return compact('class', 'namespace');
	}

    private static function getDeclaredClass(string $class): string {
        return current(array_filter(get_declared_classes(), function($value) use($class) {
            //debug(__CLASS__);
            return call_user_func([__CLASS__, 'parseNamespace'], $value)['class'] == $class;
        }));
	}

}
