<?php

namespace ishop\libs\collection;

use \IteratorAggregate;
use \ArrayAccess;
use \Countable;
use \Exception;
use \CollectionIterator;

/**
* Класс коллекции
* Базовый универсальный тип, на основе которого будут создаваться коллекции.
*
* @author [x26]VOLAND
*/
abstract class Collection implements IteratorAggregate, ArrayAccess, Countable {

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
	* @return void
	*/
	public function __construct(string $type) {
		$this->type = $type;
	}

	/**
	* Проверяет тип объекта.
	* Препятствует добавлению в коллекцию объектов `чужого` типа.
	*
	* @param object $object Объект для проверки
	* @return void
	* @throws Exception
	*/
	private function check_type(object &$object): void {
		if (get_class($object) != $this->type) {
			throw new Exception('Объект типа `' . get_class($object) . '` не может быть добавлен в коллекцию объектов типа `' . $this->type . '`');
		}
	}

	/**
	* Добавляет в коллекцию объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
	* @return self Collection
	*/
	public function add(): self {
		$args = func_get_args();
		foreach ($args as $object) {
			$this->check_type($object);
			$this->collection[] = $object;
		}
		return $this;
	}

	/**
	* Удаляет из коллекции объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
	* @return self Collection
	*/
	public function remove(): self {
		$args = func_get_args();
		foreach ($args as $object) {
			unset($this->collection[array_search($object, $this->collection)]);
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
	* Реализация интерфейса IteratorAggregate
	*/
	/**
	* Возвращает объект итератора.
	*
	* @return CollectionIterator
	*/
	public function getIterator(): CollectionIterator {
		return new CollectionIterator($this->collection);
	}
	
	/**
	* Реализация интерфейса ArrayAccess.
	*/

	/**
	* Sets an element of collection at the offset
	*
	* @param integer $offset Offset
	* @param object $object Object
	* @return void
	*/
	public function offsetSet(int $offset, object $object): void {
		$this->check_type($object);
		if ($offset === NULL) {
			$offset = max(array_keys($this->collection)) + 1;
		}
		$this->collection[$offset] = $object;
	}
	
	/**
	* Выясняет существует ли элемент с данным ключом.
	*
	* @param integer $offset Ключ
	* @return bool
	*/
	public function offsetExists(int $offset): bool {
		return isset($this->collection[$offset]);
	}
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param integer $offset Ключ
	* @return void
	*/
	public function offsetUnset(int $offset): void {
		unset($this->collection[$offset]);
	}
	
	/**
	* Возвращает элемент по ключу.
	*
	* @param integer $offset Ключ
	* @return mixed
	*/
	public function offsetGet(int $offset): mixed {
		if (isset($this->collection[$offset]) === false) {
		    return null;
		}
		return $this->collection[$offset];
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

}
