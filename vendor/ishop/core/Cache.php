<?php
// класс для кэширования - снижает нагрузку на БД
// реализует паттерн Singletone
// например, формирование меню (каталога/категорий) - взятие данных из БД и рекурсия (проход нескольких циклов в одном цикле)
// формируем меню 1 раз и записываем в кэш - при повторном обращении к сайту берем его из кэша или формируем из БД и записываем в кэш

namespace ishop;

class Cache {

    // запись в кэш
    public static function set(string $key, array|string $data, int $seconds = 3600, bool $noDelete = false): bool {
        // $key - уникальное имя файла кэша
        // $data - данные для кэширования
        // $seconds - время кэширования данных в сек (на 1ч)
        // если время кэширования > 0 - кэшируем данные (в целях тестирования ставится в 0, чтобы временно не кэшировать данные)
        if ($seconds || $noDelete) {
            $content['data'] = $data; // записываем переданные данные в массив
            $content['end_time'] = time() + $seconds; // записываем в массив конечное время кэширования (текущие время + время кэша)
            $content['no_delete'] = $seconds == 0 && $noDelete ? true : false;
            // записываем данные в кэш
            // md5($key) - хэшируем ключ имени кэша
            // serialize($content) - сериализует весь контент (переводит в строковый формат)
            if (file_put_contents(CACHE.'/'.md5($key).'.txt', serialize($content))) {
                return true;
            }
        }
        return false;
    }

    // получение кэша
    public static function get(string $key): array|string|null {
        $file = CACHE.'/'.md5($key).'.txt'; // путь к файлу кэша по ключу
        // если файл существует вынимает контент из кэша
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file)); // десериализуем контент из файла (преобразовываем из строки в массив)
            // проверяем не устарели ли данные в кэше и возвращаем их, иначе удаляем файл
            if (time() <= $content['end_time'] || $content['no_delete']) {
                return $content['data'];
            }
            unlink($file);
        }
        return null;
    }

    // удаление/очистка кэша
    public static function delete(string $key): void {
        $file = CACHE.'/'.md5($key).'.txt'; // путь к файлу кэша по ключу
        // если файл существует, удаляем его
        if (file_exists($file)) {
            unlink($file);
        }
    }

}
