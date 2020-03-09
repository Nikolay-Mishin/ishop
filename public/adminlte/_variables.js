/**
 * consts - пользовательские константы PHP (get_defined_constants(true)['user'])
 * path - ссылка на главную - абсолютный путь (для ajax-запросов и другого)
 * adminpath - ссылка на главную админки - абсолютный путь (для ajax-запросов и другого)
 * Метод Object.freeze() замораживает объект: это значит, что он предотвращает добавление новых свойств к объекту, удаление старых свойств из объекта и изменение существующих свойств или значения их атрибутов перечисляемости, настраиваемости и записываемости. В сущности, объект становится эффективно неизменным. Метод возвращает замороженный объект.
 */

Object.freeze(Ishop); // замораживает объект
Object.freeze(Ishop.consts); // замораживает объект

// выгружаем свойства из объекта Ishop (деструктуризация)
const { consts, path, adminpath } = Ishop;
console.log(Ishop);