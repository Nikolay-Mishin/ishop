### SQL Работа с датами

http://lifeexample.ru/razrabotka-i-optimizacia-saita/sql-rabota-s-datami.html

```php
NOW() — Возвращает текущую дату и время.
CURDATE() — Возвращает текущую дату.
CURTIME() — Возвращаем текущее время.
DATE() — Состоит из двух частей даты и времени.
EXTRACT() — Возвращает одно значения даты/времени.
DATE_ADD() — Добавляет до выборки указанное число дней/мину/часов и т.д.
DATE_SUB() — Вычитываем указанный интервал от даты.
DATEDIFF() — Возвращает значение времени между двумя датами.
DATE_FORMAT() — Функция для различного вывода информации о времени.
```

`Работа с датами в SQl`, как оказывается не такая сложная, и теперь вместо того чтобы вычислять периоды средствами PHP можно делать это еще на этапе выполнения SQL запроса и получать необходимую выборку данных.

##### Как получить текущую дату в SQL

###### 1 вариант:

```php
WHERE date = CURDATE()
```

###### 2 вариант:

```php
WHERE date = STR_TO_DATE(now(), '%Y-%m-%d')
```

##### Прибавить к дате один час в SQL

```php
DATE_ADD('2013-03-30', INTERVAL 1 HOUR)
```

##### Прибавить к дате один день в SQL

```php
DATE_ADD('2013-03-30', INTERVAL 1 DAY)
```

Аналогично можно прибавлять любое количество дней к текущей дате.

##### Прибавить к дате один месяц в SQL

```php
DATE_ADD('2013-03-30', INTERVAL 1 MONTH)
```

Аналогично можно прибавлять любое количество месяцев к текущей дате.

##### Получить вчерашний день в SQL

###### Первый вариант:

```php
DATE_ADD(CURDATE(), INTERVAL -1 DAY)
```

###### Второй вариант:

```php
DATE_SUB(CURDATE(), INTERVAL 1 DAY)
```

##### Получить дату начала текущей недели в SQL

Вот эта одна из самых сложных на первый взгляд задач, но решается очень просто:

```php
CURDATE()-WEEKDAY(CURDATE());
```

##### Получить выборку с этого понедельника по текущий день недели в SQL

```php
WHERE (
date BETWEEN
(CURDATE()-WEEKDAY(CURDATE()))
AND
CURDATE()
)
```

##### Получить выборку с первого числа текущего месяца по текущий день недели в SQL

```php
WHERE (
date BETWEEN
(CURDATE()-WEEKDAY(CURDATE()))
 AND
CURDATE())
```

##### Как получить дату рождения пользователя в SQL

```php
SELECT name, birth, CURRENT_DATE,
     (YEAR(CURRENT_DATE)-YEAR(birth))
     - (RIGHT(CURRENT_DATE,5)<RIGHT(birth,5))
     AS age
FROM user;
```

##### Найти всех пользователей у которых день рождение в следующем месяце в SQL

```php
SELECT name, birth FROM user
WHERE MONTH(birth) = MONTH(DATE_ADD(NOW(), INTERVAL 1 MONTH));
```
###### Или второй вариант:

```php
SELECT name, birth FROM pet
WHERE MONTH(birth) = MOD(MONTH(NOW()), 12) + 1;
```



### call_user_func_array

https://www.php.net/manual/ru/function.call-user-func-array.php

` call_user_func_array ( callable $callback , array $param_arr ) : mixed `

Вызывает callback-функцию `callback`, с параметрами из массива `param_arr`.

##### Пример #1 Пример использования функции call_user_func_array()

```php
function foobar($arg, $arg2) {
    echo __FUNCTION__, " got $arg and $arg2\n";
}
class foo {
    function bar($arg, $arg2) {
        echo __METHOD__, " got $arg and $arg2\n";
    }
}


// Вызываем функцию foobar() с 2 аргументами
call_user_func_array("foobar", array("one", "two"));

// Вызываем метод $foo->bar() с 2 аргументами
$foo = new foo;
call_user_func_array(array($foo, "bar"), array("three", "four"));
```

###### Результатом выполнения данного примера будет что-то подобное:

```
foobar got one and two
foo::bar got three and four
```

##### Пример #2 Пример использования call_user_func_array() c именем пространства имен

```php
namespace Foobar;

class Foo {
    static public function test($name) {
        print "Hello {$name}!\n";
    }
}

// Начиная с версии PHP 5.3.0
call_user_func_array(__NAMESPACE__ .'\Foo::test', array('Hannes'));

// Начиная с версии PHP 5.3.0
call_user_func_array(array(__NAMESPACE__ .'\Foo', 'test'), array('Philip'));
```

###### Результатом выполнения данного примера будет:

```
Hello Hannes!
Hello Philip!
```



### list

https://www.php.net/manual/ru/function.list.php

` list ( mixed $var1 [, mixed $... ] ) : array `

`list` — Присваивает переменным из списка значения подобно массиву

Подобно `array()`, это не функция, а языковая конструкция. `list()` используется для того, чтобы присвоить списку переменных значения за одну операцию.

##### Пример #1 Примеры использования list()

```php
$info = array('кофе', 'коричневый', 'кофеин');

// Составить список всех переменных
list($drink, $color, $power) = $info;
echo "$drink - $color, а $power делает его особенным.\n";

// Составить список только некоторых из них
list($drink, , $power) = $info;
echo "В $drink есть $power.\n";

// Или пропустить все, кроме третьей
list( , , $power) = $info;
echo "Мне нужен $power!\n";

// list() не работает со строками
list($bar) = "abcde";
var_dump($bar); // NULL
```

##### Пример #2 Пример использования list()

```php
<table>
 <tr>
  <th>Имя работника</th>
  <th>Зарплата</th>
 </tr>

<?php
$result = $pdo->query("SELECT id, name, salary FROM employees");
while (list($id, $name, $salary) = $result->fetch(PDO::FETCH_NUM)) {
    echo " <tr>\n" .
          "  <td><a href=\"info.php?id=$id\">$name</a></td>\n" .
          "  <td>$salary</td>\n" .
          " </tr>\n";
}
?>

</table>
```

##### Пример #3 Использование list() с индексами массивов

```php
list($a, list($b, $c)) = array(1, array(2, 3));

var_dump($a, $b, $c);
```

###### Результатом выполнения данного примера будет:
```
int(1)
int(2)
int(3)
```

##### Пример #4 Использование list() с индексами массива

```php
$info = array('кофе', 'коричневый', 'кофеин');

list($a[0], $a[1], $a[2]) = $info;

var_dump($a);
```



### get_called_class

https://www.php.net/manual/ru/function.get-called-class.php

`get_called_class` — Имя класса, полученное с помощью позднего статического связывания

```php
get_called_class ( void ) : string
```

Возвращает имя класса, из которого был вызван статический метод.

##### Пример #1 Пример использования get_called_class()

```php
class foo {
    static public function test() {
        var_dump(get_called_class());
    }
}

class bar extends foo {
}

foo::test();
bar::test();
```

###### Результат выполнения данного примера:
```
string(3) "foo"
string(3) "bar"
```



### get_parent_class

https://www.php.net/manual/ru/function.get-parent-class.php

`get_parent_class` — Возвращает имя родительского класса для объекта или класса

```php
get_parent_class ([ mixed $object ] ) : string
```

Возвращает имя родительского класса для объекта или класса.

##### Список параметров
`object`
Тестируемый объект или имя класса. Если вызывается из метода объекта, то этот параметр не обязателен.

##### Пример #1 Пример использования get_parent_class()

```php
class dad {
    function dad()
    {
    // реализация какой-нибудь логики
    }
}

class child extends dad {
    function child()
    {
        echo "I'm " , get_parent_class($this) , "'s son\n";
    }
}

class child2 extends dad {
    function child2()
    {
        echo "I'm " , get_parent_class('child2') , "'s son too\n";
    }
}

$foo = new child();
$bar = new child2();
```

###### Результат выполнения данного примера:
```
I'm dad's son
I'm dad's son too
```



### is_subclass_of

https://www.php.net/manual/ru/function.is-subclass-of.php

`is_subclass_of` — Проверяет, содержит ли объект в своем дереве предков указанный класс либо прямо реализует его

```php
is_subclass_of ( mixed $object , string $class_name [, bool $allow_string = TRUE ] ) : bool
```

Проверяет, содержит ли объект object в своем дереве предков класс class_name либо прямо реализует его.

##### Список параметров
`object`
Имя класса или экземпляр объекта. В случае отсутствия такого класса никакой ошибки сгенерировано не будет.

`class_name`
Имя класса

`allow_string`
Если параметр установлен в false, то не допускается имя класса в виде строки в качестве параметра object. Это также предотвращает вызов автозагрузчика, если класс не существует.

##### Пример #1 Пример использования is_subclass_of()

```php
// объявляем класс
class WidgetFactory
{
  var $oink = 'moo';
}

// объявляем наследника
class WidgetFactory_Child extends WidgetFactory
{
  var $oink = 'oink';
}

// создаем новый объект
$WF = new WidgetFactory();
$WFC = new WidgetFactory_Child();

if (is_subclass_of($WFC, 'WidgetFactory')) {
  echo "да, \$WFC наследует WidgetFactory\n";
} else {
  echo "нет, \$WFC не наследует WidgetFactory\n";
}


if (is_subclass_of($WF, 'WidgetFactory')) {
  echo "да, \$WF наследует WidgetFactory\n";
} else {
  echo "нет, \$WF не наследует WidgetFactory\n";
}


// применимо только с версии PHP 5.0.3
if (is_subclass_of('WidgetFactory_Child', 'WidgetFactory')) {
  echo "да, WidgetFactory_Child наследует WidgetFactory\n";
} else {
  echo "нет, WidgetFactory_Child не наследует WidgetFactory\n";
}
```

###### Результат выполнения данного примера:

```
да, $WFC наследует WidgetFactory
нет, $WF не наследует WidgetFactory
да, WidgetFactory_Child наследует WidgetFactory
```

### ReflectionClass::getShortName

https://www.php.net/manual/ru/reflectionclass.getshortname.php

`ReflectionClass::getShortName` — Возвращает короткое имя

```php
public ReflectionClass::getShortName ( void ) : string
```

Возвращает короткое имя класса, часть, которая не относится к названию пространства имён.

##### Пример #1 Пример использования ReflectionClass::getShortName()

```php
namespace A\B;

class Foo { }

$function = new \ReflectionClass('stdClass');

var_dump($function->inNamespace());
var_dump($function->getName());
var_dump($function->getNamespaceName());
var_dump($function->getShortName());

$function = new \ReflectionClass('A\\B\\Foo');

var_dump($function->inNamespace());
var_dump($function->getName());
var_dump($function->getNamespaceName());
var_dump($function->getShortName());
```

###### Результат выполнения данного примера:

```
bool(false)
string(8) "stdClass"
string(0) ""
string(8) "stdClass"

bool(true)
string(7) "A\B\Foo"
string(3) "A\B"
string(3) "Foo"
```


### method_exists

https://www.php.net/manual/ru/function.method-exists

`method_exists` — Проверяет, существует ли метод в данном классе

```php
method_exists ( mixed $object , string $method_name ) : bool
```

Проверяет, существует ли метод класса в указанном объекте object.

##### Примечания

Вызов этой функции будет использовать все зарегистрированные функции автозагрузки, если класс еще не известен.

##### Список параметров
`object`
Экземпляр объекта или имя класса

`method_name`
Имя метода

##### Пример #1 Пример использования method_exists()

```php
$directory = new Directory('.');
var_dump(method_exists($directory,'read'));
```

###### Результат выполнения данного примера:

```
bool(true)
```

##### Пример #2 Пример статического использования method_exists()

```php
var_dump(method_exists('Directory','read'));
```

###### Результат выполнения данного примера:

```
bool(true)
```



### property_exists

https://www.php.net/manual/ru/function.property-exists.php

`property_exists` — Проверяет, содержит ли объект или класс указанный атрибут

```php
property_exists ( mixed $class , string $property ) : bool
```

Функция проверяет, существует ли атрибут property в указанном классе.

##### Примечания

В противоположность isset(), property_exists() возвращает TRUE, даже если свойство имеет значение NULL.

Вызов этой функции будет использовать все зарегистрированные функции автозагрузки, если класс еще не известен.

##### Список параметров

`class`
Имя класса или объект класса для проверки

`property`
Имя свойства

##### Пример #1 Пример использования property_exists()

```php
class myClass {
    public $mine;
    private $xpto;
    static protected $test;

    static function test() {
        var_dump(property_exists('myClass', 'xpto')); //true
    }
}

var_dump(property_exists('myClass', 'mine'));   //true
var_dump(property_exists(new myClass, 'mine')); //true
var_dump(property_exists('myClass', 'xpto'));   //true, начиная с версии PHP 5.3.0
var_dump(property_exists('myClass', 'bar'));    //false
var_dump(property_exists('myClass', 'test'));   //true, начиная с версии PHP 5.3.0
myClass::test();
```



### in_array

https://www.php.net/manual/ru/function.in-array.php

`in_array` — Проверяет, присутствует ли в массиве значение

```php
in_array ( mixed $needle , array $haystack [, bool $strict = FALSE ] ) : bool
```

Ищет в haystack значение needle. Если strict не установлен, то при поиске будет использовано нестрогое сравнение.

##### Список параметров

`needle`
Искомое значение.
```
Замечание:
Если `needle` - строка, сравнение будет с учетом регистра.
```

`haystack`
Массив.

`strict`
Если третий параметр `strict` установлен в `TRUE`, тогда функция `in_array()` также проверит соответствие `типов` параметра `needle` и соответствующего значения массива `haystack`.

##### Пример #1 Пример использования in_array()

```php
$os = array("Mac", "NT", "Irix", "Linux");
if (in_array("Irix", $os)) {
    echo "Нашел Irix";
}
if (in_array("mac", $os)) {
    echo "Нашел mac";
}
```

Второго совпадения не будет, потому что `in_array()` регистрозависима, таким образом, программа выведет:

```
Нашел Irix
```

##### Пример #2 Пример использования in_array() с параметром strict

```php
$a = array('1.10', 12.4, 1.13);

if (in_array('12.4', $a, true)) {
    echo "'12.4' найдено со строгой проверкой\n";
}

if (in_array(1.13, $a, true)) {
    echo "1.13 найдено со строгой проверкой\n";
}
```

###### Результат выполнения данного примера:

```
1.13 найдено со строгой проверкой
```

##### Пример #3 Пример использования in_array() с массивом в качестве параметра needle

```php
$a = array(array('p', 'h'), array('p', 'r'), 'o');

if (in_array(array('p', 'h'), $a)) {
    echo "'ph' найдено\n";
}

if (in_array(array('f', 'i'), $a)) {
    echo "'fi' найдено\n";
}

if (in_array('o', $a)) {
    echo "'o' найдено\n";
}
```

###### Результат выполнения данного примера:

```
'ph' найдено
'o' найдено
```


### class_exists

`class_exists` — Проверяет, был ли объявлен класс

```php
class_exists ( string $class_name [, bool $autoload = TRUE ] ) : bool
```

Эта функция проверяет, был ли объявлен указанный класс или нет.

##### Список параметров

`class_name`
Имя класса. Воспринимается без учета регистра.

`autoload`
Вызывать ли по умолчанию __autoload.

##### Список изменений

Больше не возвращает TRUE для объявленных интерфейсов. Используйте для этого `interface_exists()`.

https://www.php.net/manual/ru/function.interface-exists.php

##### Пример #1 Пример использования class_exists()

```php
// Проверяем существование класса перед его использованием
if (class_exists('MyClass')) {
    $myclass = new MyClass();
}
```

##### Пример #2 Пример использования c параметром autoload

```php
function __autoload($class)
{
    include($class . '.php');

    // Проверяем необходимость подключения указанного класса
    if (!class_exists($class, false)) {
        trigger_error("Не удалось загрузить класс: $class", E_USER_WARNING);
    }
}

if (class_exists('MyClass')) {
    $myclass = new MyClass();
}
```
