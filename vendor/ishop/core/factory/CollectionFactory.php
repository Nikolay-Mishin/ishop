<?php

namespace ishop\factory;

use ishop\base\Factory;

/**
* Фабрика коллекций
*/
class CollectionFactory extends Factory {

	protected static string $postfix = 'Collection';
	protected static string $extends = '\\'.__NAMESPACE__.'\Collection';

}
