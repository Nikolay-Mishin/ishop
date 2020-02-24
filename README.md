# ishop - part 3
### Часть 3. Написание администраторской части CMS интернет магазина

##### ДЗ 05 - сделать наименование товара в заказе ссылкой (вытащить алиас через join из таблицы product по product_id)
```
app\views\admin\Order\view.php
app\controllers\admin\OrderController.php
```

##### ДЗ 06 - сделать проверку сохранены ли изменения
` app\controllers\admin\OrderController.php => changeAction() `

##### ДЗ 12 - разделить editAction на view и edit
* view - отображает пользователя, edit - принимает и обрабатывает данные из формы
* ` app\controllers\admin\UserController.php => editAction() `

##### ДЗ 14 - сделать пагинацию для списка заказов пользователя
` app\controllers\admin\UserController.php => viewAction() `

##### ДЗ 20 - реализовать удаление фильтров у продукта
` app\models\admin\Product.php => editFilter() `

##### ДЗ 23 - обрабатывать и выводить ошибки загрузки изображения в js файле
` public\adminlte\my.js `

##### ДЗ 24 - добавить модификаторы товара (таблица - modification)
` app\controllers\admin\ProductController.php => addAction() `

##### ДЗ 25 - реализовать удаление базовой картинки по аналогии с удалением картинок галлереи (при клике на картинку)
` app\controllers\admin\ProductController.php => editAction() `

##### ДЗ 27 - реализовать удаление товаров (параллельно удалять модификации, фильтры, связанные товары, галлерею, товары заказа (?))
` app\controllers\admin\ProductController.php => deleteAction() `



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

`method_exists` — Проверяет, существует ли метод в данном классе

```php
method_exists ( mixed $object , string $method_name ) : bool
```

Проверяет, существует ли метод класса в указанном объекте object.

Список параметров
`object`
Экземпляр объекта или имя класса

`method_name`
Имя метода

Возвращаемые значения ¶
Возвращает TRUE, если метод method_name определен для указанного объекта object, иначе возвращает FALSE.

##### Примечания

Вызов этой функции будет использовать все зарегистрированные функции автозагрузки, если класс еще не известен.

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



### Оглавление

Урок 1.  Шаблон AdminLTE 

Урок 2.  Авторизация администратора 

Урок 3.  Виджеты главной страницы 

Урок 4.  Список заказов 

Урок 5.  Обработка заказа. Часть 1 

Урок 6.  Обработка заказа. Часть 2 

Урок 7.  Управление категориями. Часть 1 

Урок 8.  Управление категориями. Часть 2 

Урок 9.  Управление категориями. Часть 3 

Урок 10.  Управление категориями. Часть 4 

Урок 11.  Управление категориями. Часть 5 

Урок 12.  Управление кэшем 

Урок 13.  Управление пользователями. Часть 1 

Урок 14.  Управление пользователями. Часть 2 

Урок 15.  Управление пользователями. Часть 3 

Урок 16.  Управление пользователями. Часть 4 

Урок 17.  Управление товарами. Список товарова 

Урок 18.  Управление товарами. Форма добавления 

Урок 19.  Управление товарами. CKEditor 

Урок 20.  Управление товарами. Добавление фильтров 

Урок 21.  Управление товарами. Связанные товары. Часть 1 

Урок 22.  Управление товарами. Связанные товары. Часть 2 

Урок 23.  Управление товарами. Загрузка картинок. Часть 1 

Урок 24.  Управление товарами. Загрузка картинок. Часть 2 

Урок 25.  Управление товарами. Редактирование товара. Часть 1 

Урок 26.  Управление товарами. Редактирование товара. Часть 2 

Урок 27.  Управление товарами. Редактирование товара. Часть 3 

Урок 28.  Управление фильтрами. Часть 1 

Урок 29.  Управление фильтрами. Часть 2 

Урок 30.  Управление фильтрами. Часть 3 

Урок 31.  Управление валютами. Часть 1 

Урок 32.  Управление валютами. Часть 2

### Бонус 1. Премиум курс по PHP+PHP7 и MySQL

Урок 1.  Введение в программирование на PHP 

Урок 2.  Синтаксис PHP. Основы синтаксиса языка PHP 

Урок 3.  PHP переменные и константы 

Урок 4.  Типы данных в PHP 

Урок 5.  Операторы в PHP. Часть 1 

Урок 6.  Операторы в PHP. Часть 2 

Урок 7.  Управляющие конструкции PHP. Условия 

Урок 8.  Управляющие конструкции PHP. Цикл while и do-while 

Урок 9.  Массивы в PHP 

Урок 10.  PHP: Функции для работы с массивами 

Урок 11.  Цикл for в PHP 

Урок 12.  Цикл foreach в PHP 

Урок 13.  Альтернативный синтаксис PHP 

Урок 14.  require и include в PHP 

Урок 15.  Пользовательские функции в PHP 

Урок 16.  Функция header в PHP. Часть 1 

Урок 17.  Функция header в PHP. Часть 2 

Урок 18.  Функции работы со строками в PHP. Часть 1 

Урок 19.  Функции работы со строками в PHP. Часть 2 

Урок 20.  Функции работы со строками в PHP. Часть 3 

Урок 21.  Функции даты и времени в PHP. Часть 1 

Урок 22.  Функции даты и времени в PHP. Часть 2 

Урок 23.  Методы GET и POST в PHP 

Урок 24.  Загрузка файлов в PHP 

Урок 25.  Работа с сессиями в PHP 

Урок 26.  Работа с куками в PHP 

Урок 27.  Функции для работы с файлами в PHP 

Урок 28.  Практика создания гостевой книги 

Урок 29.  Сервер MySQL 

Урок 30.  Функции PHP для работы с базами данных. Часть 1 

Урок 31.  Функции PHP для работы с базами данных. Часть 2 

Урок 32.  Практика создания гостевой книги с использованием БД 

Урок 33.  PHP 7. Часть 1 

Урок 34.  PHP 7. Часть 2 

Урок 35.  PHP 7. Часть 3 

Урок 36.  PHP 7. Часть 4 

Урок 37.  PHP 7. Часть 5 

Урок 38.  PHP 7. Часть 6 

Урок 39.  PHP 7. Часть 7 

### Бонус 2. Премиум курс по ООП PHP (Объектно-ориентированное программирование на PHP)

Урок 1.  Класс и объект 

Урок 2.  Свойства объекта 

Урок 3.  Методы объекта 

Урок 4.  Методы __construct и __destruct 

Урок 5.  Домашнее задание. Класс для работы с файлом 

Урок 6.  Константы класса. Статические свойства и методы 

Урок 7.  Наследование. Часть 1 

Урок 8.  Наследование. Часть 2 

Урок 9.  Модификаторы доступа 

Урок 10.  Абстрактные классы и интерфейсы 

Урок 11.  Интерфейсы и контроль типа 

Урок 12.  Автозагрузка и пространства имен 

Урок 13.  Composer и автозагрузка 

Урок 14.  Трейты 

Урок 15.  Позднее статическое связывание 

Урок 16.  Магические методы 

Урок 17.  Шаблоны проектирования

### Бонус 3. Перенос сайта на хостинг

Урок 1.  Перенос сайта на хостинг

### Бонус 4. Подключение платежной системы

Урок 1.  Подключение платежной системы. Часть 1 

Урок 2.  Подключение платежной системы. Часть 2 

### Бонус 5. Личный кабинет покупателя

Урок 1.  Личный кабинет покупателя. Часть 1 

Урок 2.  Личный кабинет покупателя. Часть 2 

### Бонус 6. Канонические URL

Урок 1.  Канонические URL 
