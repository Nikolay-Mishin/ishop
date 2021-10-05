

## [Интерфейс ArrayAccess](https://www.php.net/manual/ru/class.arrayaccess)
(PHP 5, PHP 7, PHP 8)

### Введение

Интерфейс обеспечивает доступ к объектам в виде массивов.

### Обзор интерфейсов

```php
interface ArrayAccess {
	/* Методы */
	public offsetExists(mixed $offset): bool
	public offsetGet(mixed $offset): mixed
	public offsetSet(mixed $offset, mixed $value): void
	public offsetUnset(mixed $offset): void
}
```

### Содержание

* [ArrayAccess::offsetExists](https://www.php.net/manual/ru/arrayaccess.offsetexists.php) — Определяет, существует ли заданное смещение (ключ)
* [ArrayAccess::offsetGet](https://www.php.net/manual/ru/arrayaccess.offsetget.php) — Возвращает заданное смещение (ключ)
* [ArrayAccess::offsetSet](https://www.php.net/manual/ru/arrayaccess.offsetset.php) — Присваивает значение заданному смещению
*[ArrayAccess::offsetUnset](https://www.php.net/manual/ru/arrayaccess.offsetunset.php) — Удаляет смещение

###### Пример #1 Основы использования

```php
class Obj implements ArrayAccess {
	private $container = array();

	public function __construct() {
		$this->container = array(
			"one"   => 1,
			"two"   => 2,
			"three" => 3,
		);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}

$obj = new Obj;

var_dump(isset($obj["two"]));
var_dump($obj["two"]);
unset($obj["two"]);
var_dump(isset($obj["two"]));
$obj["two"] = "A value";
var_dump($obj["two"]);
$obj[] = 'Append 1';
$obj[] = 'Append 2';
$obj[] = 'Append 3';
print_r($obj);
```

###### Результатом выполнения данного примера будет что-то подобное:

```php
bool(true)
int(2)
bool(false)
string(7) "A value"
obj Object
(
	[container:obj:private] => Array
		(
			[one] => 1
			[three] => 3
			[two] => A value
			[0] => Append 1
			[1] => Append 2
			[2] => Append 3
		)

)
```

```php
/**
* ArrayAndObjectAccess
* Yes you can access class as array and the same time as object
*
* @author Yousef Ismaeil <cliprz@gmail.com>
*/

class ArrayAndObjectAccess implements ArrayAccess {
	/**
	 * Data
	 *
	 * @var array
	 * @access private
	 */
	private $data = [];

	/**
	 * Get a data by key
	 *
	 * @param string The key data to retrieve
	 * @access public
	 */
	public function &__get ($key) {
		return $this->data[$key];
	}

	/**
	 * Assigns a value to the specified data
	 *
	 * @param string The data key to assign the value to
	 * @param mixed  The value to set
	 * @access public
	 */
	public function __set($key,$value) {
		$this->data[$key] = $value;
	}

	/**
	 * Whether or not an data exists by key
	 *
	 * @param string An data key to check for
	 * @access public
	 * @return boolean
	 * @abstracting ArrayAccess
	 */
	public function __isset ($key) {
		return isset($this->data[$key]);
	}

	/**
	 * Unsets an data by key
	 *
	 * @param string The key to unset
	 * @access public
	 */
	public function __unset($key) {
		unset($this->data[$key]);
	}

	/**
	 * Assigns a value to the specified offset
	 *
	 * @param string The offset to assign the value to
	 * @param mixed  The value to set
	 * @access public
	 * @abstracting ArrayAccess
	 */
	public function offsetSet($offset,$value) {
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}

	/**
	 * Whether or not an offset exists
	 *
	 * @param string An offset to check for
	 * @access public
	 * @return boolean
	 * @abstracting ArrayAccess
	 */
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	/**
	 * Unsets an offset
	 *
	 * @param string The offset to unset
	 * @access public
	 * @abstracting ArrayAccess
	 */
	public function offsetUnset($offset) {
		if ($this->offsetExists($offset)) {
			unset($this->data[$offset]);
		}
	}

	/**
	 * Returns the value at specified offset
	 *
	 * @param string The offset to retrieve
	 * @access public
	 * @return mixed
	 * @abstracting ArrayAccess
	 */
	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->data[$offset] : null;
	}

}

$foo = new ArrayAndObjectAccess();
// Set data as array and object
$foo->fname = 'Yousef';
$foo->lname = 'Ismaeil';
// Call as object
echo 'fname as object '.$foo->fname."\n";
// Call as array
echo 'lname as array '.$foo['lname']."\n";
// Reset as array
$foo['fname'] = 'Cliprz';
echo $foo['fname']."\n";

/** Outputs
fname as object Yousef
lname as array Ismaeil
Cliprz
*/
```



## [Интерфейс Countable]()
(PHP 5 >= 5.1.0, PHP 7, PHP 8)

### Введение

Классы, реализующие интерфейс Countable, могут быть использованы с функцией count().

### Обзор интерфейсов

```php
interface Countable {
	/* Методы */
	public count(): int
}
```

### Содержание

* [Countable::count](https://www.php.net/manual/ru/countable.count.php) — Количество элементов объекта

```php
//Example One, BAD :(

class CountMe {
	protected $_myCount = 3;

	public function count() {
		return $this->_myCount;
	}
}

$countable = new CountMe();
echo count($countable); //result is "1", not as expected

//Example Two, GOOD :)

class CountMe implements Countable {
	protected $_myCount = 3;

	public function count() {
		return $this->_myCount;
	}
}

$countable = new CountMe();
echo count($countable); //result is "3" as expected
```



## [Интерфейс IteratorAggregate](https://www.php.net/manual/ru/class.iteratoraggregate.php)
(PHP 5, PHP 7, PHP 8)

#### Введение

Интерфейс для создания внешнего итератора.

#### Обзор интерфейсов

```php
class IteratorAggregate extends Traversable {
	/* Методы */
	abstract public getIterator(): Traversable
}
```

### [Интерфейс Traversable](https://www.php.net/manual/ru/class.traversable.php)
(PHP 5, PHP 7, PHP 8)

#### Введение

Интерфейс, определяющий, является ли класс обходимым (traversable) с использованием [foreach](https://www.php.net/manual/ru/control-structures.foreach.php).

Абстрактный базовый интерфейс, который не может быть реализован сам по себе. Вместо этого должен реализовываться [IteratorAggregate](https://www.php.net/manual/ru/class.iteratoraggregate.php) или [Iterator](https://www.php.net/manual/ru/class.iterator.php).

`Замечание:`

Внутренние (встроенные) классы, которые реализуют этот интерфейс, могут быть использованы в конструкции foreach и не обязаны реализовывать IteratorAggregate или Iterator.

`Замечание:`

Это внутренний интерфейс, который не может быть реализован в скрипте PHP. Вместо него нужно использовать либо IteratorAggregate, либо Iterator. При реализации интерфейса, наследующего от Traversable, убедитесь, что в секции implements перед его именем стоит IteratorAggregate или Iterator.

#### Обзор интерфейсов

```php
interface Traversable {
}
```



## [Интерфейс Iterator](https://www.php.net/manual/ru/class.iterator.php)
(PHP 5, PHP 7, PHP 8)

#### Введение

Интерфейс для внешних итераторов или объектов, которые могут повторять себя изнутри.

#### Обзор интерфейсов

```php
interface Iterator extends Traversable {
/* Методы */
public current(): mixed
public key(): mixed
public next(): void
public rewind(): void
public valid(): bool
}
```

#### Предопределённые итераторы

PHP уже предоставляет некоторые итераторы для многих ежедневных задач. Смотрите список [итераторов SPL](https://www.php.net/manual/ru/spl.iterators.php) для более детальной информации.

#### Примеры

##### Пример #1 Основы использования

Этот пример демонстрирует в каком порядке вызываются методы, когда используется с итератором оператор [foreach](https://www.php.net/manual/ru/control-structures.foreach.php).

```php
class myIterator implements Iterator {
    private $position = 0;
    private $array = array(
        "firstelement",
        "secondelement",
        "lastelement",
    );

    public function __construct() {
        $this->position = 0;
    }

    public function rewind() {
        var_dump(__METHOD__);
        $this->position = 0;
    }

    public function current() {
        var_dump(__METHOD__);
        return $this->array[$this->position];
    }

    public function key() {
        var_dump(__METHOD__);
        return $this->position;
    }

    public function next() {
        var_dump(__METHOD__);
        ++$this->position;
    }

    public function valid() {
        var_dump(__METHOD__);
        return isset($this->array[$this->position]);
    }
}

$it = new myIterator;

foreach($it as $key => $value) {
    var_dump($key, $value);
    echo "\n";
}
```

###### Результатом выполнения данного примера будет что-то подобное:

```
string(18) "myIterator::rewind"
string(17) "myIterator::valid"
string(19) "myIterator::current"
string(15) "myIterator::key"
int(0)
string(12) "firstelement"

string(16) "myIterator::next"
string(17) "myIterator::valid"
string(19) "myIterator::current"
string(15) "myIterator::key"
int(1)
string(13) "secondelement"

string(16) "myIterator::next"
string(17) "myIterator::valid"
string(19) "myIterator::current"
string(15) "myIterator::key"
int(2)
string(11) "lastelement"

string(16) "myIterator::next"
string(17) "myIterator::valid"
```

#### Смотрите также

Смотрите также раздел [Итераторы объектов](https://www.php.net/manual/ru/language.oop5.iterations.php).

#### Содержание

* [Iterator::current](https://www.php.net/manual/ru/iterator.current.php) — Возврат текущего элемента
* [Iterator::key](https://www.php.net/manual/ru/iterator.key.php) — Возврат ключа текущего элемента
* [Iterator::next](https://www.php.net/manual/ru/iterator.next.php) — Переход к следующему элементу
* [Iterator::rewind](https://www.php.net/manual/ru/iterator.rewind.php) — Перемотать итератор на первый элемент
* [Iterator::valid](https://www.php.net/manual/ru/iterator.valid.php) — Проверяет корректность текущей позиции

```php
$it->rewind();

while ($it->valid())
{
    $key = $it->key();
    $value = $it->current();

    // ...

    $it->next();
}
```

```php
# - Here is an implementation of the Iterator interface for arrays
#     which works with maps (key/value pairs)
#     as well as traditional arrays
#     (contiguous monotonically increasing indexes).
#   Though it pretty much does what an array
#     would normally do within foreach() loops,
#     this class may be useful for using arrays
#     with code that generically/only supports the
#     Iterator interface.
#  Another use of this class is to simply provide
#     object methods with tightly controlling iteration of arrays.

class tIterator_array implements Iterator {
  private $myArray;

  public function __construct( $givenArray ) {
    $this->myArray = $givenArray;
  }
  function rewind() {
    return reset($this->myArray);
  }
  function current() {
    return current($this->myArray);
  }
  function key() {
    return key($this->myArray);
  }
  function next() {
    return next($this->myArray);
  }
  function valid() {
    return key($this->myArray) !== null;
  }
}
```



## [Класс ArrayIterator](https://www.php.net/manual/ru/class.arrayiterator.php)
(PHP 5, PHP 7, PHP 8)

#### Введение

Этот итератор позволяет сбрасывать и модифицировать значения и ключи в процессе итерации по массивам и объектам.

Когда вы хотите перебрать один и тот же массив несколько раз, вам нужно создать экземпляр ArrayObject и создать для него объекты ArrayIterator, ссылающиеся на него либо при помощи [foreach](https://www.php.net/manual/ru/control-structures.foreach.php) или при вызове метода getIterator() вручную.

#### Обзор классов

```php
class ArrayIterator implements ArrayAccess, SeekableIterator, Countable, Serializable {
	/* Константы */
	const int STD_PROP_LIST = 1;
	const int ARRAY_AS_PROPS = 2;

	/* Методы */
	public __construct(array|object $array = [], int $flags = 0)
	public append(mixed $value): void
	public asort(int $flags = SORT_REGULAR): bool
	public count(): int
	public current(): mixed
	public getArrayCopy(): array
	public getFlags(): int
	public key(): mixed
	public ksort(int $flags = SORT_REGULAR): bool
	public natcasesort(): bool
	public natsort(): bool
	public next(): void
	public offsetExists(mixed $key): bool
	public offsetGet(mixed $key): mixed
	public offsetSet(mixed $key, mixed $value): void
	public offsetUnset(mixed $key): void
	public rewind(): void
	public seek(int $offset): void
	public serialize(): string
	public setFlags(int $flags): void
	public uasort(callable $callback): bool
	public uksort(callable $callback): bool
	public unserialize(string $data): void
	public valid(): bool
}
```

#### Предопределённые константы

#### Флаги ArrayIterator

##### ArrayIterator::STD_PROP_LIST

Свойства имеют обычную функциональность при доступе в виде списке (var_dump, foreach и т.д.).

##### ArrayIterator::ARRAY_AS_PROPS

Записи могут быть доступны как свойства (чтение и запись).

#### Содержание

* [ArrayIterator::append](https://www.php.net/manual/ru/arrayiterator.append.php) — Добавить элемент
* [ArrayIterator::asort](https://www.php.net/manual/ru/arrayiterator.asort.php) — Сортирует массив по значениям
* [ArrayIterator::__construct](https://www.php.net/manual/ru/arrayiterator.construct.php) — Создаёт ArrayIterator
* [ArrayIterator::count](https://www.php.net/manual/ru/arrayiterator.count.php) — Посчитать количество элементов
* [ArrayIterator::current](https://www.php.net/manual/ru/arrayiterator.current.php) — Возвращает текущий элемент в массиве
* [ArrayIterator::getArrayCopy](https://www.php.net/manual/ru/arrayiterator.getarraycopy.php) — Возвращает копию массива
* [ArrayIterator::getFlags](https://www.php.net/manual/ru/arrayiterator.getflags.php) — Получает флаги поведения
* [ArrayIterator::key](https://www.php.net/manual/ru/arrayiterator.key.php) — Возвращает ключ текущего элемента массива
* [ArrayIterator::ksort](https://www.php.net/manual/ru/arrayiterator.ksort.php) — Сортирует массив по ключам
* [ArrayIterator::natcasesort](https://www.php.net/manual/ru/arrayiterator.natcasesort.php) — Сортирует массив "натурально", с учётом регистра
* [ArrayIterator::natsort](https://www.php.net/manual/ru/arrayiterator.natsort.php) — Сортирует массив "натурально"
* [ArrayIterator::next](https://www.php.net/manual/ru/arrayiterator.next.php) — Перемещает указатель за следующую запись
* [ArrayIterator::offsetExists](https://www.php.net/manual/ru/arrayiterator.offsetexists.php) — Проверяет существует ли смещение
* [ArrayIterator::offsetGet](https://www.php.net/manual/ru/arrayiterator.offsetget.php) — Получает значение для смещения
* [ArrayIterator::offsetSet](https://www.php.net/manual/ru/arrayiterator.offsetset.php) — Устанавливает значение для смещения
* [ArrayIterator::offsetUnset](https://www.php.net/manual/ru/arrayiterator.offsetunset.php) — Сбрасывает значение по смещению
* [ArrayIterator::rewind](https://www.php.net/manual/ru/arrayiterator.rewind.php) — Перемещает указатель в начало массива
* [ArrayIterator::seek](https://www.php.net/manual/ru/arrayiterator.seek.php) — Перемещает указатель на выбранную позицию
* [ArrayIterator::serialize](https://www.php.net/manual/ru/arrayiterator.serialize.php) — Сериализует массив
* [ArrayIterator::setFlags](https://www.php.net/manual/ru/arrayiterator.setflags.php) — Устанавливает флаги, изменяющие поведение ArrayIterator
* [ArrayIterator::uasort](https://www.php.net/manual/ru/arrayiterator.uasort.php) — Сортировка с помощью заданной пользователем функции и сохранением ключей
* [ArrayIterator::uksort](https://www.php.net/manual/ru/arrayiterator.uksort.php) — Сортировка по ключам с помощью заданной функции сравнения
* [ArrayIterator::unserialize](https://www.php.net/manual/ru/arrayiterator.unserialize.php) — Десериализация
* [ArrayIterator::valid](https://www.php.net/manual/ru/arrayiterator.valid.php) — Проверяет, содержит ли массив ещё записи

```php
$fruits = array(
	"apple" => "yummy",
	"orange" => "ah ya, nice",
	"grape" => "wow, I love it!",
	"plum" => "nah, not me"
);

$obj = new ArrayObject( $fruits );
$it = $obj->getIterator();

// How many items are we iterating over?

echo "Iterating over: " . $obj->count() . " values\n";

// Iterate over the values in the ArrayObject:
while($it->valid()) {
	echo $it->key() . "=" . $it->current() . "\n";
	$it->next();
}

// The good thing here is that it can be iterated with foreach loop

foreach ($it as $key=>$val)
	echo $key.":".$val."\n";

/* Outputs something like */
/*
Iterating over: 4 values
apple=yummy
orange=ah ya, nice
grape=wow, I love it!
plum=nah, not me
*/
```
