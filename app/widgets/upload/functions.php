<?php
function can_upload ($file) {
    $formats = array ('jpg', 'png', 'gif', 'bmp', 'jpeg'); // объявим массив допустимых расширений
    
    if ($file['name'] == '') return 'Вы не выбрали файл.'; // если имя пустое, значит файл не выбран
    
    // если размер файла 0, значит его не пропустили настройки сервера из-за того, что он слишком большой
    if ($file['size'] == 0) return 'Файл слишком большой или пустой.';

    $fi = finfo_open (FILEINFO_MIME_TYPE); // Создадим ресурс FileInfo
    $mime = (string) finfo_file ($fi, $file['tmp_name']); // Получим MIME-тип
    finfo_close ($fi); // Закроем ресурс
    
    // Проверим ключевое слово image (image/jpeg, image/png и т. д.)
    if (strpos ($mime, 'image') === false) return 'Можно загружать только изображения.';
    // Проверим форматы image (jpeg, png и т. д.)
    $format = explode ('/', $mime)[1];
    $str = implode (", ", $formats);
    if (!in_array ($format, $formats)) return "Можно загружать только изображения в форматах: $str";
    
    return true;
}

function make_upload ($file, $name) { copy ($file['tmp_name'], 'img/' . $name); }
?>