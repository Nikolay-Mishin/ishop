<?php

namespace ishop\libs\collection;

use ishop\base\Factory;

/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
abstract class CollectionFactory extends Factory {

	//use \ishop\traits\T_Factory;

    protected static string $postfix = 'Collection';
    protected static string $extends = '\\'.__NAMESPACE__.'\Collection';

    /**
    * Создаёт коллекцию заданного типа.
    *
    * @param string $type Тип коллекции
	* @param object $args Объекты
    * @return object
    */
    public static function create(string|object $type, object ...$args): object {
        $obj = parent::create($type, ...$args);
        if (is_object($type)) array_unshift($args, $type);
        if ($args) $obj->add(...$args);
        return $obj;
    }

}
