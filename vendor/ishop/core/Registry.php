<?php
// класс для регистрации (отлова) исключений из ошибок
// класс Реестр / хранит все параметры приложения
// реализует паттерн Одиночка (Singletone)

namespace ishop;

class Registry {
    // для использования (подключения) трейта используется директива use
    use TSingletone; // трейд Singletone для создание единичного экземпляра класса

    protected static $properties = []; // контейнер для свойств

    // сеттер - кладет (устанавливает) свойство в контейнер (имя - значение)
    public function setProperty($name, $value){
        self::$properties[$name] = $value;
    }

    // геттер - получение свойства по имени
    public function getProperty($name){
        // если свойство существует, возвращаем его, иначе null
        if(isset(self::$properties[$name])){
            return self::$properties[$name];
        }
        return null;
    }

    // возвращает все свойства
    public function getProperties(){
        return self::$properties;
    }

}