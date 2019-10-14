# ishop - part 2
# Часть 2. Написание пользовательской части CMS интернет-магазина

## ДЗ 2-4 - рассчитать размер скидки программно (при наличии старой цены)
### app\views\Main\index.php (line 78)

## ДЗ 2-6 - делать не запрос к БД, а выбирать валюту из контейнера (реестра)
### app\controllers\CurrencyController (line 15)

## ДЗ 2-7 - сделать функцию округления при пересчете цены
### app\views\Main\index.php (line 71, 74)

## ДЗ 2-15 - ссылка для просмотра всех просмотренных товаров
### app\controllers\ProductController (line 39)

## ДЗ 2-17 - модификации товара (цвет + размер, обновлять старую цену вместе с базовой (старые цены для модификаций излишне))
### app\controllers\ProductController (line 54)
### app\views\Product\view.php (line 66, 75)
* цена может отличать и от цвета и от размера
* можно сделать что-то вроде зависимых списков - при выборе 1 подгружить что-то другое (и наоборот)
* также учитывать, что 1 цвет может быть все цвета, а другой цвет - не все (часть списка)

## ДЗ 2-22 - в корзине реализовать увеличение и уменьшение количества по каждой позиции
### app\controllers\CartController
### app\models\Cart
### public\js\main.js

## 2-30 - при регистрации и входе вывод php ошибок сделать под полями формы
### app\controllers\UserController
### app\layouts\watches.php (line 176)
### app\views\User\signup.php
### app\views\User\login.php

## ДЗ 2-31 - при регистрации авторизовывать пользователя (+ перенапрявлять со страницы входа уже авторизованного пользователя)
### app\controllers\UserController
### app\models\User

## ДЗ 2-33 - сделать правильное сохранение заказа (с помощью метода save в базовой модели)
### app\models\Order->saveOrder() (line 8)

## ДЗ 2-35 - выводить в фильтрах виды полей (checkbox, radio-button, select, input)
### app\controllers\CartController
### app\models\Cart
### использовать транзакции в БД (интернет) - сначала сохраняем заказ, потом выгружаем товары по заказу. Есть шанс, что мы сохраним заказ, а в момент выгрузки товаров сервер откажет и будет заказ без товаров (order_product). Оформление заказа и выгрузку товаров сделать 1 транзакцией - в случае ошибки на 1 из этапов откатываются все изменения в цепочке (если не выгрузились товары, откатывается изменение в заказах и данный заказ удаляется).
### кол-во сделать input и при изменении данного инпута или нажатии кнопки пересчитать
### собираем все инпуты и id товаров и пересчитываем корзину с помощью метода recalc()

## ДЗ 2-36 - добавить фильтры по брендам и диапазону цен + выводить в фильтрах виды полей (checkbox, select, radio-button, input)
### app\widgets\Filter
### app\widgets\filter_tpl.php
### в attribute_value добавляем поле type, на основе которого формируется соответствующий html-код
### type: checkbox | select | boolean      | string     | int (диапазон цен)
###       checkbox | select | radio-button | input(str) | input(int)
#### https://zlob.in/2013/01/struktura-tablic-dlya-kataloga-tovarov-internet-magazina/
#### http://softtime.ru/forum/read.php?id_forum=3&id_theme=88061
#### https://gist.github.com/greabock/afc4a08577806b60dc61

# HAVING оператор MySQL

#### https://expange.ru/e/Having_count_(MySQL)

## При применении в запросе агрегирующих функций (COUNT(), SUM(), AVG() и др.), чтобы выполнить условие придется использовать параметр HAVING (WHERE не получится использовать).

## Задача
### Например есть две таблицы «Новости» и «Комментарии» (таблицы создавались в статье Количество записей в дочерней таблице) — необходимо вывести список новостей, у которых больше четырех комментариев.

## Запрос
```sql
SELECT n.`id`, n.`date`, n.`text`, COUNT(c.`id`) as cnt
FROM news n , comments c
WHERE c.`id_news`=n.`id`
GROUP BY n.`id`
HAVING COUNT(c.`id`)>3;
```

## Результат
```php
// id   date        text                            COUNT(c.`id`)
// 1    2010-12-16  Опубликована первая статья      4
// 5    2011-01-08  Открыта страница лица expange   4
```

#### https://oracleplsql.ru/having-mysql.html

* выбираем товары из категории 6 и во 2 выборку IN идет вложенный sql-запрос
* для товаров attribute_product мы выбираем те товары, для которых поле attr_id = 1,5
* 1,2,3,4 - простая выборка (если 1 из фильтров совпадает)
* 1,2,3 - сложная выборка (если все группы фильтров совпадают) | группа 1 - аттрибут 1, группа 2 - аттрибут 5

* простая выборка
```sql
SELECT `product`.*  FROM `product`  WHERE category_id IN (6) AND id IN
(
SELECT product_id FROM attribute_product WHERE attr_id IN (1,5) - простая выборка
)
```
* сложная выборка с оператором HAVING
```sql
SELECT `product`.*  FROM `product`  WHERE category_id IN (6) AND id IN
(
SELECT product_id FROM attribute_product WHERE attr_id IN (1,5) GROUP BY product_id HAVING COUNT(product_id) = 2
)
```

* у продукта 1 имеется аттрибут 1 и 5, а у продукта 4 аттрибут 2 и 5, но тк выбран фильтр 1 и 5 - продукт 4 исключается
* GROUP BY product_id - группировка по product_id (необходима, чтобы сгруппировать данные по данному полю)
* product_id = 1 => [attr_id = 1, attr_id = 5]
* product_id = 4 => [attr_id = 2, attr_id = 5]
* HAVING COUNT(product_id) = 2 - ограничивает
```php
/*
attr_id	product_id
1	    1
1	    2
1	    3
2	    4
5	    1
5	    2
5	    3
5	    4
*/
```

## Описание
### MySQL оператор HAVING используется в сочетании с оператором GROUP BY, чтобы ограничить группы возвращаемых строк только тех, чье условие TRUE.

## Синтаксис
### Синтаксис оператора HAVING в MySQL:
```sql
SELECT expression1, expression2, … expression_n,

       aggregate_function (expression)

FROM tables

[WHERE conditions]

GROUP BY expression1, expression2, … expression_n

HAVING condition;
```

#### Параметры или аргументы
* aggregate_function — функция, такая как функции SUM, COUNT, MIN, MAX или AVG.
* expression1, expression2, … expression_n — выражения, которые не заключены в агрегированную функцию и должны быть включены в предложение GROUP BY.
* WHERE conditions — необязательный. Это условия для выбора записей.
* HAVING condition — Это дополнительное условие применяется только к агрегированным результатам для ограничения групп возвращаемых строк. В результирующий набор будут включены только те группы, состояние которых соответствует TRUE.

## Пример использования функции SUM

### Рассмотрим пример MySQL оператора HAVING, в котором используется функция SUM.
### Вы также можете использовать функцию SUM, чтобы вернуть имя product и общее количество (для этого product). MySQL оператор HAVING будет фильтровать результаты так, чтобы возвращались только product с общим количеством больше 10.

```sql
SELECT product, SUM(quantity) AS "Total quantity"
FROM order_details
GROUP BY product
HAVING SUM(quantity) > 10;
```

## Пример использования функции COUNT

### Рассмотрим, как мы можем использовать оператор HAVING с функцией COUNT в MySQL.
### Вы можете использовать функцию COUNT, чтобы вернуть имя product и количество заказов (для этого product), которые находятся в категории ‘produce’. MySQL оператор HAVING будет фильтровать результаты так, чтобы возвращались только product с более чем 20 заказами.

```sql
SELECT product, COUNT(*) AS "Number of orders"
FROM order_details
WHERE category = 'produce'
GROUP BY product
HAVING COUNT(*) > 20;
```

## Пример использования функции MIN

### Рассмотрим, как мы можем использовать оператор HAVING с функцией MIN в MySQL.
### Вы также можете использовать функцию MIN, чтобы вернуть имя каждого department и минимальную зарплату в department. MySQL оператор HAVING будет возвращать только те department, где минимальная зарплата составляет менее 50000 биткоинов :).

```sql
SELECT department, MIN(salary) AS "Lowest salary"
FROM employees
GROUP BY department
HAVING MIN(salary) < 50000;
```

## Пример использования функции MAX

### Наконец, давайте посмотрим, как мы можем использовать оператор HAVING с функцией MAX в MySQL.
### Например, вы также можете использовать функцию MAX для возврата имени каждого department и максимальной заработной платы в department. Предложение MySQL HAVING будет возвращать только те department, чья максимальная заработная плата превышает 25000 биткоинов :).

```sql
SELECT department, MAX(salary) AS "Highest salary"
FROM employees
GROUP BY department
HAVING MAX(salary) > 25000;
```

# передача пользовательской функции (some_func) в качестве аргумента другой функции
```JavaScript
function ajaxFormRequest(form_id, url, dataT, some_func) {
    $.ajax({
        url: url,
        type: "POST", // Тип запроса
        data: jQuery("#"+form_id).serialize(), 
        dataType: dataT, 
        success: function(response) {
            getInfo('alert-'+response.type, response.msg);
            some_func();
        },
        error: function(response) {
            getInfo('alert-danger', 'Ошибка при отправке формы');
        }
    });
}
```

# Bind, Call и Apply - вызов функций с подменой контекста
## bind - создаёт "обёртку" над функцией, которая подменяет контекст этой функции. Поведение похоже на call и apply, но, в отличие от них, bind не вызывает функцию, а лишь возвращает "обёртку", которую можно вызвать позже.
```JavaScript
function f() {
    alert(this);
}

var wrapped = f.bind('abc');

f(); // [object Window]
wrapped(); // abc
```
## call - вызов функции с подменой контекста - this внутри функции.
```JavaScript
function f(arg) {
    console.log(this);
    console.log(arg);
}

f('abc'); // abc, [object Window]

f.call('123', 'abc'); // 123 (this), abc
```
## apply - вызов функции с переменным количеством аргументов и с подменой контекста.
```JavaScript
Пример:
function f() {
    console.log(this);
    console.log(arguments);
}

f(1, 2, 3); // [object Window], [1, 2, 3]

f.apply('abc', [1, 2, 3, 4]); // abc (this), [1, 2, 3, 4]
```

# создание пользовательской функции с передачей аргументов в виде объекта (по типу JQuery Ajax)
```JavaScript
/**
 * This is how to document the shape of the parameter object
 * @param {boolean} [args.arg1 = false] Blah blah blah
 * @param {boolean} [args.notify = false] Blah blah blah
 */
function doSomething(args) {
    var defaults = {
        arg1: false,
        notify: false
    };
    args = Object.assign(defaults, args);
    console.log(args);

    var arg1 = args.arg1 !== undefined ? args.arg1 : false,
        notify = args.notify !== undefined ? args.notify : false;
    console.log('arg1 = ' + arg1 + ', notify = ' + notify);

    if (args.hasOwnProperty('arg1')) {
        // arg1 isset
    }

    if (args.hasOwnProperty('notify')) {
        // notify isset
    }
}

doSomething({notify: true}); // {arg1: false, notify: true}
```

# Передача аргументов по ссылке
### https://www.php.net/manual/ru/functions.arguments.php
## По умолчанию аргументы в функцию передаются по значению (это означает, что если вы измените значение аргумента внутри функции, то вне ее значение все равно останется прежним). Если вы хотите разрешить функции модифицировать свои аргументы, вы должны передавать их по ссылке.
## Если вы хотите, чтобы аргумент всегда передавался по ссылке, вы можете указать амперсанд (&) перед именем аргумента в описании функции:

```php
function add_some_extra(&$string)
{
    $string .= 'и кое-что еще.';
}
$str = 'Это строка, ';
add_some_extra($str);
echo $str;    // выведет 'Это строка, и кое-что еще.'
```

# Исключения - throw new \Exception('Страница не найдена', 404);
## https://www.php.net/manual/ru/language.exceptions.php

# htmlspecialchars() является подмножеством htmlentities().

## В то время как htmlentities преобразует «все применимые символы в HTML-объекты», htmlspecialchars() преобразует только символы ниже:
* ‘&’ (Амперсанд) становится ‘&amp;’
* ‘ ” ‘ (Двойная кавычка) становится ‘ &quot; ’, когда ENT_NOQUOTES не установлен.
* « ‘ » (Одинарная кавычка) становится ‘ ' ’ (или & apos) только в том случае, если установлен ENT_QUOTES.
* ‘>’ (Больше чем) становится ‘& gt;’

## Обе функции используются для «вывода», чтобы обезопасить веб-страницу от атак с использованием межсайтовых сценариев. Однако в книге Essential PHP Security говорится…
* «Htmlentities() - лучшая экранирующая функция для экранирования данных, отправляемых клиенту».
* Используйте флаг ENT_QUOTES и кодировку UTF-8, как ниже в примере
* htmlentities($userdata, ENT_QUOTES, 'UTF-8');
* Флаг ENT_QUOTES указывает ему преобразовывать как двойные, так и одинарные кавычки.
* И на заметку, параметры двух функций идентичны.

## htmlspecialchars:

1. Если нет необходимости кодировать все символы, имеющие свои эквиваленты HTML.
* Если вы знаете, что кодировка страницы соответствует текстовым специальным символам, зачем использовать htmlentities? htmlspecialchars является очень простым, и выдает меньше кода для отправки клиенту. Например:

```php
echo htmlentities('<Il était une fois un être>.');
// Output: &lt;Il &eacute;tait une fois un &ecirc;tre&gt;.
//                ^^^^^^^^                 ^^^^^^^
```

```php
echo htmlspecialchars('<Il était une fois un être>.');
// Output: &lt;Il était une fois un être&gt;.
//                ^                 ^
```

* Второй вариант короче и не вызывает проблем, если установлена ​​кодировка ISO-8859-1.

2. Когда данные будут обрабатываться не только через браузер (чтобы избежать декодирования HTML-объектов),

3. Если вывод XML (см. ответ Artefacto).

## Потому что:

1. Иногда вы пишете XML-данные, и вы не можете использовать HTML-объекты в XML файле.
* Потому что htmlentities заменяет больше символов, чем htmlspecialchars. Это необязательно, делает PHP скрипт менее эффективным, а полученный HTML-код менее читабельным.
2. htmlentities необходимо, только если ваши страницы используют кодировки, такие как ASCII или LATIN-1 вместо UTF-8, и вы обрабатываете данные с кодировкой, отличной от страницы.

## Это кодируется htmlentities.

```php
implode( "\t", array_values( get_html_translation_table( HTML_ENTITIES ) ) ):

"& <>
¢ ¤ ¤ ¦ ¦ ¦ ¨ ª ± ± ± ´ ± ± ± µ º µ µ ½ ½ ½ À À À À À Ã Ã Å Å É É Ì Í Í Î Î Î Ð Î Î Ð Ð Ñ Ñ Ð Ð Ð Ð Ð Ð × Õ Ö ×
Ù Ú Û Û Û Û ß ß ß ß ã ç ç ç ç ç ë ë ì ì ì ï ï ò ò õ õ ü û ü œ Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š
Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š Š. Α Β Γ Δ Ε Ζ Η Θ Ι Λ Λ Μ Ν Ξ Ο Π Σ Σ Τ Υ Φ β Ω Ψ β α β γ γ γ
β η η θ ι ι λ μ μ ω ϑ ϒ ϖ ‌ ‍ - - - - „„ • • ‹‹ ‹‹ ‹‹ ‹‹ ℵ ℵ ℵ ℵ ⇒ ⇒ ⇒ ⇒ ⇒ ⇒ ⇒ ⇒ ⇒ ⇒ ∉ ∉ ∉ ∉ ∑ ∏ ∑ - ∗ √ ∞
∞ ∧ ∩ ∩ ∪ ∪ ∴ ≈ ≈ ≈ ≡ ≤ ≤ ≥ ≥ ⊄ ⊆ ⊇ ⊥ ⋅ ♦ ♦ ♦ ♦ ♦ ♦ ♦
```

## Это кодируется с помощью htmlspecialchars.
```php
implode( "\t", array_values( get_html_translation_table( HTML_SPECIALCHARS ) ) ):

"& <>
```

# Valitron\Validator
### https://github.com/vlucas/valitron

## Встроенные правила проверки
* required - Поле, обязательное для заполнения
* requiredWith - Поле обязательно для заполнения, если есть другие поля
* requiredWithout - Поле обязательно для заполнения, если нет других полей
* equals - Поле должно соответствовать другому полю (электронная почта / подтверждение пароля)
* different - Поле должно отличаться от другого поля
* accepted - Флажок или Радио должны быть приняты (да, вкл, 1, правда)
* numeric - Должен быть числовым
* integer - Должно быть целым числом
* boolean - Должно быть логическим
* array - должен быть массив
* length - Строка должна быть определенной длины
* lengthBetween - Строка должна быть между заданными длинами
* lengthMin - Строка должна быть больше указанной длины
* lengthMax - Строка должна быть меньше заданной длины
* min - минимум
* max - максимум
* listContains- Выполняет проверку in_array для заданных значений массива (наоборот in)
* in - выполняет проверку in_array для заданных значений массива
* notIn- Отрицание inправила (не в массиве значений)
* ip - Действительный IP-адрес
* ipv4 - Действительный IP v4-адрес
* ipv6 - Действительный IP-адрес v6
* email - Действующий электронный адрес
* emailDNS - Действительный адрес электронной почты с активной записью DNS
* url - Действительный URL
* urlActive - Действительный URL с активной записью DNS
* alpha - только буквенные символы
* alphaNum - Буквенные и числовые символы только
* ascii - только символы ASCII
* slug - символы слагов URL (az, 0-9, -, _)
* regex - Поле соответствует заданному шаблону регулярных выражений
* date - Поле является действительной датой
* dateFormat - Поле является действительной датой в указанном формате
* dateBefore - Поле является действительной датой и предшествует указанной дате
* dateAfter - Поле является действительной датой и находится после указанной даты
* contains - Поле является строкой и содержит данную строку
* subset - Поле - это массив или скаляр, и все элементы содержатся в данном массиве.
* containsUnique - Поле является массивом и содержит уникальные значения
* creditCard - Поле является действительным номером кредитной карты
* instanceOf - Поле содержит экземпляр данного класса
* optional- Значение не нужно включать в массив данных. Однако, если это так, он должен пройти проверку.
* arrayHasKeys - Поле является массивом и содержит все указанные ключи.

## набор правил для валидации
### Пример проверки формы
```php
public $rules = [
    // обязательные поля - правило проверяет , если поле существует в массиве данных, 
    // и не является нулевым или пустой строкой
    'required' => [
        ['login'],
        ['password'],
        ['name'],
        ['email'],
        ['address'],
    ],
    // поле email - проверяет , что поле является действительным адресом электронной почты
    'email' => [
        ['email'],
    ],
    // минимальная длина для поля
    'lengthMin' => [
        ['password', 6],
    ]
]
```

### Проверка полей
```php
public $rules = [
    // если два поля равны в массиве данных, а также о том , что второе поле не является пустым
    // ['password' => 'youshouldnotseethis', 'confirmPassword' => 'youshouldnotseethis']
    'equals' => [
        ['password', 'confirmPassword']
    ],
    // два поля не совпадают, или разные, в массиве данных и о том , что второе поле не является пустым
    // ['username' => 'spiderman', 'password' => 'Gr33nG0Blin']
    'different' => [
        ['username', 'password']
    ]
]
```

### Проверка значений
```php
public $rules = [
    // поле соответствует заданному шаблону регулярного выражения.
    // (Это регулярное выражение проверяет, что строка является буквенно-цифровой от 5 до 10 символов)
    // ['username' => 'Batman123']
    'regex' => [
        ['username', '/^[a-zA-Z0-9]{5,10}$/']
    ],
    // является ли поле точно заданной длины , и что поле является действительной строкой
    'length' => [
        ['username', 10]
    ],
    // если поле между заданной длиной Тангой и что полем является действительной строкой
    'lengthBetween' => [
        ['username', 1, 10]
    ],
    // если поле по меньшей мере , заданное значение , и что при условии , значение числовое
    // ['age' => 28]
    'min' => [
        ['age', 18]
    ],
    // поле содержит только символы слизняка URL (Az, 0-9, -, _)
    // ['username' => 'L337-H4ckZ0rz_123']
    'slug' => [
        ['username']
    ],
    // поле только алфавитные символы
    // ['username' => 'batman']
    'alpha' => [
        ['username']
    ],
    // поле содержит только буквенные или цифровые символы
    // ['username' => 'batman123']
    'alphaNum' => [
        ['username']
    ]
]
```

### Проверка типов
```php
public $rules = [
    // является ли поле номер. Это аналог функции is_numeric () в php
    // ['amount' => 3.14]
    'numeric' => [
        ['amount']
    ],
    // если поле является целым числом
    // ['age' => '27']
    'integer' => [
        ['age']
    ],
    // Обратите внимание, что необязательный логический флаг для строгого режима позволит указывать целые числа
    // как отрицательные значения. Таким образом, следующее правило оценивается как истинное
    // ['age' => '-27']
    'integer' => [
        ['age', true]
    ],
    // если поле является логическим. Это аналог функции is_bool () в php
    // ['remember_me' => true]
    'boolean' => [
        ['remember_me']
    ],
    // если поле является массивом. Это аналог функции is_array () в php
    /* ['user_notifications' => [
        'bulletin_notifications' => true,
        'marketing_notifications' => false,
        'message_notification' => true]
    ] */
    'array' => [
        ['user_notifications']
    ]
]
```

### Проверка даты
```php
public $rules = [
    // поле является действительным \ DateTime объекта
    // или , если строка может быть преобразован в метку времени UNIX с помощью StrToTime ()
    // ['created_at' => '2018-10-13']
    'date' => [
        ['created_at']
    ],
    // прилагаемое поле является действительной датой в заданном формате дата
    'dateFormat' => [
        ['created_at', 'Y-m-d']
    ],
    // прилагаемое поле является действительной датой до указанной даты
    // ['created_at' => '2018-09-01']
    'dateBefore' => [
        ['created_at', '2018-10-13']
    ],
    // прилагаемое поле является действительной датой после указанной даты
    'dateAfter' => [
        ['created_at', '2018-01-01']
    ]
]
```

### Проверка URL
```php
public $rules = [
    // поле является допустимой URL
    // ['website' => 'https://example.com/contact']
    'url' => [
        ['website']
    ]
]
```

### Проверка IP
```php
public $rules = [
    // поле является действительным адресом IP. Это включает в себя IPv4, IPv6
    // частные и зарезервированные диапазоны
    // ['user_ip' => '127.0.0.1']
    'ip' => [
        ['user_ip']
    ]
]
```

### Проверка кредитной карты
```php
public $rules = [
    // Проверка кредитной карты в настоящее время позволяет проверить Visa visa, 
    // Mastercard mastercard, Dinersclub dinersclub, American Express amex или Discoverdiscover
    // ['credit_card' => '']
    // Это будет проверять кредитную карту по каждому типу карты
    // $v->rule('creditCard', 'credit_card');
    'creditCard' => [
        ['credit_card']
    ]
    // Чтобы дополнительно фильтровать типы карт, добавьте слаг в массив в качестве следующего параметра
    // $v->rule('creditCard', 'credit_card', ['visa', 'mastercard']);
    'creditCard' => [
        ['credit_card', ['visa', 'mastercard']]
    ]
    // Если вы хотите проверить только один тип карты, поместите ее в виде строки
    // $v->rule('creditCard', 'credit_card', 'visa');
    'creditCard' => [
        ['credit_card', 'visa']
    ]
    // Если информация о типе карты поступает от клиента, вы также можете указать массив допустимых типов карт
    // $cardType = 'amex';
    // $v->rule('creditCard', 'credit_card', $cardType, ['visa', 'mastercard']);
    'creditCard' => [
        ['credit_card', $cardType, ['visa', 'mastercard']]
    ]
]
```

### Contains fields usage (Содержит использование полей)
```php
public $rules = [
    // данная строка существует в пределах поля и проверяет , что поле и значение для поиска
    // являются как действительными строками
    // ['username' => 'Batman123']
    'contains' => [
        ['username', 'man']
    ]
    // Вы можете использовать необязательный строгий флаг для обеспечения соответствия с учетом регистра
    // Принимая во внимание, что это возвратило бы ложь, поскольку M в строке поиска
    // не в верхнем регистре в предоставленном значении
    'contains' => [
        ['username', 'Man', true]
    ]
]
```