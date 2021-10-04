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

}
