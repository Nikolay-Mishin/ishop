## [Интерфейс Collection](https://www.php.net/manual/ru/class.ds-collection.php)

(No version information available, might only be in Git)

### Введение

Collection - это базовый интерфейс, который покрывает функциональность общую для всех структур данных в этой библиотеке. Он гарантирует, что все структуры обходимы, счётный и могут быть преобразованы в JSON с помощью функции [json_encode()](https://www.php.net/manual/ru/function.json-encode.php).

### Обзор интерфейсов

```php
class Ds\Collection implements Traversable, Countable, JsonSerializable {
/* Методы */
abstract public clear(): void
abstract public copy(): Ds\Collection
abstract public isEmpty(): bool
abstract public toArray(): array
}
```

### Содержание

* Ds\Collection::clear — Удаляет все значения
* Ds\Collection::copy — Возвращает копию коллекции
* Ds\Collection::isEmpty — Проверяет, пуста ли коллекция
* Ds\Collection::toArray — Преобразует коллекцию в массив (array)

```php
// Collection
$collection_a = new \Ds\Vector([1, 2, 3]);
$collection_b = new \Ds\Vector();

var_dump($collection_a, $collection_b);
/*
	object(Ds\Vector)[1]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3

	object(Ds\Vector)[2]
*/

//json_encode
var_dump( json_encode($collection_a));
/*
string '[1,2,3]
*/
  
//count
var_dump(count($collection_a));
/*
int 3
*/
  
// serialize
var_dump(serialize($collection_a));
/*
string 'C:9:"Ds\Vector":12:{i:1;i:2;i:3;}'
*/

// foreach
foreach ($collection_a as $key => $value) {
	echo $key ,'--', $value, PHP_EOL;
}
/*
	0--1
	1--2
	2--3
	*/

// clone
$clone = clone($collection_a);
var_dump($clone);
/*
	object(Ds\Vector)[1]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3
*/

// push
$clone->push('aa');
var_dump($clone);
/*
object(Ds\Vector)[3]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3
	public 3 => string 'aa' (length=2)
	*/

// isEmpty
var_dump($collection_a->isEmpty(), $collection_b->isEmpty());
/*
boolean false
boolean true
	*/

// toArray
var_dump($collection_a->toArray(), $collection_b->toArray());
/*
	array (size=3)
	0 => int 1
	1 => int 2
	2 => int 3

array (size=0)
	empty
*/

// copy ( void )
//浅拷贝， shallow copy
$collection_c = $collection_a->copy();

var_dump($collection_c);
/*
object(Ds\Vector)[3]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3
*/

$collection_c->push(4);
var_dump($collection_a, $collection_c);
/*
object(Ds\Vector)[1]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3

object(Ds\Vector)[3]
	public 0 => int 1
	public 1 => int 2
	public 2 => int 3
	public 3 => int 4
*/
  
// clear
$collection_a->clear();
$collection_b->clear();
$collection_c->clear();

var_dump($collection_a, $collection_b, $collection_c);
/*
object(Ds\Vector)[1]
object(Ds\Vector)[2]
object(Ds\Vector)[3]
*/
```



## [Реализации наборов](https://ru.stackoverflow.com/questions/615126/%D0%A7%D1%82%D0%BE-%D1%82%D0%B0%D0%BA%D0%BE%D0%B5-%D0%BA%D0%BE%D0%BB%D0%BB%D0%B5%D0%BA%D1%86%D0%B8%D1%8F-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%BE%D0%B2-%D0%B2-php)

Наборы представлены единственной реализацией UniqueStore. Объекты в хранилище UniqueStore. Уникальность объектов обеспечивается за счет метода getIdentity(), который возвращает идентификаторы объектов. В хранилище UniqueStore не могут присутствовать несколько объектов с одинаковыми идентификаторами. Внутренняя структура хранилища уникальных объектов UniqueStore построена на основе ассоциативных связей между объектами и их идентификаторами. Это дает возможность реализовывать все операции хранилища с помощью ассоциативных выборок, что очень сильно повышает скорость его работы. Сложность работы алгоритмов хранилища уникальных объектов равна O(1), что означает, что время установки/получения объектов не изменяется в зависимости от размера хранилища. Хранилище уникальных объектов UniqueStore поддерживает любые типы данных для значений.

###### Примеры использования набора:

```php
namespace Rmk\Collection;

use \UnexpectedValueException as UnexpectedValueException;
use \InvalidArgumentException as InvalidArgumentException;
use \stdClass as stdClass;

include '../../bootstrap.php';

$set = new UniqueStore('stdClass');

$obj1 = new stdClass();
$obj2 = new stdClass();
$obj3 = new stdClass();

// Добавление объектов в хранилище.
$set->add($obj1);
$set->add($obj2);
$set->add($obj3);

// Повторно объекты в хранилище добавлены не будут.
$set->add($obj3);

try {
	$set->add(new UnexpectedValueException);
} catch (InvalidArgumentException $exc) {
	echo 'Значение не подходит по типу.';
}

// Обход хранилища.
$set->each(function($value, $thisSet) {
			/**
			 * @TODO: Обработка хранилища.
			 */
		}
);

// Удаление объектов из хранилища.
$set->remove($obj1);
$set->remove($obj2);
$set->remove($obj3);

// Преобразование в массив.
$array = $set->toArray();
```

```php
Class Ticket {
	protected $ticketType;
	protected $person;

	public function __construct($ticketType,$person) {
		$this->ticketType = $ticketType;
		$this->person     = $person;
	}

}

Class Collection implements Iterator, Countable {

	private $tickets = array();
	private $position;

	public function __construct(){
		$this->position = 0;
	}

	//Добавить элемент в массив
	public function push($ticket) {
		if ($ticket instanceof Ticket === false) {
			throw new InvalidArgumentException('Попытка добавить не верный тип');
		}
		array_push($this->tickets,$ticket);
	}

	//Взять последний добавленный
	public function pop() {
		array_prop($this->tickets,$ticket);
	}

	//Ниже Методы для реализации интерфейса Iterator
	function rewind() {
		$this->position = 0;
	}

	function current() {
		return $this->tickets[$this->position];
	}

	function key() {
		return $this->position;
	}

	function next() {
		++$this->position;
	}

	function valid() {
		return isset($this->tickets[$this->position]);
	}


	function count(){
		return count($this->tickets);
	}
}

$ticketJhon = new Ticket('emptyTicket',"John");
$ticketJane = new Ticket('emptyTicket',"Jane");

$collection = new Collection();

$collection->push($ticketJhon);
$collection->push($ticketJane);

foreach($collection as $ticket) {
	print_r($ticket);
}

$ticketStr = 'Не билет';
$collection->push($ticketStr);
```



## [Коллекции объектов в PHP](https://habr.com/ru/post/144182/)

[Исходные коды](https://github.com/rmk135/Rmk-Framework)

На протяжении последних 5 лет я работаю с PHP. У него есть достаточно разных проблем, но это никогда не мешало создавать отлично работающие продукты.

Не смотря на это, есть ряд вещей, которые выполняются внутри достаточно «криво». Один из вопросов, который постоянно тратил мои нервы, был вопрос работы с множествами объектов с помощью массивом данных.

На мой взгляд, массивы в PHP имеют достаточно широкую функциональность, что, кстати, является одной из проблем при работе с множествами объектов.

Что не нравилось?
* [Исторически сложившиеся имена функций и отсутствие ОО интерфейса](http://php.net/manual/en/ref.array.php).
* Отсутствие возможности построить ассоциативный массив, где ключами будут объекты.
* Самостоятельный контроль типов объектов в массиве.
* Самостоятельный контроль последовательности индексов в списке.


В результате было принято решение о создании пакета, который должен работать с множествами однотипных объектов (коллекциями).

И так, представляю вашему вниманию пакет `Rmk\Collection`.

#### Интерфейсы

Интерфейсы коллекций предназначены для описания функциональности и классификации типов коллекций.

Базовым интерфейсом коллекций является интерфейс Collection.

Интерфейсы Map, Set, List, Queue и Deque наследуют интерфейс Collection и добавляют собственную функциональность.

Интерфейс Iterable предназначен для обхода объектов коллекции. Он поддерживается всеми интерфейсами коллекций.

#### Iterable

Данный интерфейс предоставляет возможность выполнять обход объектов коллекции и применять к каждому объекту пользовательскую функцию.

#### Collection

Данный интерфейс предназначен для описания базовых функций работы с множеством объектов. Он наследует интерфейсы Countable и Iterable, что позволяет получать количество объектов в коллекции и выполнять обход и применение пользовательской функции для каждого объекта коллекции. Интерфейс коллекции подразумевает, что в коллекции находятся объекты одного типа.

#### Map

Данный интерфейс предназначен для описания функциональности карты объектов. Карта объектов предназначена для построения ассоциативных связей между объектами, где один из объектов является ключем, а другой значением. Интерфейс карты объектов подразумевает, что в карте находятся ключи одного типа. Интерфейс карты объектов подразумевает, что в карте находятся значения одного типа. Интерфейс карты объектов наследует интерфейс Collection.

#### Set

Данный интерфейс предназначен для описания функциональности набора объектов, где объекты являются уникальными в рамках коллекции. Уникальность объектов осуществляется с помощью метода getIdentity(). Интерфейс набора объектов наследует интерфейс Collection.

#### Queue

Данный интерфейс предназначен для описания функциональности очереди объектов. Очередь объектов предназначена для описания структуры данных, когда объекты добавляются в конец очереди, а забираются с начала очереди. Интерфейс очереди объектов наследует интерфейс Collection.

#### Deque

Данный интерфейс предназначен для описания функциональности двунаправленной очереди объектов. Интерфейс двунаправленной очереди объектов наследует интерфейс очереди объектов Queue и добавляет дополнительную функциональность. Функциональность двунаправленной очереди объектов подразумевает работу с очередью с обеих сторон.

#### SequentialList

Данный интерфейс предназначен для описания функциональности списка объектов. Списком объектов является последовательность объектов, где объекты хранятся под последовательными целочисленными индексами. Кроме общей функциональности списка объектов, данный интерфейс определяет метод reverseEach() аналогичный методу Iterable::each() за исключением того, что метод reverseEach() обходит список в обратном порядке. Интерфейс списка объектов наследует интерфейс Collection.

#### Реализации карт

Карты представлены реализациями HashMap и HashStore.

Часть функциональности HashMap и HashStore наследуется от абстрактных классов AbstractCollection и AbstractMap.

Внутренняя структура карт HashMap и HashStore построена на основе сети ассоциативных связей. Это дает возможность реализовывать все операции карт с помощью ассоциативных выборок, что очень сильно повышает скорость их работы. Сложность работы алгоритмов карт равна O(1), что означает, что время установки/получения объектов не изменяется в зависимости от размера карт.

Карты HashMap и HashStore поддерживают любые типы данных для ключей и значений. Это является функциональным преимуществом по сравнению с стандартными Php массивами.

Ключи карты HashMap являются уникальными. Значения карты HashMap не являются уникальными, что позволяет ассоциировать одно значение с несколькими ключами.

Ключи и значения карты HashStore являются уникальными, что позволяет организовывать хранилище уникальных ассоциированных объектов.

Карта HashStore работает в среднем на 20% быстрее карты HashMap. Данное преимущество получено за счет уникальности объектов в HashStore, что требует меньшего количества ассоциативных связей.

#### Реализации наборов

Наборы представлены единственной реализацией UniqueStore.

Объекты в хранилище UniqueStore. Уникальность объектов обеспечивается за счет метода getIdentity(), который возвращает идентификаторы объектов. В хранилище UniqueStore не могут присутствовать несколько объектов с одинаковыми идентификаторами.

Внутренняя структура хранилища уникальных объектов UniqueStore построена на основе ассоциативных связей между объектами и их идентификаторами. Это дает возможность реализовывать все операции хранилища с помощью ассоциативных выборок, что очень сильно повышает скорость его работы. Сложность работы алгоритмов хранилища уникальных объектов равна O(1), что означает, что время установки/получения объектов не изменяется в зависимости от размера хранилища.

Хранилище уникальных объектов UniqueStore поддерживает любые типы данных для значений.
Изображение не загружено

#### Реализации списков

Списки представлены реализациями ArrayList и LinkedList.

Списки объектов ArrayList и LinkedList поддерживают последовательный порядок индексов при изменении своей структуры.

Производительность списков объектов ArrayList и LinkedList зависит от количества изменений их структуры. Исходя из этого, самыми «дешевыми» являются операции работы с концом списка (добавление / удаление), а самыми «дорогими» — операции работы с началом списка (добавление / удаление). Сложность работы алгоритмов списка объектов равна O(n * (count — index)), где n — операция; count — размер списка; index — индекс, по которому выполняется операция.

Списки объектов ArrayList и LinkedList поддерживают любые типы данных для значений.

Связанный список объектов LinkedList реализует интерфейс двунаправленной очереди объектов Deque и наследует функциональность от ArrayList.

#### Реализации очередей

Конкретные реализации очередей отсутствуют, так как связанный список LinkedList отлично покрывает их функциональность.

###### Примеры использования карт:

```php
namespace Rmk\Collection;

use \UnexpectedValueException as UnexpectedValueException;
use \InvalidArgumentException as InvalidArgumentException;
use \stdClass as stdClass;

include '../../bootstrap.php';

$map = new HashMap('stdClass', 'string');

$obj1 = new stdClass();
$obj2 = new stdClass();
$obj3 = new stdClass();

// Установка ассоциаций ключ / значение.
$map->set('k1', $obj1);
$map->set('k2', $obj2);
$map->set('k3', $obj3);

try {
	$map->set(27, $obj1);
} catch (InvalidArgumentException $exc) {
	echo 'Ключ не подходит по типу.';
}

try {
	$map->set('k4', new UnexpectedValueException);
} catch (InvalidArgumentException $exc) {
	echo 'Значение не подходит по типу.';
}

// Обход карты.
$map->each(function($value, $key, $thisMap) {
			/**
			 * @TODO: Обработка карты.
			 */
		}
);

// Удаление по значению.
$map->remove($obj1);
$map->remove($obj2);

// Удаление по ключу.
$map->removeKey('k3');

if ($map->isEmpty()) {
	/**
	 * @TODO: Что делать, если карта пуста? 
	 */
}

// Преобразование в массив.
$array = $map->toArray();

// Внимание! Невозможно преобразовать в массив карту, у которой ключами 
// являются объекты.
$objectMap = new HashMap('stdClass', 'stdClass');

try {
	$objectArray = $objectMap->toArray();
} catch (UnexpectedValueException $exc) {
	echo 'Объекты не могут являться ключами массива.';
}
```

###### Примеры использования наборов:

```php
namespace Rmk\Collection;

use \UnexpectedValueException as UnexpectedValueException;
use \InvalidArgumentException as InvalidArgumentException;
use \stdClass as stdClass;

include '../../bootstrap.php';

$set = new UniqueStore('stdClass');

$obj1 = new stdClass();
$obj2 = new stdClass();
$obj3 = new stdClass();

// Добавление объектов в хранилище.
$set->add($obj1);
$set->add($obj2);
$set->add($obj3);

// Повторно объекты в хранилище добавлены не будут.
$set->add($obj3);

try {
	$set->add(new UnexpectedValueException);
} catch (InvalidArgumentException $exc) {
	echo 'Значение не подходит по типу.';
}

// Обход хранилища.
$set->each(function($value, $thisSet) {
			/**
			 * @TODO: Обработка хранилища.
			 */
		}
);

// Удаление объектов из хранилища.
$set->remove($obj1);
$set->remove($obj2);
$set->remove($obj3);

// Преобразование в массив.
$array = $set->toArray();
```

###### Примеры использования списков:

```php
namespace Rmk\Collection;

use \UnexpectedValueException as UnexpectedValueException;
use \InvalidArgumentException as InvalidArgumentException;
use \OutOfRangeException as OutOfRangeException;
use \stdClass as stdClass;

include '../../bootstrap.php';

$list = new LinkedList('stdClass');

$obj1 = new stdClass();
$obj2 = new stdClass();
$obj3 = new stdClass();

// Добавление объектов в список.
$list->add(0, $obj1);
$list->add(1, $obj2);
$list->add(2, $obj3);

try {
	$list->add(4, $obj1);
} catch (OutOfRangeException $exc) {
	echo 'Индекс находится за пределами списка дальше, чем на единицу.';
}

// Обход списка.
$list->each(function($value, $index, $thisList) {
			/**
			 * @TODO: Обработка списка.
			 */
		}
);

// Обход списка в обратном порядке.
$list->reverseEach(function($value, $index, $thisList) {
			/**
			 * @TODO: Обработка списка.
			 */
		}
);

// Удаление из списка.
$list->remove($obj1);
$list->removeIndex(0);
$list->removeFirst();

if ($list->isEmpty()) {
	echo 'Список пуст.';
}
```


##### Преимущества

* Увереность в типе объектов в коллекции.
* ОО интерфейс вместо «функций работы с массивами» (основная причина написания данного пакета).
* Уверенность в последовательности индексов в коллекциях типа SequentialList.

##### Недостатки

* Условно-низкая производительность. То, что можно было выжать из PHP реализации, я старался выжать. Но если бы данный пакет был реализован на С, как PECL модуль, то он бы работал значительно быстрее.



## [Полноценные коллекции в PHP](https://habr.com/ru/post/64840/)

[Скачать исходник](http://wonted.ru/upl/Collection.php.txt)

Не так давно при разработке своего проекта возникла идея реализовать полноценные коллекции для хранения объектов одинакового типа, по удобству напоминающие `List<Type>` в C#.

Идея состоит в том, чтобы коллекции, содержащие объекты различных типов сами по себе различались, а не имели, скажем, один унифицированный тип Collection. Другими словами, коллекция объектов типа *User* это не то же что коллекция объектов Book. Естественно, первой мыслью было создание различных классов для коллекций (*UserCollection*, *BookCollection*, …). Но данных подход не обеспечивает нужной гибкости, плюс ко всему, нужно тратить время на объявление каждого подобного класса.

Немного поразмыслив, реализовал динамическое создание классов коллекций. Выглядит это так: пользователь создаёт коллекцию объектов типа Book, а нужный тип BookCollection создаётся (т.е. объявляется) автоматически.

Что я получил в итоге:

* Полноценный `TypeHinting` создаваемых типов коллекций.
* Строгая типизация коллекций.
* Возможнось обращатся к коллекции как к массиву как в C# (путём реализации интерфейса *ArrayAccess*)
* Полноценная итерация коллекции (возможность использования в любых циклах).

#### Реализация

##### Фабрика коллекций

```php
/**
* Фабрика коллекций
*
* @author [x26]VOLAND
*/
abstract class CollectionFactory {

	/**
	* Создаёт коллекцию заданного типа.
	*
	* @param string $type Тип коллекции
	* @return mixed
	*/
	public static function create($type) {
		$class = $type . 'Collection';
		self::__create_class($class);
		$obj = new $class($type);
		return $obj;
	}

	/**
	* Создаёт класс с именем $class
	*
	* @param string $class Имя класса
	* @return void
	*/
	private static function __create_class($class) {
		if ( ! class_exists($class)) {
			eval('class ' . $class . ' extends Collection { }');
		}
	}

}
```


##### Класс коллекции (описывает поведение)

```php
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
	private $__type;
	/**
	* Хранилище объектов
	* @var array
	*/
	private $__collection = array();

	/**
	* Констурктор.
	* Задаёт тип элементо, которые будут хранитья в данной коллекции.
	*
	* @param string $type Тип элементов
	* @return void
	*/
	public function __construct($type) {
		$this->__type = $type;
	}

	/**
	* Проверяет тип объекта.
	* Препятствует добавлению в коллекцию объектов `чужого` типа.
	*
	* @param object $object Объект для проверки
	* @return void
	* @throws Exception
	*/
	private function __check_type(&$object) {
		if (get_class($object) != $this->__type) {
			throw new Exception('Объект типа `' . get_class($object)
		. '` не может быть добавлен в коллекцию объектов типа `' . $this->__type . '`');
		}
	}

	/**
	* Добавляет в коллекцию объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
	* @return mixed Collection
	*/
	public function add() {
		$args = func_get_args();
		foreach ($args as $object) {
			$this->__check_type($object);
			$this->__collection[] = $object;
		}
		return $this;
	}

	/**
	* Удаляет из коллекции объекты, переданные в аргументах.
	*
	* @param object(s) Объекты
	* @return mixed Collection
	*/
	public function remove() {
		$args = func_get_args();
		foreach ($args as $object) {
			unset($this->__collection[array_search($object, $this->__collection)]);
		}
		return $this;
	}

	/**
	* Очищает коллекцию.
	*
	* @return mixed Collection
	*/
	public function clear() {
		$this->__collection = array();
		return $this;
	}

	/**
	* Выясняет, пуста ли коллекция.
	*
	* @return bool
	*/
	public function isEmpty() {
		return empty($this->__collection);
	}

	/**
	* Реализация интерфейса IteratorAggregate
	*/
	/**
	* Возвращает объект итератора.
	*
	* @return CollectionIterator
	*/
	public function getIterator() {
		return new CollectionIterator($this->__collection);
	}
	
	/**
	* Реализация интерфейса ArrayAccess.
	*/

	/**
	* Sets an element of collection at the offset
	*
	* @param ineter $offset Offset
	* @param mixed $offset Object
	* @return void
	*/
	public function offsetSet($offset, $object) {
		$this->__check_type($object);
		if ($offset === NULL) {
			$offset = max(array_keys($this->__collection)) + 1;
		}
		$this->__collection[$offset] = $object;
	}
	
	/**
	* Выясняет существует ли элемент с данным ключом.
	*
	* @param integer $offset Ключ
	* @return bool
	*/
	public function offsetExists($offset) {
		return isset($this->__collection[$offset]);
	}
	
	/**
	* Удаляет элемент, на который ссылается ключ $offset.
	*
	* @param integer $offset Ключ
	* @return void
	*/
	public function offsetUnset($offset) {
		unset($this->__collection[$offset]);
	}
	
	/**
	* Возвращает элемент по ключу.
	*
	* @param integer $offset Ключ
	* @return mixed
	*/
	public function offsetGet($offset) {
		if (isset($this->__collection[$offset]) === FALSE) {
		return NULL;
		}
		return $this->__collection[$offset];
	}
	
	/**
	* Реализация интерфейса Countable
	*/
	/**
	* Возвращает кол-во элементов в коллекции.
	*
	* @return integer
	*/
	public function count() {
		return sizeof($this->__collection);
	}

}
```


###### Примеры использования


```php
class BookStore {
	function addBooks(BookCollection $books) {
		// реализация
	}

	function addMagazines(MagazineCollection $magazines) {
		// реализация
	}

	// Если тип коллекции не важен, можно указать базовый тип Collection
	function addGoods(Collection $goods) {
		// реализация
	}
}

class Book {
	var $id;

	function __construct($id) {
		$this->id = $id;
	}
}

class Magazine {
	var $id;

	function __construct($id) {
		$this->id = $id;
	}
}

// Создаём коллекцию
$books = CollectionFactory::create('Book');
echo get_class($books); // BookCollection

// Добавим объектов в коллекцию:
$books->add(new Book(1), new Book(2));
$books->add(new Book(3))->add(new Book(2));
$books[] = new Book(5);
echo count($books); // 5

foreach ($books as $book) {
	echo $book->id;
}
// 12345

$books->add(new Magazine(1)); // Ошибка (неверный тип)

$magazines = CollectionFactory::create('Magazine');
$magazines->add(new Magazine(1));

$bookStore = new BookStore();
$bookStore->addBooks($books); // Всё в порядке
$bookStore->addBooks($magazines); // Ошибка (неверный тип)
$bookStore->addMagazines($magazines); // Всё в порядке
$bookStore->addGoods($books); // Всё в порядке
$bookStore->addGoods($magazines); // Всё в порядке
```
