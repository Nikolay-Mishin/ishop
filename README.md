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
