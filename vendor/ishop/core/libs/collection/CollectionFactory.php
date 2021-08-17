<?php

namespace ishop\libs\collection;

use ishop\base\Factory;

/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
abstract class CollectionFactory extends Factory {

	/**
	* Создаёт коллекцию заданного типа.
	*
	* @param string $type Тип коллекции
	* @return object
	*/
	public static function create(string|object $type, object ...$args): object {
		patent::create($type, ...$args);
                $curr_namespace = __NAMESPACE__;
		if ($args) $obj->add(...$args);
		return $obj;
	}

}
