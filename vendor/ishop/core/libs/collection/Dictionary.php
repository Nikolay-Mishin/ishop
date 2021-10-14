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
//abstract
class Dictionary extends Collection implements Countable, ArrayAccess, IteratorAggregate /*, Iterator*/ {

	/**
	* Тип ключей, хранящихся в данной коллекции.
	* @var string
	*/
	private string $keyType;

	/**
	* Хранилище ключей
	* @var array
	*/
	private array $keys = [];

	///**
	//* Позиция текущей итерации коллекции.
	//* @var int
	//*/
	//private int $position = 0;

	/**
	* Констурктор.
	* Задаёт тип элементо, которые будут хранитья в данной коллекции.
	*
	* @param array $type Тип элементов
    * @param mixed $args Объекты
	* @return void
	*/
	public function __construct(array $type, mixed ...$args) {
		$this->keyType = key($type);
        $this->type = current($type);
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
	* @param array $args Объекты
	* @return self Dictionary
	*/
	public function add(mixed ...$args): self {
		foreach ($args as $value) {
			$key = key($value);
			$value = current($value);
			debug([$key => $value]);
			$this->verifyKey($key);
			$this->verify($value);
			$this->keys[] = $key;
			$this->collection[] = $value;
		}
		return $this;
	}

	/**
	* Очищает коллекцию.
	*
	* @return self Dictionary
	*/
	public function clear(): self {
		$this->keys = [];
		parent::clear();
		return $this;
	}

	/**
	* Проверяет тип ключа.
	* Препятствует добавлению в коллекцию объектов `чужого` типа.
	*
	* @param int|string|null $key Объект для проверки
	* @return void
	* @throws InvalidArgumentException
	*/
	private function verifyKey(int|string|null $key): void {
		$this->verify($key, $this->keyType, 'Key for dictionary');
    }

	/**
	* Ищет указанный ключ.
	*
	* @param int|string|null $key Ключ
	* @return bool|int|string
	*/
	private function searchKey(int|string|null $key, bool $strict = true): bool|int|string {
		$this->verifyKey($key);
		return array_search($key, $this->keys, $strict);
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
		return $this->searchKey($offset) !== false;
	}

	/**
	* Возвращает элемент по ключу.
	*
	* @param mixed $offset Ключ
	* @return mixed
	*/
	public function offsetGet(mixed $offset): mixed {
        return $this->offsetExists($offset) ? $this->collection[$this->searchKey($offset)] : null;
	}

	/**
	* Устанавливает элемент коллекции по ключу $offset.
	*
	* @param mixed $offset Offset
	* @param mixed $value Object
	* @return void
	*/
	public function offsetSet(mixed $offset, mixed $value): void {
		debug(['Dictionary->offsetSet:this' => $this]);
		$max = max(array_keys($this->keys));
		$isStr = $this->keyType === 'string';
		if ($exist = $this->offsetExists($offset ??= !$isStr ? ++$max : (string) ++$max)) {
			$key = $this->searchKey($offset);
			$offset = !$isStr ? $key : (string) $key;
		}
		parent::offsetSet($offset, $value);
		if (!$exist) $this->keys[] = $offset;
	}
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param mixed $offset Ключ
	* @return void
	*/
	public function offsetUnset(mixed $offset): void {
        if ($this->offsetExists($offset)) {
            $key = $this->searchKey($offset);
            unset($this->keys[$key], $this->collection[$key]);
        }
	}

}
