<?php

namespace ishop\libs\collection; // FFAE81FF - FFA6E229

use ishop\base\Factory;

/** // FFE04242
* Фабрика коллекций // FF787878
*
* @author [x26]VOLAND // FFAFC84D
*/
abstract class CollectionFactory extends Factory { // FF4EC9B0

    protected static string $postfix = 'Collection'; // FF66D9EF - FFCB892B?
    protected static string $extends = '\\'.__NAMESPACE__.'\Collection';

    /**
    * Создаёт коллекцию заданного типа.
    *
    * @param string $type Тип коллекции // FFB363D4
    * @return object
    */
    public static function create(string|object $type, object ...$args): object {
        $obj = parent::create($type, ...$args);
        if (is_object($type)) array_unshift($args, $type);
        if ($args) $obj->add(...$args);
        return $obj;
    }

}
