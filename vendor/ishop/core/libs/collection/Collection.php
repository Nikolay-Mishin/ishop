<?php

namespace ishop\libs\collection;

use \IteratorAggregate;
use \Countable;
use \ArrayAccess;

use \Exception;
use \Traversable;
use \ArrayIterator;

/**
* Класс коллекции
* Базовый универсальный тип, на основе которого будут создаваться коллекции.
*
* @author [x26]VOLAND
*/
abstract class Collection implements IteratorAggregate, Countable, ArrayAccess  {

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

	/**
	* Констурктор.
	* Задаёт тип элементо, которые будут хранитья в данной коллекции.
	*
	* @param string $type Тип элементов
	* @param string $namespace Пространство имен
	* @return void
	*/
	public function __construct(string $type) {
		$this->type = $type;
	}

	public function __call(string $method, array $args): mixed {
		//$iterator = $this->getIterator();
		if (!method_exists($this, $method) && isCallable($iterator = $this->getIterator(), $method)) return $iterator->$method(...$args);
		list($class, $caller) = [get_class($this), get_called_class()];
		throw new Exception("Метод `$class::$method()` отсутствует или не может быть вызван в области видимости `$caller`");
	}

	/**
	* Добавляет в коллекцию объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
	* @return self Collection
	*/
	public function add(object ...$args): self {
		foreach ($args as $obj) {
			$this->check_type($obj);
			$this->collection[] = $obj;
		}
		return $this;
	}

	/**
	* Удаляет из коллекции объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
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
	private function check_type(object &$obj): void {
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
	* @return integer
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
		if (isset($this->collection[$offset]) === false) {
			return null;
		}
		return $this->collection[$offset];
	}

	/**
	* Sets an element of collection at the offset
	*
	* @param mixed $offset Offset
	* @param mixed $obj Object
	* @return void
	*/
	public function offsetSet(mixed $offset, mixed $obj): void {
		$this->check_type($obj);
		if ($offset === NULL) {
			$offset = max(array_keys($this->collection)) + 1;
		}
		$this->collection[$offset] = $obj;
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

}
