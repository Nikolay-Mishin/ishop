/**
 * consts - пользовательские константы PHP (get_defined_constants(true)['user'])
 * path - ссылка на главную - абсолютный путь (для ajax-запросов и другого)
 * course - текущий курс валюты
 * symboleLeft - символ слева ($ 1)
 * symboleRight - символ справа (1 руб.)
 */

// выгружаем свойства из объекта Ishop (деструктуризация)
const { consts, path, course, symboleLeft, symboleRight } = Ishop;
