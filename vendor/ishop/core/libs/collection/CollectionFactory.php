<?php

namespace ishop\libs\collection;

use ishop\base\Factory;

/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
class CollectionFactory extends Factory {

	use \ishop\traits\T_Factory;

	protected static string $postfix = 'Collection';
	protected static string $extends = '\\'.__NAMESPACE__.'\Collection';

	protected string $_postfix = 'Collection';
	protected string $_extends = '\\'.__NAMESPACE__.'\Collection';

}
