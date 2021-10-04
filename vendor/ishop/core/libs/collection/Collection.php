<?php

namespace ishop\libs\collection;

use \IteratorAggregate;
use \Countable;
use \ArrayAccess;
use \Iterator;

use \Exception;
use \Traversable;
use \ArrayIterator;

/**
* Класс коллекции
* Базовый универсальный тип, на основе которого будут создаваться коллекции.
*
* @author [x26]VOLAND
*/
abstract class Collection implements IteratorAggregate, Countable, ArrayAccess, Iterator {

	/**
	* Тип элементов, хранящихся в данной коллекции.
	* @var string
	*/
	private string $type;

	/**
	* Хранилище объектов
	* @var array
	*/
	private array $collection = [];

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

	public function __call(string $method, array $args): mixed {
		if (isCallable($obj = $this->getIterator(), $method)) return $obj->$method(...$args);
		list($class, $caller) = [get_class($this), get_called_class()];
		throw new Exception("Метод `$class::$method()` отсутствует или не может быть вызван в области видимости `$caller`");
	}

	/**
	* Добавляет в коллекцию объекты, переданные в аргументах.
	*
	* @param object $args Объекты
	* @return self Collection
	*/
	public function add(object ...$args): self {
		foreach ($args as $obj) {
			$this->checkType($obj);
			$this->collection[] = $obj;
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
		foreach ($args as $obj) {
			unset($this->collection[array_search($obj, $this->collection)]);
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
	* @param object $obj Объект для проверки
	* @return void
	* @throws Exception
	*/
	private function checkType(object $obj): void {
		if (get_class($obj) != $this->type) {
			$class = get_class($obj);
			throw new Exception("Объект типа `$class` не может быть добавлен в коллекцию объектов типа `$this->type`");
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

	/**
	* Реализация интерфейса Countable
	*/

	/**
	* Возвращает кол-во элементов в коллекции.
	*
	* @return int
	*/
	public function count(): int {
		return sizeof($this->collection);
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
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param mixed $offset Ключ
	* @return void
	*/
	public function offsetUnset(mixed $offset): void {
		unset($this->collection[$offset]);
	}

	/**
	* Реализация интерфейса Iterator.
	*/

	/**
	* Возврат текущего элемента.
	*
	* @return mixed
	*/
	public function current(): mixed {
        return current($this->collection);
		//return $this->collection[$this->position];
    }

	/**
	* Переход к следующему элементу.
	*
	* @return mixed
	*/
	public function next(): mixed {
        return next($this->collection);
		//return ++$this->position;
    }

	/**
	* Возврат ключа текущего элемента.
	*
	* @return int|string|null
	*/
    public function key(): int|string|null {
        return key($this->collection);
		//return $this->position;
    }

	/**
	* Проверяет корректность текущей позиции.
	*
	* @return bool
	*/
    public function valid(): bool {
		return is_null($this->key());
		//return isset($this->collection[$this->position]);
    }

	/**
	* Перемотать итератор на первый элемент.
	*
	* @return mixed
	*/
    public function rewind(): mixed {
		return reset($this->myArray);
		//return $this->position = 0;
    }

}
