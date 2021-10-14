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
abstract class Collection implements Countable, ArrayAccess, IteratorAggregate /*, Iterator*/ {

	/**
	* Тип элементов, хранящихся в данной коллекции.
	* @var string
	*/
	protected string $type;

	/**
	* Хранилище объектов
	* @var array
	*/
	protected array $collection = [];

	///**
	//* Позиция текущей итерации коллекции.
	//* @var int
	//*/
	//protected int $position = 0;

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
			$this->verify($value);
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
			$this->offsetUnset($this->searchValue($value));
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

	/**
	* Проверяет тип объекта.
	* Препятствует добавлению в коллекцию объектов `чужого` типа.
	*
	* @param object|int|string|null $value Объект для проверки
	* @return void
	* @throws InvalidArgumentException
	*/
	protected function verify(object|int|string|null $value, mixed $type = null, ?string $msg = null): void {
		$type ??= $this->type;
		$msg ??= 'Value for collection';
		if (!($isObj = is_object($value) ? $value instanceof $type : gettype($value) === $type)) {
			throw new InvalidArgumentException(
				sprintf("$msg must be of type %s: %s given", $type, call_user_func($isObj ? 'get_class' : 'gettype', $value)));
		}
	}

	/**
	* Ищет указанное значение.
	*
	* @param object $value Ключ
	* @return bool|int|string
	*/
	protected function searchValue(object $value, bool $strict = true): bool|int|string {
		$this->verify($value);
		return array_search($value, $this->collection, $strict);
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
		return count($this->collection);
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

	/**
	* Возвращает элемент по ключу.
	*
	* @param mixed $offset Ключ
	* @return mixed
	*/
	public function offsetGet(mixed $offset): mixed {
		return $this->offsetExists($offset) ? $this->collection[$offset] : null;
	}

	/**
	* Устанавливает элемент коллекции по ключу $offset.
	*
	* @param mixed $offset Offset
	* @param mixed $value Object
	* @return void
	*/
	public function offsetSet(mixed $offset, mixed $value): void {
		debug(['Collection->offsetSet:this' => $this]);
		$this->verify($value);
		if ($this->offsetExists($offset ??= max(array_keys($this->collection)) + 1)) {
            $this->collection[$offset] = $value;
        }
		else {
			$this->collection[] = $value;
		}
	}
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param mixed $offset Ключ
	* @return void
	*/
	public function offsetUnset(mixed $offset): void {
		if ($this->offsetExists($offset)) {
            unset($this->collection[$offset]);
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
