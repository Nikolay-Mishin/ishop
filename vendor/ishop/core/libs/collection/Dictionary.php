<?php

namespace ishop\libs\collection;

use \Countable;
use \ArrayAccess;
use \IteratorAggregate;
use \Iterator;

use \Exception;
use \InvalidArgumentException;
use \Traversable;
use \ArrayIterator;

/**
* Класс коллекции
* Базовый универсальный тип, на основе которого будут создаваться коллекции.
*
* @author [x26]VOLAND
*/
abstract class Dictionary extends Collection implements Countable, ArrayAccess, IteratorAggregate /*, Iterator*/ {

	protected $keyType;
    protected $valType;

	private $keys = [];
    private $values = [];

	///**
	//* Позиция текущей итерации коллекции.
	//* @var int
	//*/
	//private int $position = 0;

	/**
	* Констурктор.
	* Задаёт тип элементо, которые будут хранитья в данной коллекции.
	*
	* @param string $type Тип элементов
    * @param mixed $args Объекты
	* @return void
	*/
	public function __construct(string $type, mixed ...$args) {
		$this->type = $type;
        $this->add(...$args);
	}

	/**
	* @throws Exception
	*/
	public function __call(string $method, array $args): mixed {
		if (isCallable($iterator = $this->getIterator(), $method)) return $iterator->$method(...$args);
		list($class, $caller) = [get_class($this), static::class];
		throw new Exception("Метод `$class::$method()` отсутствует или не может быть вызван в области видимости `$caller`");
	}

	/**
	* Добавляет в коллекцию объекты, переданные в аргументах.
	*
	* @param object $args Объекты
	* @return self Collection
	*/
	public function add(object ...$args): self {
		foreach ($args as $value) {
			$this->checkType($value);
			$this->collection[] = $value;
		}
		return $this;
	}

	/**
	* Удаляет из коллекции объекты, переданные в аргументах.
	*
	* @param object $args Объекты
	* @return self Collection
	*/
	public function remove(object ...$args): self {
		foreach ($args as $value) {
			unset($this->collection[array_search($value, $this->collection)]);
		}
		return $this;
	}

	/**
	* Очищает коллекцию.
	*
	* @return self Collection
	*/
	public function clear(): self {
		$this->collection = [];
		return $this;
	}

	/**
	* Выясняет, пуста ли коллекция.
	*
	* @return bool
	*/
	public function isEmpty(): bool {
		return empty($this->collection);
	}

	private function verifyKey($key) {
        if(!($key instanceof $this->keyType)) {
            throw new InvalidArgumentException(
				sprintf('Key for dictionary must be of type %s: %s given', $this->keyType, get_class($key)));
        }
    }

    private function verifyValue($value) {
        if(!($value instanceof $this->type)) {
            throw new InvalidArgumentException(
				sprintf('Value for dictionary must be of type %s: %s given', $this->type, get_class($value)));
        }
    }

	/**
	* Реализация интерфейса Countable
	*/

	/**
	* Возвращает кол-во элементов в коллекции.
	*
	* @return int
	*/
	public function count(): int {
		return count($this->keys);
		//return count($this->collection);
	}
	
	/**
	* Реализация интерфейса ArrayAccess.
	*/

	/**
	* Выясняет существует ли элемент с данным ключом.
	*
	* @param mixed $offset Ключ
	* @return bool
	*/
	public function offsetExists(mixed $offset): bool {
		return isset($this->collection[$offset]);
	}

	public function _offsetExists($key) {
        $this->verifyKey($key);
        return array_search($key, $this->keys, true) !== false;
    }

	/**
	* Возвращает элемент по ключу.
	*
	* @param mixed $offset Ключ
	* @return mixed
	*/
	public function offsetGet(mixed $offset): mixed {
		return $this->offsetExists($offset) ? $this->collection[$offset] : null;
	}

	public function _offsetGet($key) {
        $this->verifyKey($key);
        return $this->values[array_search($key, $this->keys, true)];
    }

	/**
	* Sets an element of collection at the offset
	*
	* @param mixed $offset Offset
	* @param mixed $value Object
	* @return void
	*/
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->checkType($value);
		if (is_null($offset)) {
			$offset = max(array_keys($this->collection)) + 1;
		}
		$this->collection[$offset] = $value;
	}

	public function _offsetSet($key, $value) {
        $this->verifyKey($key);
        $this->verifyValue($value);
        if($this->offsetExists($key)) {
            $this->values[array_search($key, $this->keys, true)] = $value;
        }
        $this->keys[] = $key;
        $this->values[] = $value;
    }
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param mixed $offset Ключ
	* @return void
	*/
	public function offsetUnset(mixed $offset): void {
		unset($this->collection[$offset]);
	}

	public function _offsetUnset($key) {
        $this->verifyKey($key);
        if($this->offsetExists($key)) {
            $valueKey = array_search($key, $this->keys, true);
            unset($this->keys[$valueKey], $this->values[$valueKey]);
        }
    }

	/**
	* Реализация интерфейса IteratorAggregate
	*/

	/**
	* Возвращает объект итератора.
	*
	* @return Traversable
	*/
	public function getIterator(): Traversable {
		return new ArrayIterator($this->collection);
	}

	
	///**
	//* Реализация интерфейса Iterator.
	//*/

	///**
	//* Возврат текущего элемента.
	//*
	//* @return mixed
	//*/
	//public function current(): mixed {
	//    return current($this->collection);
	//    //return $this->collection[$this->position];
	//}

	///**
	//* Переход к следующему элементу.
	//*
	//* @return mixed
	//*/
	//public function next(): mixed {
	//    return next($this->collection);
	//    //return ++$this->position;
	//}

	///**
	//* Возврат ключа текущего элемента.
	//*
	//* @return int|string|null
	//*/
	//public function key(): int|string|null {
	//    return key($this->collection);
	//    //return $this->position;
	//}

	///**
	//* Проверяет корректность текущей позиции.
	//*
	//* @return bool
	//*/
	//public function valid(): bool {
	//    return is_null($this->key());
	//    //return isset($this->collection[$this->position]);
	//}

	///**
	//* Перемотать итератор на первый элемент.
	//*
	//* @return mixed
	//*/
	//public function rewind(): mixed {
	//    return reset($this->collection);
	//    //return $this->position = 0;
	//}

}
