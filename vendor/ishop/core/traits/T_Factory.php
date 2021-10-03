<?php declare(strict_types = 1);

//namespace ishop\traits;

//trait T_Factory<T, O> {
//    public function make(O $obj): T {
//        return new T();
//    }
//}

class Dog {}
class Cat {}

/**
 * @phpstan-template T of \Exception
 *
 * @param \Exception $param
 * @return \Exception
 *
 * @phpstan-param T $param
 * @phpstan-return T
 */
function foo($param) {
	return new Exception;
}

/**
 * @template T
 */
interface Collection {
	/**
	 * @param T $item
	 */
	public function add($item): void;

	/**
	 * @return T
	 */
	public function get(int $index);
}

class Foo {}
class Bar {}

/**
* @template F of Foo
* @template B of Bar
*/
class T_Factory {
	/**
	* @param B $obj
	* @return F
	*/
    public function make($obj) {
        return new Foo;
    }
}

$factory = new T_Factory();
$factory->make(new Bar);
