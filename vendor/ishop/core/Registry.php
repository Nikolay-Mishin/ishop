<?php
// класс Реестр - хранит все параметры приложения
// реализует паттерн Одиночка (Singletone)

namespace ishop;

use ishop\traits\TSingletone;

class Registry {

    use \ishop\traits\TSingletone; // подключаем трейт Singletone с помощью директивы (служебного слова) use

    protected static $properties = [];

    // сеттер - инициализирует (устанавливает) свойство реестра (ключ - значение)
    public function setProperty($name, $value){
        self::$properties[$name] = $value;
    }

    // геттер - получает свойство из реестра по имени
    public function getProperty($name){
        // если значение есть в реестре возвращаем его, иначе null
        if(isset(self::$properties[$name])){
            return self::$properties[$name];
        }
        return null;
    }

    // возвращает контейнер (свойство) $properties
    public function getProperties(){
        return self::$properties;
    }

}
