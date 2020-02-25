### Класс ReflectionClass

https://www.php.net/manual/ru/class.reflectionclass.php

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



### Аргументы функции

https://www.php.net/manual/ru/functions.arguments.php

Функция может принимать информацию в виде списка аргументов, который является списком разделенных запятыми выражений. Аргументы вычисляются слева направо.

PHP поддерживает передачу аргументов по значению (по умолчанию), `передачу аргументов по ссылке`, и `значения по умолчанию`. `Списки аргументов переменной длины` также поддерживаются, смотрите также описания функций `func_num_args()`, `func_get_arg()` и `func_get_args()` для более детальной информации.

https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference
https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.default
https://www.php.net/manual/ru/functions.arguments.php#functions.variable-arg-list

https://www.php.net/manual/ru/function.func-num-args.php
https://www.php.net/manual/ru/function.func-get-arg.php
https://www.php.net/manual/ru/function.func-get-args.php

##### Пример #1 Передача массива в функцию

```php
function takes_array($input)
{
    echo "$input[0] + $input[1] = ", $input[0]+$input[1];
}
```

### Передача аргументов по ссылке

https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference

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

### Значения аргументов по умолчанию

https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.default

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

### Объявление типов

```
Замечание:
Объявление типов также известно, как подсказки для типов в PHP 5.
```

Объявления типов позволяют функциям строго задавать тип передаваемых параметров. Передача в функцию значений несоответствующего типа будет приводить к ошибке: в PHP 5 это будет обрабатываемая фатальная ошибка, а в PHP 7 будет выбрасываться исключение `TypeError`.

https://www.php.net/manual/ru/class.typeerror.php

Чтобы объявить тип аргумента, необходимо перед его именем добавить имя требуемого типа. Объявление типов может принимать значения NULL, если значение по умолчанию для аргумента является NULL.

##### Допустимые типы

Тип	Описание	Минимальная версия PHP
Имя класса/интерфейса	Аргумент должен быть instanceof, что и имя класса или интерфейса.	PHP 5.0.0
self	Этот параметр должен быть instanceof того же класса, в методе которого он указан. Это можно использовать только в методах класса или экземпляра.	PHP 5.0.0
array	Аргумент должен быть типа array.	PHP 5.1.0
callable	Аргумент должен быть корректным callable-типом.	PHP 5.4.0
bool	Аргумент должен быть типа boolean.	PHP 7.0.0
float	Аргумент должен быть типа float.	PHP 7.0.0
int	Аргумент должен быть типа integer.	PHP 7.0.0
string	Аргумент должен иметь тип string.	PHP 7.0.0
iterable	Параметр должен быть либо массивом, либо экземпляром класса, реализующего Traversable.	PHP 7.1.0
object	Параметр должен быть объектом (object).	PHP 7.2.0

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

##### Пример #7 Объявление типа базового класса

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

##### Пример #8 Объявление типа базового интерфейса

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

##### Пример #9 Типизированные параметры, передаваемые по ссылке

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

##### Пример #10 Обнуляемое объявление типа

```php
class C {}

function f(C $c = null) {
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

### Строгая типизация

По умолчанию PHP будет пытаться привести значения несоответствующих типов к скалярному типу, если это возможно. Например, если в функцию передается целое число (`integer`), а тип аргумента объявлен как строка (`string`), в итоге функция получит преобразованное в строку (`string`) значение.

Для отдельных файлов можно включать режим строгой типизации. В этом режиме в функцию можно передавать значения только тех типов, которые объявлены для аргументов. В противном случае будет выбрасываться исключение `TypeError`. Есть лишь одно исключение - целое число (`integer`) можно передать в функцию, которая ожидает значение типа `float`. Вызовы функций внутри встроенных функций не будут затронуты директивой strict_types.

Для включения режима строгой типизации используется выражение `declare` в объявлении strict_types:

https://www.php.net/manual/ru/control-structures.declare.php

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

### Списки аргументов переменной длины

https://www.php.net/manual/ru/functions.arguments.php#functions.variable-arg-list

PHP поддерживает списки аргументов переменной длины для функций, определяемых пользователем. Для версий PHP 5.6 и выше это делается добавлением многоточия (...). Для версий 5.5 и старше используются функции `func_num_args()`, `func_get_arg()` и `func_get_args()`.

https://www.php.net/manual/ru/function.func-num-args.php
https://www.php.net/manual/ru/function.func-get-arg.php
https://www.php.net/manual/ru/function.func-get-args.php

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

Многоточие (...) можно использовать при вызове функции, чтобы распаковать массив (`array`) или `Traversable` переменную в список аргументов:

https://www.php.net/manual/ru/class.traversable.php

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

В конце концов, можно передавать аргументы `по ссылке`. Для этого перед ... нужно поставить амперсанд (&).

https://www.php.net/manual/ru/functions.arguments.php#functions.arguments.by-reference
