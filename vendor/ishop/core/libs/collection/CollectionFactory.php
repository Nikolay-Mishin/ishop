<?php

namespace ishop\libs\collection;

use ishop\base\Factory;

/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
abstract class CollectionFactory extends Factory {

    private static string $postfix = 'Collection';
    private static string $extends = '\\'.__NAMESPACE__.'\Collection';

    /**
    * Создаёт коллекцию заданного типа.
    *
    * @param string $type Тип коллекции
    * @return object
    */
    public static function create(string|object $type, object ...$args): object {
        parent::create($type, ...$args);
        debug(self::$extends);
        if ($args) $obj->add(...$args);
        return $obj;
    }

}
