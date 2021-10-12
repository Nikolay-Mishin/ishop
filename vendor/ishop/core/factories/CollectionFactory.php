<?php

namespace ishop\factories;

use ishop\base\Factory;

/**
* Фабрика коллекций
*/
class CollectionFactory extends Factory {

	protected static string $postfix = 'Collection';
	protected static string $extends = '\ishop\libs\collection\Collection';

}
