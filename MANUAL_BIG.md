### Класс [ReflectionClass](https://www.php.net/manual/ru/class.reflectionclass.php)

Класс ReflectionClass сообщает информацию о классе.

##### Обзор классов

```php
ReflectionClass implements Reflector {
/* Константы */
const integer IS_IMPLICIT_ABSTRACT = 16 ;
const integer IS_EXPLICIT_ABSTRACT = 32 ;
const integer IS_FINAL = 64 ;
/* Свойства */
public $name ;
/* Методы */
public __construct ( mixed $argument )
public static export ( mixed $argument [, bool $return = FALSE ] ) : string
public getConstant ( string $name ) : mixed
public getConstants ( void ) : array
public getConstructor ( void ) : ReflectionMethod
public getDefaultProperties ( void ) : array
public getDocComment ( void ) : string
public getEndLine ( void ) : int
public getExtension ( void ) : ReflectionExtension
public getExtensionName ( void ) : string
public getFileName ( void ) : string
public getInterfaceNames ( void ) : array
public getInterfaces ( void ) : array
public getMethod ( string $name ) : ReflectionMethod
public getMethods ([ int $filter ] ) : array
public getModifiers ( void ) : int
public getName ( void ) : string
public getNamespaceName ( void ) : string
public getParentClass ( void ) : ReflectionClass
public getProperties ([ int $filter ] ) : array
public getProperty ( string $name ) : ReflectionProperty
public getReflectionConstant ( string $name ) : ReflectionClassConstant
public getReflectionConstants ( void ) : array
public getShortName ( void ) : string
public getStartLine ( void ) : int
public getStaticProperties ( void ) : array
public getStaticPropertyValue ( string $name [, mixed &$def_value ] ) : mixed
public getTraitAliases ( void ) : array
public getTraitNames ( void ) : array
public getTraits ( void ) : array
public hasConstant ( string $name ) : bool
public hasMethod ( string $name ) : bool
public hasProperty ( string $name ) : bool
public implementsInterface ( string $interface ) : bool
public inNamespace ( void ) : bool
public isAbstract ( void ) : bool
public isAnonymous ( void ) : bool
public isCloneable ( void ) : bool
public isFinal ( void ) : bool
public isInstance ( object $object ) : bool
public isInstantiable ( void ) : bool
public isInterface ( void ) : bool
public isInternal ( void ) : bool
public isIterable ( void ) : bool
public isSubclassOf ( mixed $class ) : bool
public isTrait ( void ) : bool
public isUserDefined ( void ) : bool
public newInstance ([ mixed $... ] ) : object
public newInstanceArgs ([ array $args ] ) : object
public newInstanceWithoutConstructor ( void ) : object
public setStaticPropertyValue ( string $name , mixed $value ) : void
public __toString ( void ) : string
}
```

##### Свойства

`name`
Имя класса. Доступно только для чтения и выбрасывает исключение ReflectionException при попытке записи.

##### Модификаторы ReflectionClass

```php
ReflectionClass::IS_IMPLICIT_ABSTRACT
```
Указывает, что класс является абстрактным, потому что он содержит абстрактные методы.

```php
ReflectionClass::IS_EXPLICIT_ABSTRACT
```
Указывает, что класс является абстрактным, потому что так указано при его описании.

```php
ReflectionClass::IS_FINAL
```
Указывает, что класс является окончательным (final)

##### Содержание

```php
ReflectionClass::__construct — Создаёт объект класса ReflectionClass
ReflectionClass::export — Экспортирует класс
ReflectionClass::getConstant — Возвращает определенную константу
ReflectionClass::getConstants — Возвращает константы
ReflectionClass::getConstructor — Возвращает конструктор класса
ReflectionClass::getDefaultProperties — Возвращает свойства по умолчанию
ReflectionClass::getDocComment — Возвращает doc-блоки комментариев
ReflectionClass::getEndLine — Возвращает номер последней строки
ReflectionClass::getExtension — Возвращает объект класса ReflectionExtension для расширения, определяющего класс
ReflectionClass::getExtensionName — Возвращает имя расширения, определяющее класс
ReflectionClass::getFileName — Возвращает имя файла, в котором определен класс
ReflectionClass::getInterfaceNames — Возвращает имена интерфейсов
ReflectionClass::getInterfaces — Возвращает интерфейсы
ReflectionClass::getMethod — Возвращает экземпляр ReflectionMethod для метода класса
ReflectionClass::getMethods — Возвращает список методов в виде массива
ReflectionClass::getModifiers — Возвращает информацию о модификаторах класса
ReflectionClass::getName — Возвращает имя класса
ReflectionClass::getNamespaceName — Возвращает название пространства имён
ReflectionClass::getParentClass — Возвращает родительский класс
ReflectionClass::getProperties — Возвращает свойства
ReflectionClass::getProperty — Возвращает экземпляр ReflectionProperty для свойства класса
ReflectionClass::getReflectionConstant — Получает ReflectionClassConstant для константы класса
ReflectionClass::getReflectionConstants — Получает константы класса
ReflectionClass::getShortName — Возвращает короткое имя
ReflectionClass::getStartLine — Возвращает номер начальной строки
ReflectionClass::getStaticProperties — Возвращает статические свойства
ReflectionClass::getStaticPropertyValue — Возвращает значение статического свойства
ReflectionClass::getTraitAliases — Возвращает массив псевдонимов трейтов
ReflectionClass::getTraitNames — Возвращает массив имён трейтов, используемых в этом классе
ReflectionClass::getTraits — Возвращает массив трейтов, используемых в этом классе
ReflectionClass::hasConstant — Проверяет, определена ли константа
ReflectionClass::hasMethod — Проверяет, задан ли метод
ReflectionClass::hasProperty — Проверяет, определено ли свойство
ReflectionClass::implementsInterface — Проверяет, реализуется ли интерфейс
ReflectionClass::inNamespace — Проверяет, определён ли класс в пространстве имён
ReflectionClass::isAbstract — Проверяет, является ли класс абстрактным
ReflectionClass::isAnonymous — Проверяет, является класс анонимным
ReflectionClass::isCloneable — Проверяет, можно ли клонировать этот класс
ReflectionClass::isFinal — Проверяет, является ли класс окончательным (final)
ReflectionClass::isInstance — Проверяет, принадлежит ли объект классу
ReflectionClass::isInstantiable — Проверяет, можно ли создать экземпляр класса
ReflectionClass::isInterface — Проверяет, является ли класс интерфейсом
ReflectionClass::isInternal — Проверяет, является ли класс встроенным в расширение или в ядро
ReflectionClass::isIterable — Проверить, является ли класс итерируемым
ReflectionClass::isIterateable — Псевдоним ReflectionClass::isIterable
ReflectionClass::isSubclassOf — Проверяет, является ли класс подклассом
ReflectionClass::isTrait — Проверяет, является ли это трейтом
ReflectionClass::isUserDefined — Проверяет, является ли класс пользовательским
ReflectionClass::newInstance — Создаёт экземпляр класса с переданными аргументами
ReflectionClass::newInstanceArgs — Создаёт экземпляр класса с переданными параметрами
ReflectionClass::newInstanceWithoutConstructor — Создаёт новый экземпляр класса без вызова конструктора
ReflectionClass::setStaticPropertyValue — Устанавливает значение статического свойства
ReflectionClass::__toString — Возвращает строковое представление объекта класса ReflectionClass
```

##### To reflect on a namespaced class in PHP 5.3, you must always specify the fully qualified name of the class - even if you've aliased the containing namespace using a "use" statement.

```php
// So instead of:

use App\Core as Core;
$oReflectionClass = new ReflectionClass('Core\Singleton');

// You would type:

use App\Core as Core;
$oReflectionClass = new ReflectionClass('App\Core\Singleton');
```

##### Reflecting an alias will give you a reflection of the resolved class.

```php
class X {
   
}

class_alias('X','Y');
class_alias('Y','Z');
$z = new ReflectionClass('Z');
echo $z->getName(); // X
```

##### Unserialized reflection class cause error.

```php
/**
* abc
*/
class a{}

$ref = new ReflectionClass('a');
$ref = unserialize(serialize($ref));
var_dump($ref);
var_dump($ref->getDocComment());

// object(ReflectionClass)#2 (1) {
//   ["name"]=>
//   string(1) "a"
// }
// PHP Fatal error:  ReflectionClass::getDocComment():
// Internal error: Failed to retrieve the reflection object
```



### [Аргументы функции](https://www.php.net/manual/ru/functions.arguments.php)

Функция может принимать информацию в виде списка аргументов, который является списком разделенных запятыми выражений. Аргументы вычисляются слева направо.

PHP поддерживает передачу аргументов по значению (по умолчанию), [передачу аргументов по ссылке](https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference), и [значения по умолчанию](https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.default). [Списки аргументов переменной длины](https://www.php.net/manual/ru/functions.arguments.php#functions.variable-arg-list) также поддерживаются, смотрите также описания функций [func_num_args()](https://www.php.net/manual/ru/function.func-num-args.php), [func_get_arg()](https://www.php.net/manual/ru/function.func-get-arg.php) и [func_get_args()](https://www.php.net/manual/ru/function.func-get-args.php) для более детальной информации.

##### Пример #1 Передача массива в функцию

```php
function takes_array($input)
{
	echo "$input[0] + $input[1] = ", $input[0]+$input[1];
}
```

### [Передача аргументов по ссылке](https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference)

По умолчанию аргументы в функцию передаются по значению (это означает, что если вы измените значение аргумента внутри функции, то вне ее значение все равно останется прежним). Если вы хотите разрешить функции модифицировать свои аргументы, вы должны передавать их по ссылке.

Если вы хотите, чтобы аргумент всегда передавался по ссылке, вы можете указать амперсанд (&) перед именем аргумента в описании функции:

##### Пример #2 Передача аргументов по ссылке

```php
function add_some_extra(&$string)
{
	$string .= 'и кое-что еще.';
}
$str = 'Это строка, ';
add_some_extra($str);
echo $str;    // выведет 'Это строка, и кое-что еще.'
```

### [Значения аргументов по умолчанию](https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.default)

Функция может определять значения по умолчанию в стиле C++ для скалярных аргументов, например:

##### Пример #3 Использование значений по умолчанию в определении функции

```php
function makecoffee($type = "капучино")
{
	return "Готовим чашку $type.\n";
}
echo makecoffee();
echo makecoffee(null);
echo makecoffee("эспрессо");
```

###### Результат выполнения данного примера:

```
Готовим чашку капучино.
Готовим чашку .
Готовим чашку эспрессо.
PHP также позволяет использовать массивы (array) и специальный тип NULL в качестве значений по умолчанию, например:
```

##### Пример #4 Использование нескалярных типов в качестве значений по умолчанию

```php
function makecoffee($types = array("капучино"), $coffeeMaker = NULL)
{
	$device = is_null($coffeeMaker) ? "вручную" : $coffeeMaker;
	return "Готовлю чашку ".join(", ", $types)." $device.\n";
}
echo makecoffee();
echo makecoffee(array("капучино", "лавацца"), "в чайнике");
```

Значение по умолчанию должно быть константным выражением, а не (к примеру) переменной или вызовом функции/метода класса.

Обратите внимание, что все аргументы, для которых установлены значения по умолчанию, должны находиться правее аргументов, для которых значения по умолчанию не заданы, в противном случае ваш код может работать не так, как вы этого ожидаете. Рассмотрим следующий пример:

##### Пример #5 Некорректное использование значений по умолчанию

```php
function makeyogurt($type = "ацидофил", $flavour)
{
	return "Готовим чашку из бактерий $type со вкусом $flavour.\n";
}
 
echo makeyogurt("малины");   // Не будет работать так, как мы могли бы ожидать
```

###### Результат выполнения данного примера:

```
Warning: Missing argument 2 in call to makeyogurt() in 
/usr/local/etc/httpd/htdocs/phptest/functest.html on line 41
Готовим чашку из бактерий малины со вкусом .
```

###### Теперь сравним его со следующим примером:

##### Пример #6 Корректное использование значений по умолчанию

```php
function makeyogurt($flavour, $type = "ацидофил")
{
	return "Готовим чашку из бактерий $type со вкусом $flavour.\n";
}
 
echo makeyogurt("малины");   // отрабатывает правильно
```

###### Результат выполнения данного примера:

```
Готовим чашку из бактерий ацидофил со вкусом малины.
Замечание: Начиная с PHP 5, значения по умолчанию могут быть переданы по ссылке.
```



### [Получить первое значение массива (PHP)](https://expange.ru/e/%D0%9F%D0%BE%D0%BB%D1%83%D1%87%D0%B8%D1%82%D1%8C_%D0%BF%D0%B5%D1%80%D0%B2%D0%BE%D0%B5_%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8%D0%B5_%D0%BC%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%B0_(PHP)#:~:text=%D0%9F%D0%BE%D0%BB%D1%83%D1%87%D0%B8%D1%82%D1%8C%20%D0%BF%D0%B5%D1%80%D0%B2%D1%8B%D0%B9%20%D1%8D%D0%BB%D0%B5%D0%BC%D0%B5%D0%BD%D1%82%20%D0%BC%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%B0%20%D0%BC%D0%BE%D0%B6%D0%BD%D0%BE,%D1%88%D0%B5%D1%81%D1%82%D1%8C'%20%2F%2F%20%D0%9F%D0%BE%D1%81%D0%BB%D0%B5%D0%B4%D0%BD%D0%B5%D0%B5%20%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8%D0%B5\)%3B%20%3F%3E)

Получить первый элемент массива можно тремя способами: функциями `current()`, функцией `array_shift()` и третий способ при помощи сбрасывания ключей массива и получения нулевого элемента.

#### Пример

Например у нас есть массив, состоящий из цифровых и текстовых ключей:

```php
$array = array(
	0 => 'Номер один',
	1 => 'Номер два',
	2 => 'Номер три',
	3 => 'Номер четыре',
	'KEY_FOUR' => 'Номер пять',
	'KEY_FIVE' => 'Номер шесть' // Последнее значение
);
```

#### Функция current()

В каждом массиве PHP есть внутренний указатель на «текущий» элемент. И если не использовалась функция next(), то ее вызов вернет текущее значение массива, в данном случае первое. Если вызов функции next(), был осуществлен, то сперва нужно воспользоваться функцией reset().

```php
echo current($array); // Номер один

// Вызываем next()
echo next($array); // Номер два

// Функция current() теперь будет возвращать значение "Номер два",
// чтобы сбросить указатель в начало нужно вызвать функцию reset()
reset($array);
echo current($array); // Номер один
```

#### Функция array_shift()

Функция array_shift() извлекает первое значение массива и возвращает его.

```php
echo array_shift($array); // Номер один
```

###### Внимание! Значение возвращаемое функцией array_shift() пропадает из массива.

#### Третий способ

Третий способ не самый лучший, но, если вы уверены что все ключи массива цифровые или вам неважны ключи, то способ тоже подойдет.

```php
// Функция array_values() делает все ключи в виде цифр и упорядочивает их
$array = array_values($array);

// Первый ключ
echo $array[0]; // Номер один
```



### [Манипуляции с типами](https://www.php.net/manual/ru/language.types.type-juggling.php)

PHP не требует (и не поддерживает) явного типа при определении переменной; тип переменной определяется по контексту, в котором она используется. То есть, если вы присвоите значение типа string переменной $var, то $var изменит тип на string. Если вы затем присвоите $var значение типа int, она станет целым числом (int).

Примером автоматического преобразования типа является оператор умножения '*'. Если какой-либо из операндов является float, то все операнды интерпретируются как float, и результатом также будет float. В противном случае операнды будут интерпретироваться как int, и результат также будет int. Обратите внимание, что это НЕ меняет типы самих операндов; меняется только то, как они вычисляются и сам тип выражения.

```php
$foo = "1";  // $foo - это строка (ASCII-код 49)
$foo *= 2;   // $foo теперь целое число (2)
$foo = $foo * 1.3;  // $foo теперь число с плавающей точкой (2.6)
$foo = 5 * "10 Little Piggies"; // $foo - это целое число (50)
$foo = 5 * "10 Small Pigs";     // $foo - это целое число (50)
```

Если последние два приведённых выше примера кажутся странными, посмотрите, как [строки, содержащие числа](https://www.php.net/manual/ru/language.types.numeric-strings.php), преобразуются в целые числа.

Если вы хотите, чтобы переменная принудительно вычислялась как определённый тип, смотрите раздел приведение типов. Если вы хотите изменить тип переменной, смотрите [settype()](https://www.php.net/manual/ru/function.settype.php).

Если вы хотите протестировать любой из примеров, приведённых в данном разделе, вы можете использовать функцию [var_dump()](https://www.php.net/manual/ru/function.var-dump.php).

###### Замечание:

`
Поведение автоматического преобразования в массив в настоящий момент не определено.

К тому же, так как PHP поддерживает индексирование в строках аналогично смещениям элементов массивов, следующий пример будет верен для всех версий PHP:
`

```php
$a    = 'car'; // $a - это строка
$a[0] = 'b';   // $a всё ещё строка
echo $a;       // bar
```

Более подробно смотрите в разделе [доступ к символу в строке](https://www.php.net/manual/ru/language.types.string.php#language.types.string.substr).

#### Приведение типов

Приведение типов в PHP работает так же, как и в C: имя требуемого типа записывается в круглых скобках перед приводимой переменной.

```php
$foo = 10;   // $foo - это целое число
$bar = (boolean) $foo;   // $bar - это булев тип
```

Допускаются следующие приведения типов:

* (int), (integer) - приведение к int
* (bool), (boolean) - приведение к bool
* (float), (double), (real) - приведение к float
* (string) - приведение к string
* (array) - приведение к array
* (object) - приведение к object
* (unset) - приведение к NULL

Приведение типа (binary) и поддержка префикса b существует для прямой поддержки. Обратите внимание, что (binary) по существу то же самое, что и (string), но не следует полагаться на этот тип приведения.

Приведение типа (unset) объявлено устаревшим с PHP 7.2.0. Обратите внимание, что приведение типа (unset) это то же самое, что присвоение NULL переменной. Тип приведения (unset) удалён в PHP 8.0.0.

Обратите внимание, что внутри скобок допускаются пробелы и символы табуляции, поэтому следующие примеры равносильны по своему действию:

```php
$foo = (int) $bar;
$foo = ( int ) $bar;
```

Приведение строковых литералов и переменных к бинарным строкам:

```php
$binary = (binary) $string;
$binary = b"binary string";
```

###### Замечание:

Вместо использования приведения переменной к string, можно также заключить её в двойные кавычки.

```php
$foo = 10;            // $foo - это целое число
$str = "$foo";        // $str - это строка
$fst = (string) $foo; // $fst - это тоже строка

// Это напечатает "они одинаковы"
if ($fst === $str) {
	echo "они одинаковы";
}
```

Может быть не совсем ясно, что именно происходит при приведении между типами. Для дополнительной информации смотрите разделы:

* [Преобразование в булев тип](https://www.php.net/manual/ru/language.types.boolean.php#language.types.boolean.casting)
* [Преобразование в целое число](https://www.php.net/manual/ru/language.types.integer.php#language.types.integer.casting)
* [Преобразование в число с плавающей точкой](https://www.php.net/manual/ru/language.types.float.php#language.types.float.casting)
* [Преобразование в строку](https://www.php.net/manual/ru/language.types.string.php#language.types.string.casting)
* [Преобразование в массив](https://www.php.net/manual/ru/language.types.array.php#language.types.array.casting)
* [Преобразование в объект](https://www.php.net/manual/ru/language.types.object.php#language.types.object.casting)
* [Преобразование в ресурс](https://www.php.net/manual/ru/language.types.resource.php#language.types.resource.casting)
* [Преобразование в NULL](https://www.php.net/manual/ru/language.types.null.php#language.types.null.casting)
* [Таблицы сравнения типов](https://www.php.net/manual/ru/types.comparisons.php)



### [Объявление типов](https://www.php.net/manual/ru/language.types.declarations.php)

```
Замечание:
Объявление типов также известно, как подсказки для типов в PHP 5.
```

Объявления типов позволяют функциям строго задавать тип передаваемых параметров. Передача в функцию значений несоответствующего типа будет приводить к ошибке: в PHP 5 это будет обрабатываемая фатальная ошибка, а в PHP 7 будет выбрасываться исключение [TypeError](https://www.php.net/manual/ru/class.typeerror.php).

Чтобы объявить тип аргумента, необходимо перед его именем добавить имя требуемого типа. Объявление типов может принимать значения NULL, если значение по умолчанию для аргумента является NULL.

#### Допустимые типы

Тип	| Описание | Минимальная версия PHP
--- | --- | ---
Имя класса/интерфейса| Значение должно представлять собой [instanceof](https://www.php.net/manual/ru/language.operators.type.php) заданного класса или интерфейса.
self | Значение должно представлять собой `instanceof` того же класса, в котором используется объявление типа. Может использоваться только в классах.
parent | Значение должно представлять собой `instanceof` родительского класса, в котором используется объявление типа. Может использоваться только в классах.
array | Значение должно быть типа array.
[callable](https://www.php.net/manual/ru/language.types.callable.php) | Значение должно быть корректным `callable`. Нельзя использовать в качестве объявления для свойств класса.
bool | Значение должно быть логического типа.
float | Значение должно быть числом с плавающей точкой.
int | Значение должно быть целым числом.
string | Значение должно быть строкой (тип string).
[iterable](https://www.php.net/manual/ru/language.types.iterable.php) | Значение может быть либо массивом (тип array), либо представлять собой `instanceof` [Traversable](https://www.php.net/manual/ru/class.traversable.php). | PHP 7.1.0
object | Значение должно быть объектом (тип object). | PHP 7.2.0
[mixed](https://www.php.net/manual/ru/language.types.declarations.php#language.types.declarations.mixed) | Значение может иметь любой тип. | PHP 8.0.0

`Внимание`
Псевдонимы для вышеперечисленных скалярных типов не поддерживаются. Вместо этого они рассматриваются как имена классов или интерфейсов. К примеру, используя boolean как параметр или возвращаемое значение, потребует, чтобы эти аргумент или возвращаемое значение были `instanceof` класса или интерфейса boolean, а не типа `bool`:

```php
function test(boolean $param) {}
test(true);
```

###### Результат выполнения данного примера:

```
Fatal error: Uncaught TypeError: Argument 1 passed to test() must be an instance of boolean, boolean given, called in - on line 1 and defined in -:1
```

##### Пример #1 Объявление типа для класса

```php
class C {}
class D extends C {}

// Это не является расширением класса C.
class E {}

function f(C $c) {
	echo get_class($c)."\n";
}

f(new C);
f(new D);
f(new E);
```

###### Результат выполнения данного примера:

```
C
D

Fatal error: Uncaught TypeError: Argument 1 passed to f() must be an instance of C, instance of E given, called in - on line 14 and defined in -:8
Stack trace:
#0 -(14): f(Object(E))
#1 {main}
  thrown in - on line 8
```

##### Пример #2 Объявление типа для интерфейса

```php
interface I { public function f(); }
class C implements I { public function f() {} }

// Это не реализует интерфейс I.
class E {}

function f(I $i) {
	echo get_class($i)."\n";
}

f(new C);
f(new E);
```

###### Результат выполнения данного примера:

```
C

Fatal error: Uncaught TypeError: Argument 1 passed to f() must implement interface I, instance of E given, called in - on line 13 and defined in -:8
Stack trace:
#0 -(13): f(Object(E))
#1 {main}
  thrown in - on line 8
```

##### Пример #3 Объявление типа возвращаемого значения

```php
function sum($a, $b): float {
	return $a + $b;
}

// Обратите внимание, что будет возвращено число с плавающей точкой.
var_dump(sum(1, 2));
```

###### Результат выполнения данного примера:

```
float(3)
```

##### Пример #4 Возвращение объекта

```php
class C {}

function getC(): C {
	return new C;
}

var_dump(getC());
```

###### Результат выполнения данного примера:

```
object(C)#1 (0) {
}
```

##### Пример #4.1 Типизированные параметры, передаваемые по ссылке

Объявленные типы параметров-ссылок проверяются при вызове функции, но не при возврате функции, поэтому после того, как функция вернула значение, тип аргумента может измениться.

```php
function array_baz(array &$param)
{
	$param = 1;
}
$var = [];
array_baz($var);
var_dump($var);
array_baz($var);
```

###### Результатом выполнения данного примера будет что-то подобное:

```
int(1)
	 
Fatal error: Uncaught TypeError: Argument 1 passed to array_baz() must be of the type array, int given, called in %s on line %d
```

#### [Использование типизированных свойств](https://www.php.net/manual/ru/language.oop5.properties.php)

Начиная с PHP 7.4.0, определения свойств могут включать Объявление типов, за исключением типа callable.

##### Пример #4.2 Пример использования типизированных свойств

```php

class User
{
	public int $id;
	public ?string $name;

	public function __construct(int $id, ?string $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
}

$user = new User(1234, null);

var_dump($user->id);
var_dump($user->name);
```

###### Результат выполнения данного примера:

```
int(1234)
NULL
Перед обращением к типизированному свойству у него должно быть задано значение, иначе будет выброшено исключение Error.
```

##### Пример #4.3 Обращение к свойствам

```php

class Shape
{
	public int $numberOfSides;
	public string $name;

	public function setNumberOfSides(int $numberOfSides): void
	{
		$this->numberOfSides = $numberOfSides;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getNumberOfSides(): int
	{
		return $this->numberOfSides;
	}

	public function getName(): string
	{
		return $this->name;
	}
}

$triangle = new Shape();
$triangle->setName("triangle");
$triangle->setNumberofSides(3);
var_dump($triangle->getName());
var_dump($triangle->getNumberOfSides());

$circle = new Shape();
$circle->setName("circle");
var_dump($circle->getName());
var_dump($circle->getNumberOfSides());
```

###### Результат выполнения данного примера:

```
string(8) "triangle"
int(3)
string(6) "circle"

Fatal error: Uncaught Error: Typed property Shape::$numberOfSides must not be accessed before initialization
```

### Обнуляемые типы

Начиная с PHP 7.1.0, объявления типов могут быть помечены как обнуляемые, путём добавления префикса в виде знака вопроса(?). Это означает, что значение может быть как объявленного типа, так и быть равным null.

##### Пример #5 Объявление обнуляемых типов

```php
class C {}

function f(?C $c) {
	var_dump($c);
}

f(new C);
f(null);
```

###### Результат выполнения данного примера:

```
object(C)#1 (0) {
}
NULL
```

##### Пример #6 Обнуляемые типы для возвращаемого значения

```php
function get_item(): ?string {
	if (isset($_GET['item'])) {
		return $_GET['item'];
	} else {
		return null;
	}
}
```

###### Замечание:

До PHP 7.1.0, было возможно задавать обнуляемые типы аргументов функций путём задания значения по умолчанию равного null. Так делать не рекомендуется, поскольку это может поломать наследование.

##### Пример #7 Старый способ задавать обнуляемые типы для аргументов

```php
class C {}

function f(C $c = null) {
	var_dump($c);
}

f(new C);
f(null);
```

Результат выполнения данного примера:

```
object(C)#1 (0) {
}
NULL
```

### Объединённые типы

Объединённые типы позволяют использовать несколько типов, а не исключительно один. Для их объявления используется следующий синтаксис: T1|T2|.... Объединённые типы доступны начиная с PHP 8.0.0.

#### Обнуляемые объединённые типы

Тип null можно использовать как часть объединений следующим образом: T1|T2|null. Существующая нотация ?T рассматривается как сокращение для T|null.

`
Предостережение
null не может использоваться как отдельный тип.
`

###### Псевдотип false

Псевдотип false поддерживается как часть объединённых типов. Он добавлен по историческим причинам, так как многие встроенные функции возвращают false вместо null в случае ошибок. Классический пример - функция strpos().

`
Предостережение
false нельзя использовать как самостоятельный тип (включая обнуляемый вариант). Таким образом, все объявления такого типа недопустимы: false, false|null и ?false.
`

`
Предостережение
Обратите внимание, что псевдотип true не существует.
`

#### Дублирующиеся и повторяющиеся типы

Для отлова простых ошибок в объединённых объявлениях, повторяющиеся типы, которые можно отследить без загрузки класса, приведут к ошибке компиляции. В том числе:

* Каждый тип, распознаваемый по имени, должен встречаться только один раз. Типы вида int|string|INT приведут к ошибке.
* Если используется bool, то использовать дополнительно false нельзя.
* Если используется тип object, то дополнительное использование имён классов недопустимо.
* Если используется iterable, то к нему нельзя добавить array или Traversable.

`Замечание: Это не гарантирует, что все объединённые типы объявлены корректно, поскольку такая проверка потребует загрузки всех используемых классов.`

К примеру, если A и B являются псевдонимами одного и того же класса, то A|B выглядит как корректный объединённый тип, даже если фактически объявление может быть сокращено до A или B. Аналогично, если B extends A {}, то A|B тоже выглядит корректным типом, несмотря на то, что он может быть сокращён до A.

```php
function foo(): int|INT {} // Запрещено
function foo(): bool|false {} // Запрещено

use A as B;
function foo(): A|B {} // Запрещено ("use" является частью разрешения имён)

class_alias('X', 'Y');
function foo(): X|Y {} // Допустимо (повторение будет определено только во время выполнения)
```

#### Типы, подходящие только для возвращаемого значения

###### void

Тип void означает, что функция ничего не возвращает. Соответственно, он не может быть частью объединения. Доступно с PHP 7.1.0.

###### static

Значение должно представлять собой instanceof того же класса, в котором был вызван метод. Доступно с PHP 8.0.0.

### Строгая типизация

По умолчанию PHP будет пытаться привести значения несоответствующих типов к скалярному типу, если это возможно. Например, если в функцию передается целое число (`integer`), а тип аргумента объявлен как строка (`string`), в итоге функция получит преобразованное в строку (`string`) значение.

Для отдельных файлов можно включать режим строгой типизации. В этом режиме в функцию можно передавать значения только тех типов, которые объявлены для аргументов. В противном случае будет выбрасываться исключение `TypeError`. Есть лишь одно исключение - целое число (`integer`) можно передать в функцию, которая ожидает значение типа `float`. Вызовы функций внутри встроенных функций не будут затронуты директивой strict_types.

Для включения режима строгой типизации используется выражение [declare](https://www.php.net/manual/ru/control-structures.declare.php) в объявлении strict_types:

`Предостережение`
Включение режима строгой типизации также повлияет на `объявления типов возвращаемых значений`.

`Замечание:`

Режим строгой типизации распространяется на вызовы функций, совершенные из файла, в котором этот режим включен, а не на функции, которые в этом файле объявлены. Если файл без строгой типизации вызывает функцию, которая объявлена в файле с включенным режимом, значения аргументов будут приведены к нужным типам и ошибок не последует.

`Замечание:`

Строгая типизация применима только к скалярным типам и работает только в PHP 7.0.0 и выше. Равно как и сами объявления скалярных типов добавлены в этой версии.

##### Пример #11 Строгая типизация

```php
declare(strict_types=1);

function sum(int $a, int $b) {
	return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
```

###### Результат выполнения данного примера:

```
int(3)

Fatal error: Uncaught TypeError: Argument 1 passed to sum() must be of the type integer, float given, called in - on line 9 and defined in -:4
Stack trace:
#0 -(9): sum(1.5, 2.5)
#1 {main}
  thrown in - on line 4
```

##### Пример #12 Слабая типизация

```php
function sum(int $a, int $b) {
	return $a + $b;
}

var_dump(sum(1, 2));

// Будут приведены к целым числам: обратите внимание на результат ниже!
var_dump(sum(1.5, 2.5));
```

###### Результат выполнения данного примера:

```
int(3)
int(3)
```

##### Пример #13 Обработка исключения `TypeError`

```php
declare(strict_types=1);

function sum(int $a, int $b) {
	return $a + $b;
}

try {
	var_dump(sum(1, 2));
	var_dump(sum(1.5, 2.5));
} catch (TypeError $e) {
	echo 'Ошибка: '.$e->getMessage();
}
```

###### Результат выполнения данного примера:

```
int(3)
Ошибка: Argument 1 passed to sum() must be of the type integer, float given, called in - on line 10
```

### [Списки аргументов переменной длины](https://www.php.net/manual/ru/functions.arguments.php#functions.variable-arg-list)

PHP поддерживает списки аргументов переменной длины для функций, определяемых пользователем. Для версий PHP 5.6 и выше это делается добавлением многоточия (...). Для версий 5.5 и старше используются функции [func_num_args()](https://www.php.net/manual/ru/function.func-num-args.php), [func_get_arg()](https://www.php.net/manual/ru/function.func-get-arg.php) и [func_get_args()](https://www.php.net/manual/ru/function.func-get-args.php).

`... в PHP 5.6+`

В версиях PHP 5.6 и выше список аргументов может содержать многоточие ..., чтобы показать, что функция принимает переменное количество аргументов. Аргументы в этом случае будут переданы в виде массива. Например:

##### Пример #14 Использование ... для доступа к аргументам

```php
function sum(...$numbers) {
	$acc = 0;
	foreach ($numbers as $n) {
		$acc += $n;
	}
	return $acc;
}

echo sum(1, 2, 3, 4);
```

###### Результат выполнения данного примера:

```
10
```

Многоточие (...) можно использовать при вызове функции, чтобы распаковать массив (`array`) или [Traversable](https://www.php.net/manual/ru/class.traversable.php) переменную в список аргументов:

##### Пример #15 Использование ... для передачи аргументов

```php
function add($a, $b) {
	return $a + $b;
}

echo add(...[1, 2])."\n";

$a = [1, 2];
echo add(...$a);
```

###### Результат выполнения данного примера:

```
3
3
```

Можно задать несколько аргументов в привычном виде, а затем добавить .... В этом случае ... поместит в массив только те аргументы, которые не нашли соответствия указанным в объявлении функции.

Также можно добавить подсказку типа перед .... В этом случае PHP будет следить, чтобы все аргументы обработанные многоточием (...) были того же типа, что указан в подсказке.

##### Пример #16 Аргументы с подсказкой типов

```php
function total_intervals($unit, DateInterval ...$intervals) {
	$time = 0;
	foreach ($intervals as $interval) {
		$time += $interval->$unit;
	}
	return $time;
}

$a = new DateInterval('P1D');
$b = new DateInterval('P2D');
echo total_intervals('d', $a, $b).' days';

// Это не сработает, т.к. null не является объектом DateInterval.
echo total_intervals('d', null);
```

###### Результат выполнения данного примера:
```
3 days
Catchable fatal error: Argument 2 passed to total_intervals() must be an instance of DateInterval, null given, called in - on line 14 and defined in - on line 2
```

В конце концов, можно передавать аргументы [по ссылке](https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference). Для этого перед ... нужно поставить амперсанд (&).
