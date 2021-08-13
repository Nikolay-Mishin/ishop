<?php
/**
 * Загрузка картинки из формы
 * @see https://denisyuk.by/all/polnoe-rukovodstvo-po-zagruzke-izobrazheniy-na-php/#paragraph-6
 */

include_once 'file-handler.php'; // Подключим функцию для обработки загрузки файла в единичном экземляре

$files = $_FILES; // Полученные файлы
$done_files = []; // Массив для хранения данных об результате загрузки файлов
if (!array_key_exists (0, $files)) sort ($files);

// Загружаем все картинки по порядку
foreach ($files as $k => $v) $done_files[] = load ($files[$k]['tmp_name'], $files[$k]['error'], 'uploads');

// Запишем в переменную ассоциативный массив с результатом загрузки файла
$result = $done_files ? ['files' => $files, 'info' => $done_files] : ['error' => 'Ошибка загрузки файлов.'];
$json = json_encode ($result); // Конвертируем полученный массив данных в json формат
echo $json; // Выведем в ответ сформированные данные

//print_r ($files);
//print_r ($result);
