## Чат на WebSocket'ах

###### #C/C#/C++ #PHP

В статье описывается, как разработать и запустить простейший чат на вебсокетах.

![Окно чата](https://wxmaper.ru/pub/simplychat/scr1-1.png)

##### Сервер

Существует много готовых продуктов для запуска websocket-сервера. Наш сервер мы запустим используя библиотеку Workerman, ключевое преимущество которой – это полное отсутствие зависимостей: скачал, запустил – работает. Также в Workerman имеется поддержка таймеров прямо из коробки, они нам пригодятся. И конечно же! Библиотека написана на PHP, а все любят PHP ;-)

##### Клиент

Для разработки приложения-клиента я выбрал фреймворк Qt только потому, что он мне нравится. Qt – кроссплатформенная библиотека (фреймворк), позволяющая создавать программы практически под любую платформу: Windows, Linux, Android, OS X, iOS и др. Разумеется, клиент для полученного сервера можно написать на чем угодно, хоть на HTML+JS, который будет работать прямо из браузера.

Итак, приступим.

### 1. Настройка VDS-сервера

Сразу после приобретения VDS и установки операционной системы (выбрал свежую версию Ubuntu 18.04 на тарифе «Master») подключаемся к нему. На сервер можно зайти через консоль из панели управления VDS, но это не самый удобный вариант. Предпочтительнее подключаться по SSH.

Если разные способы подключения по SSH из Windows, например:

1. Воспользоваться программой [Putty](https://www.putty.org/);
2. Воспользоваться терминалом [Cygwin](https://www.cygwin.com/);
3. Воспользоваться терминалом Ubuntu из [WSL](https://ru.wikipedia.org/wiki/Windows_Subsystem_for_Linux) (я выбрал этот способ).

В Linux намного проще, клиент для подключения по SSH, как правило, установлен во всех дистрибутивах по умолчанию, поэтому просто открываем терминал.

Независимо от выбранного способа, команда для подключения будет одна:
```
ssh -l root {VDS_IP_ADDRESS}
```

где `{VDS_IP_ADDRESS}` – это IP-адрес вашего сервера, который можно найти в панели управления VDS (блок «Список используемых IP-адресов»).

![Окно терминала](https://wxmaper.ru/pub/simplychat/scr2-2.png)

#### Установка Workerman

Чтобы скачать Workerman, сначала устанавливаем composer:

```
# apt update
# apt install composer
```

Теперь скачиваем Workerman в папку /usr/local/workerman:

```
# mkdir /usr/local/workerman
# cd /usr/local/workerman
# composer require workerman/workerman
```

И создаём php-файл, в котором будем писать код сервера чата:

```
touch ChatWorker.php
```

Далее открываем файл `ChatWorker.php` для редактирования. Это можно сделать разными способами. Самый хардкорный и олдскульный вариант - редактировать прямо в терминале, воспользовавшись консольными редакторами nano, mcedit, vim и др.

Если работаете в Linux, то из рабочего окружения KDE можно подключиться через файловый менеджер `Dolphin` по протоколу `SFTP` и открыть файл в любом редакторе или даже в IDE (например, в `KDevelop`).

Если работаете в Windows, то можете скачать `Notepad++` с плагином `NppFTP`, либо что-то более продвинутое, вроде `Sublime / Atom / Visual Studio Code`, и так же подключиться по протоколу `SFTP`.

### 2. Сервер

Чат будет работать по такому принципу: сообщения между сервером и клиентом передаются в формате JSON с указанием, какое «действие» (action) выполняет это сообщение. Таким образом можно разделить сообщения на типы: служебные, публичные, приватные и т.д., и с лёгкостью дополнять различные типы служебной и иной сопутствующей информацией.

Такой подход позволит в будущем при необходимости произвести «безболезненную» модернизацию чата. Например, если сервер начнёт отправлять сообщения неизвестного действия, то клиент просто не будет на них реагировать, но можно оповестить пользователя о том, что он использует устаревшую версию клиента.

Наш простейший чат будет поддерживать следующие действия:

##### авторизация пользователя

`action = Authorized`

При подключении пользователя к чату сервер предварительно проверяет свободность выбранного никнейма. Если никнейм занят, то приписываем к нему номер (2, 3, 4 и т.д.). Если никнейм свободен, то отправляем пользователю сообщение «Authorized», в котором передаются данные, с которыми он был авторизован в чате и список пользователей чата.
Дополнительно пользователь может выбрать цвет отображения своего имени и указать, к какому полу (М/Ж) относится.

##### оповещение всех пользователей о присоединении нового участника к чату

`action = Connected`

После авторизации нового пользователя сервер отправляет всем участникам сообщение «Connected», в котором передаются данные авторизованного пользователя.

##### оповещение всех пользователей при выходе участника из чата

`action = Disconnected`

При выходе пользователя из чата все участники оповещаются сообщением «Disconnected».

##### отправка сообщения в общий чат

`action = PublicMessage`

Если пользователь отправляет сообщение в чат без указания адресата, то такое сообщение определяется как «Публичное» и рассылается всем участникам.

##### отправка приватного сообщения

`action = PrivateMessage`

Если пользователь отправляет сообщение в чат с указанием адресата, то такое сообщение определяется как «Приватное» и отправляется только адресату.

##### проверка пользователей на потерю соединений

`action = Ping`

Сервер рассылает всем участникам служебное сообщение «Ping» с определённым интервалом и ожидает от каждого ответное сообщение «Pong».

##### оповещение пользователей о потере соединения участником

`action = ConnectionLost`

Если сервером несколько раз подряд не будет получено ответное на «Ping» сообщение от участника, то участник считается отключившимся, всем остальным участникам рассылается сообщение «ConnectionLost» с данными “отвалившегося” пользователя.

#### Инициализация WebSocket-сервера

`ChatWorker.php`

```php 

// Подключаем библиотеку Workerman
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Lib\Timer;
use Workerman\Worker;

$connections = []; // сюда будем складывать все подключения

// Стартуем WebSocket-сервер на порту 27800
$worker = new Worker("websocket://0.0.0.0:27800");

Worker::runAll();
```

И это всё. При запуске этого php-скрипта WebSocket-сервер будет запущен на порту `27800` и к нему уже можно будет подключиться.

Но обратите внимание: можно указать любой другой свободный порт, главное не забыть открыть его на VDS-сервере командой:

```
iptables -I INPUT -p tcp --dport {PORT} --syn -j ACCEPT
```

где `{PORT}` – выбранный вами порт для чата.

Запускаем WebSocket-сервер командой:

```
php ChatWorker.php start
```

![Запущенный Workerman](https://wxmaper.ru/pub/simplychat/scr3-1.png)

Для проверки соединения и дальнейшей отладки можно воспользоваться плагином [Simple WebSocket Client](https://chrome.google.com/webstore/detail/simple-websocket-client/pfdhoblngboilpfeibdedpjgfnlcodoo?hl=ru) для браузера Google Chrome.

![Окно плагина Simple WebSocket Client](https://wxmaper.ru/pub/simplychat/scr3.png)

В поле Server `Location -> URL:` вводим адрес сервера, начиная с названия протокола: `ws://` и нажимаем кнопку `Open`. 

При успешном подключении метка `Status: CLOSED` будет заменена на `OPENED` и разблокируется поле `Request`, которое в дальнейшем можно будет использовать для отправки тестовых запросов как от клиента. По сути, наш браузер уже является клиентом для сервера, просто не имеет визуального оформления и обработчиков сообщений.

Инициализировать сервер было легко, но надо ведь ещё обработать события!

#### Подключение и авторизация пользователя

Авторизация пользователя будет происходить во время подключения клиента к серверу с передачей параметров. Параметры подключения передаются в адресной строке как в обычном URL-адресе (QUERY STRING), а на сервере их можно прочитать из переменной `$_GET`.

Предполагается, что при подключении приложение-клиент должно передать все необходимые сведения о пользователе:
- отображаемое имя (ник): параметр `userName`;
- цвет ника: параметр `userColor`;
- пол: параметр `gender`.

Если какой-то из этих параметров не передан, то его значение должно устанавливаться автоматически. 

При подключении клиента к websocket-серверу, вызывается функция Worker::onConnect, в которую передается указатель на созданный объект соединения TcpConnection.

После подключения обрабатываются все параметры подключения (а нас как раз и интересует то, с какими параметрами осуществлялось подключение) и затем вызывается функция установки соединения TcpConnection::onWebSocketConnect – в которой будет доступна обработанная переменная $_GET - отсюда будем извлекать сведения о подключившемся клиенте.

`ChatWorker.php`

```php

// Подключаем библиотеку Workerman
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Lib\Timer;
use Workerman\Worker;

$connections = []; // сюда будем складывать все подключения

// Стартуем WebSocket-сервер на порту 27800
$worker = new Worker("websocket://0.0.0.0:27800");

$worker->onConnect = function($connection)
{
    // Эта функция выполняется при подключении пользователя к WebSocket-серверу
    $connection->onWebSocketConnect = function($connection) 
    {
        echo "Hello World!\n"; // все сообщения выводятся в терминал сервера
        print_r($_GET);
        $connection->send("Hello World!"); // а это сообщение будет отправлено клиенту
    };
};

Worker::runAll();
```

###### Обратите внимание!

Вызов функции `Worker::runAll()` запускает цепь обработки событий Workerman. Код, написанный после вызова этой функции, не будет выполнен. Помните об этом при внесении дальнейших изменений.


Сохраним файл на сервере и перезапустим Workerman. Остановить предыдущий запуск можно клавишами CTRL+C, а затем снова запустить той же командой:

```
php ChatWorker.php start
```

###### Обратите внимание!

Перезапускать WebSocket-сервер нужно после каждого внесения изменений в любой из php-скриптов вашего проекта.

Пробуем подключиться с передачей параметров: `ws://{IP}:27800?userName=anonymous`

И вот, наконец, мы добрались до `Hello World!`

![Hello World!](https://wxmaper.ru/pub/simplychat/scr4.png)

Теперь можно написать полный код авторизации пользователя, обеспечивающий выполнение ранее изложенных требований.

```php
$worker->onConnect = function($connection) use(&$connections)
{
    // Эта функция выполняется при подключении пользователя к WebSocket-серверу
    $connection->onWebSocketConnect = function($connection) use (&$connections)
    {
        // Достаём имя пользователя, если оно было указано
        if (isset($_GET['userName'])) {
            $originalUserName = preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9\-\_ ]/u', '', trim($_GET['userName']));
        }
        else {
            $originalUserName = 'Инкогнито';
        }
        
        // Половая принадлежность, если указана
        // 0 - Неизвестный пол
        // 1 - М
        // 2 - Ж
        if (isset($_GET['gender'])) {
            $gender = (int) $_GET['gender'];
        }
        else {
            $gender = 0;
        }
        
        if ($gender != 0 && $gender != 1 && $gender != 2) 
            $gender = 0;
        
        // Цвет пользователя
        if (isset($_GET['userColor'])) {
            $userColor = $_GET['userColor'];
        }
        else {
            $userColor = "#000000";
        }
                
        // Проверяем уникальность имени в чате
        $userName = $originalUserName;
        
        $num = 2;
        do {
            $duplicate = false;
            foreach ($connections as $c) {
                if ($c->userName == $userName) {
                    $userName = "$originalUserName ($num)";
                    $num++;
                    $duplicate = true;
                    break;
                }
            }
        } 
        while($duplicate);
        
        // Добавляем соединение в список
        // + мы можем добавлять произвольные поля в $connection
        //   и затем читать их из любой функции:
        $connection->userName = $userName; 
        $connection->gender = $gender;
        $connection->userColor = $userColor;
        $connection->pingWithoutResponseCount = 0; // счетчик безответных пингов
        
        $connections[$connection->id] = $connection;
        
        // Собираем список всех пользователей
        $users = [];
        foreach ($connections as $c) {
            // TcpConnection::id - уникальный идентификатор соединения, 
            // присваивается автоматически. Будем использовать его как 
            // идентификатор пользователя 'userId'.
            $users[] = [
                'userId' => $c->id,
                'userName' => $c->userName,
                'gender' => $c->gender,
                'userColor' => $c->userColor
            ];
        }
        
        // Отправляем пользователю данные авторизации
        $messageData = [
            'action' => 'Authorized',
            'userId' => $connection->id,
            'userName' => $connection->userName,
            'gender' => $connection->gender,
            'userColor' => $connection->userColor,
            'users' => $users
        ];
        $connection->send(json_encode($messageData));
        
        // Оповещаем всех пользователей о новом участнике в чате
        $messageData = [
            'action' => 'Connected',
            'userId' => $connection->id,
            'userName' => $connection->userName,
            'gender' => $connection->gender,
            'userColor' => $connection->userColor
        ];
        $message = json_encode($messageData);
        
        foreach ($connections as $c) {
            $c->send($message);
        }
    };
};
```

Сохраняем файл, перезапускаем сервер, переподключаемся к вебсокету и видим получение двух сообщений:

![Авторизация пользователя](https://wxmaper.ru/pub/simplychat/scr5.png)

Первое (`Authorized`) отправляется только подключившемуся пользователю, чтобы сообщить ему, с какими данными он был подключен.

Второе (`Connection`) отправляется всем, в том числе и подключившемуся, его мы в дальнейшем будем использовать в клиенте для пополнения списка пользователей.

#### Отключение пользователя

Когда клиент закрывает соединение с сервером, вызывается функция `Worker::onClose`, тут обработаем выход пользователя из чата:

```php
$worker->onClose = function($connection) use(&$connections)
{
    // Эта функция выполняется при закрытии соединения
    if (!isset($connections[$connection->id])) {
        return;
    }
    
    // Удаляем соединение из списка
    unset($connections[$connection->id]);
    
    // Оповещаем всех пользователей о выходе участника из чата
    $messageData = [
        'action' => 'Disconnected',
        'userId' => $connection->id,
        'userName' => $connection->userName,
        'gender' => $connection->gender,
        'userColor' => $connection->userColor
    ];
    $message = json_encode($messageData);
    
    foreach ($connections as $c) {
        $c->send($message);
    }
};
```

Выполняйте перезапуск и подключение к вебсокету после каждого внесения изменений, чтобы проконтролировать работоспособность сервера.

#### Пинг пользователей

При вызове метода `Worker::runAll` запускаются все объявленные «работники» (их может быть несколько), а при их запуске вызывается функция `Worker::onWorkerStart` – здесь добавим код таймера для пинга пользователей.

###### Примечание

Протокол WebSocket имеет встроенную реализацию ping/pong из коробки, но мы напишем собственную, в которой сможем выполнять дополнительные действия. Однако клиент будет дополнительно оповещать сервер о наличии подключения, используя встроенную реализацию.

```php
$worker->onWorkerStart = function($worker) use (&$connections)
{
    $interval = 5; // пингуем каждые 5 секунд


    Timer::add($interval, function() use(&$connections) {
        foreach ($connections as $c) {
            // Если ответ от клиента не пришел 3 раза, то удаляем соединение из списка
            // и оповещаем всех участников об "отвалившемся" пользователе
            if ($c->pingWithoutResponseCount >= 3) {
                $messageData = [
                    'action' => 'ConnectionLost',
                    'userId' => $c->id,
                    'userName' => $c->userName,
                    'gender' => $c->gender,
                    'userColor' => $c->userColor
                ];
                $message = json_encode($messageData);
                
                unset($connections[$c->id]); 
                $c->destroy(); // уничтожаем соединение
                
                // рассылаем оповещение
                foreach ($connections as $c) {
                    $c->send($message);
                }
            }
            else {
                $c->send('{"action":"Ping"}');
                $c->pingWithoutResponseCount++; // увеличиваем счетчик пингов
            }
        }
    });
};
```

#### Обработка сообщений

И самое главное - обработка сообщений клиента, которые приходят с вызовом метода `Worker::onMessage`:

```php
$worker->onMessage = function($connection, $message) use (&$connections)
{
    // распаковываем json
    $messageData = json_decode($message, true);
    // проверяем наличие ключа 'toUserId', который используется для отправки приватных сообщений
    $toUserId = isset($messageData['toUserId']) ? (int) $messageData['toUserId'] : 0;
    $action = isset($messageData['action']) ? $messageData['action'] : '';
    
    if ($action == 'Pong') {
        // При получении сообщения "Pong", обнуляем счетчик пингов
        $connection->pingWithoutResponseCount = 0;
    }
    else {
        // Все остальные сообщения дополняем данными об отправителе
        $messageData['userId'] = $connection->id;
        $messageData['userName'] = $connection->userName;
        $messageData['gender'] = $connection->gender;
        $messageData['userColor'] = $connection->userColor;
        
        // Преобразуем специальные символы в HTML-сущности в тексте сообщения
        $messageData['text'] = htmlspecialchars($messageData['text']);
        // Заменяем текст заключенный в фигурные скобки на жирный
        // (позже будет описано зачем и почему)
        $messageData['text'] = preg_replace('/\{(.*)\}/u', '<b>\\1</b>', $messageData['text']);
        
        if ($toUserId == 0) {
            // Отправляем сообщение всем пользователям
            $messageData['action'] = 'PublicMessage';
            foreach ($connections as $c) {
                $c->send(json_encode($messageData));
            }
        }
        else {
            $messageData['action'] = 'PrivateMessage';
            if (isset($connections[$toUserId])) {
                // Отправляем приватное сообщение указанному пользователю
                $connections[$toUserId]->send(json_encode($messageData));
                // и отправителю
                $connections->send(json_encode($messageData));
            }
            else {
                $messageData['text'] = 'Не удалось отправить сообщение выбранному пользователю';
                $connection->send(json_encode($messageData));
            }
        }
    }
};
```

На этом с сервером закончили. Полный код файла `ChatWorker.php` доступен на [GitHub](https://github.com/wxmaper/SimpleChat-server):

После тестирования websocket-сервер можно запустить в режиме службы, для этого нужно добавить параметр `-d`:

```
php ChatWorker.php start -d
```

Перезапуск выполняется командой restart:

```
php ChatWorker.php restart -d
```

Полная остановка:

```
php ChatWorker.php stop
```

### 3. Клиент

Примитивное приложение-клиент будет реализовано с использованием модуля `QtWidgets` и написано на `C++`. Сообщения будут отображаться в обычном текстовом поле в режиме readonly (привет любителям лампового IRC :-)).

Приложение будет иметь возможность подсвечивать разными цветами имена пользователей, а так же вставлять имя пользователя в поле сообщения при клике на его ник.

Режим приватного чата активируется двойным кликом по имени в списке пользователей, а закрывается этот режим специальной кнопкой `[x]`, которая по умолчанию скрыта.

Сообщения отправляются нажатием на кнопку Return (Enter) на клавиатуре.

![Прототип окна чата](https://wxmaper.ru/pub/simplychat/scr6.png)

Диалог авторизации будет вызываться сразу после запуска приложения, а так же при разрыве соединений.

![](https://wxmaper.ru/pub/simplychat/scr7.png)

Весь проект доступен на [GitHub](https://github.com/wxmaper/SimpleChat-client):

В рамках этой статьи рассмотрим лишь основные моменты.

#### Установка соединения с сервером

В проекте реализован метод `Widget::connectToServer`, он открывает диалог авторизации. Если диалог будет закрыт (`result != AuthDialog::Accepted`), то приложение закроется вместе с ним.

Если же ввести все авторизационные данные, то будет осуществлена попытка подключения к серверу.

```c
void Widget::connectToServer()
{
    AuthDialog authDialog(this);
    authDialog.setConnectionData(m_connectionData);

    int result = authDialog.exec();

    if (result == AuthDialog::Accepted) {
        m_connectionData = authDialog.connectionData();

        QString html = QString("%1 "
                               "Установка соединения с %2:%3...")
                .arg(datetime())
                .arg(m_connectionData.server)
                .arg(m_connectionData.port);
        ui->textBrowser->append(html);

        m_webSocket->open(QUrl(QString("ws://%1:%2?userName=%3&userColor=%4&gender=%5")
                               .arg(m_connectionData.server)
                               .arg(m_connectionData.port)
                               .arg(m_connectionData.userName)
                               .arg(QString(m_connectionData.userColor).replace("#","%23"))
                               .arg(m_connectionData.gender)));
    }
    else {
        qApp->quit();
    }
}
```

#### Обработка сообщений

Вся логика общения клиента с сервером реализована в слоте `Widget::onTextMessageReceived` – тут проверяется тип входящего сообщения (какое он имеет «действие») и вызываются соответствующие методы для его обработки.

```c
void Widget::onTextMessageReceived(const QString &message)
{
    // Преобразуем полученное сообщение в JSON-объект
    QJsonObject messageData = QJsonDocument::fromJson(message.toUtf8()).object();

    QString action = messageData.value("action").toString();

    if (action == "Ping") {
        // В ответ на "Ping" клиент должен послать действие "Pong",
        // чтобы сервер понял, что клиент в онлайне
        sendPong();
    }
    else {
        int userId = messageData.value("userId").toInt();
        QString userName = messageData.value("userName").toString();
        Gender gender = Gender(messageData.value("gender").toInt());
        QString userColor = messageData.value("userColor").toString();

        if (action == "Authorized") {
            onUserAuthorized(userId, userName, gender);
            QJsonArray users = messageData.value("users").toArray();
            addUsers(users);
        }

        else if (action == "Connected") {
            onUserConnected(userId, userName, gender, userColor);
        }

        else if (action == "Disconnected") {
            onUserDisconnected(userId, userName, gender, userColor);
        }

        else if (action == "ConnectionLost") {
            onConnectionLost(userId, userName, gender, userColor);
        }

        else if (action == "PublicMessage") {
            QString text = messageData.value("text").toString();
            onPublicMessage(userId, userName, userColor, text);
        }

        else if (action == "PrivateMessage") {
            QString text = messageData.value("text").toString();
            onPrivateMessage(userId, userName, userColor, text);
        }

        else {
            // неизвестное действие, можно добавить оповещение 
            qWarning() << "unknown action: " << action;
        }
    }
}
```

#### Оповещение пользователя

Если кликнуть по имени пользователя, то имя вставляется в поле ввода сообщения и заворачивается в фигурные скобки: «`{`» и «`}`».

На сервере имеется паттерн обработки такого текста (функция `$worker->onMessage`), который заменяет фигурные скобки на теги «`<b>`» и «`</b>`», выделяя текст жирным шрифтом.

Таким образом, когда пользователь получает сообщение, можно проверить наличие этих тегов и содержимого в них. Если в тегах содержится имя текущего пользователя, значит, в сообщении кто-то упомянул этого пользователя и надо его об этом уведомить. Это реализовано в методе обработки публичных сообщений:

```c
void Widget::onPublicMessage(int userId,
                             const QString &userName,
                             const QString &userColor,
                             const QString &text)
{
    if (text.contains("" + m_userName + "")) {
        qApp->beep();
        qApp->alert(this);
    }

    QString html = QString("%1 %3:"
                           " %4")
            .arg(datetime())
            .arg(userColor)
            .arg(userName)
            .arg(text)
            .arg(userId);

    ui->textBrowser->append(html);
}
```

По такой же схеме можно реализовать полноценную поддержку markdown, вставку смайликов и картинок. 

Расширяя функционал сервера и клиента можно также добавить:

поддержку чат-комнат и полноценных приватных диалогов;
сохранении истории сообщений в БД и её отправку при подключении или по запросу;
статусы пользователей («Работаю», «Отдыхаю», «Отошёл» и др.);
звуковые уведомления «Послать сигнал»;
редактирование и удаление сообщений;
цитирование сообщений других пользователей;
передачу файлов.
Скриншот получившегося чата был в начале статьи, дополнительно приведу пример реального чата, реализованного по описанной в статье модели

![](https://wxmaper.ru/pub/simplychat/scr8.png)
