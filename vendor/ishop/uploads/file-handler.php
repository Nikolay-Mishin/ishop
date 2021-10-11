<?php
/**
 * Загрузка картинки из формы
 * @see http://denisyuk.by/all/polnoe-rukovodstvo-po-zagruzke-izobrazheniy-na-php/
 */

// Функция, возвращающая ассоциативный массив с сообщением об успешной записи файла и сопутствующие данные
function msg ($uploadPath, $name, $format) {
    return array (
        'status' => 'Sucess!',
        'path' => __DIR__ . "\\$uploadPath\\$name$format",
        'dir' => __DIR__,
        'folder' => $uploadPath,
        'name' => $name,
        'format' => preg_split ('/\./', $format)[1]
    );
}

// Функция, возвращающая ассоциативный массив с сообщением об ошибке
function error ($error) {
    return array ('error' => $error);
}

// функция для обработки загрузки файла в единичном экземляре
function load ($filePath, $errorCode, $uploadPath = 'pics') {
    // Зададим ограничения для картинок
    $formats = array ('jpg', 'jpeg', 'png', 'gif');
    $mBytes = 1048576;
    $limitBytes = 10 * $mBytes;
    
    if (!is_dir ($uploadPath)) mkdir ($uploadPath, 0777); // Создадим директорию, если она отсутсвует

    // Проверим на ошибки
    if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file ($filePath)) {
        // Массив с названиями ошибок
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
            UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
            UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
            UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
            UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
        ];
        
        $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.'; // Зададим неизвестную ошибку
        // Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
        $outputMessage = isset ($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
        
        return error ($outputMessage); // Выведем название ошибки
    }
    
    $fi = finfo_open (FILEINFO_MIME_TYPE); // Создадим ресурс FileInfo
    $mime = (string) finfo_file ($fi, $filePath); // Получим MIME-тип
    finfo_close ($fi); // Закроем ресурс

    // Проверим ключевое слово image (image/jpeg, image/png и т. д.)
    if (strpos ($mime, 'image') === false) return error ('Можно загружать только изображения.');
    $str = implode (", ", $formats); // Проверим форматы image (jpeg, png и т. д.)
    if (strpos ($str, explode ('/', $mime)[1]) === false) return error ("Можно загружать только изображения в форматах: $str");

    // Проверим нужные параметры
    if (filesize ($filePath) > $limitBytes) return error ("Размер изображения не должен превышать $limitBytes Мбайт.");

    $image = getimagesize ($filePath); // Результат функции запишем в переменную

    //$limitWidth  = 2000;
    //$limitHeight = 1500;
    //if ($image[0] > $limitWidth) return error ("Ширина изображения не должна превышать $limitWidth точек.");
    //if ($image[1] > $limitHeight) return error ("Высота изображения не должна превышать $limitHeight точек.");
    
    $name = md5_file ($filePath); // Сгенерируем новое имя файла на основе MD5-хеша
    $extension = image_type_to_extension ($image[2]); // Сгенерируем расширение файла на основе типа картинки
    $format = str_replace ('jpeg', 'jpg', $extension); // Сократим .jpeg до .jpg

    // Переместим картинку с новым именем и расширением в папку /pics
    if (move_uploaded_file ($filePath, __DIR__ . "/$uploadPath/$name$format")) return msg ($uploadPath, $name, $format);
    else return error ('При записи изображения на диск произошла ошибка.');
}
